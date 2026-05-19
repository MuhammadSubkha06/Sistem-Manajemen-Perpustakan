<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Struk;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LibraryTransactionService
{
    public const MAX_ACTIVE_LOANS_PER_MEMBER = 3;

    public function refreshOverdueLoans(): int
    {
        return Peminjaman::where('approval_status', 'approved')
            ->where('status', 'dipinjam')
            ->where('tgl_kembali_rencana', '<', today()->toDateString())
            ->update(['status' => 'terlambat']);
    }

    public function createManualLoan(array $data): Peminjaman
    {
        return DB::transaction(function () use ($data) {
            $anggota = Anggota::lockForUpdate()->findOrFail($data['anggota_id']);
            $buku = Buku::lockForUpdate()->findOrFail($data['buku_id']);

            $this->ensureCanBorrow($anggota, $buku);
            $this->ensureStockAvailable($buku);

            $buku->decrement('stok');

            $peminjaman = Peminjaman::create([
                'anggota_id' => $anggota->id,
                'buku_id' => $buku->id,
                'tgl_pinjam' => $data['tgl_pinjam'],
                'tgl_kembali_rencana' => $data['tgl_kembali_rencana'],
                'status' => 'dipinjam',
                'approval_status' => 'approved',
                'approved_at' => now(),
                'return_status' => 'none',
                'denda' => 0,
            ]);

            Struk::buatUntukPeminjaman($peminjaman->load(['anggota', 'buku']), 'peminjaman', 'Bukti Peminjaman Buku');

            return $peminjaman;
        });
    }

    public function submitLoanRequest(Anggota $anggota, array $data): Peminjaman
    {
        return DB::transaction(function () use ($anggota, $data) {
            $anggota = Anggota::lockForUpdate()->findOrFail($anggota->id);
            $buku = Buku::lockForUpdate()->findOrFail($data['buku_id']);

            $this->ensureCanBorrow($anggota, $buku);
            $this->ensureStockAvailable($buku);

            return Peminjaman::create([
                'anggota_id' => $anggota->id,
                'buku_id' => $buku->id,
                'tgl_pinjam' => today(),
                'tgl_kembali_rencana' => $data['tgl_kembali_rencana'],
                'status' => 'dipinjam',
                'approval_status' => 'pending',
                'return_status' => 'none',
                'denda' => 0,
            ]);
        });
    }

    public function approveLoan(Peminjaman $peminjaman): void
    {
        DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::with(['anggota', 'buku'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);
            $buku = Buku::lockForUpdate()->findOrFail($peminjaman->buku_id);

            if (!$peminjaman->isPendingApproval()) {
                throw ValidationException::withMessages(['approval' => 'Permohonan ini sudah diproses sebelumnya.']);
            }

            $this->ensureCanBorrow($peminjaman->anggota, $buku, $peminjaman->id);
            $this->ensureStockAvailable($buku);

            $buku->decrement('stok');
            $peminjaman->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approval_note' => null,
            ]);

            Struk::buatUntukPeminjaman($peminjaman->fresh(['anggota', 'buku']), 'peminjaman', 'Bukti Peminjaman Buku');
        });
    }

    public function approveReturn(Peminjaman $peminjaman): int
    {
        return DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::with('buku')
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if (!$peminjaman->isApprovedLoan() || !$peminjaman->hasPendingReturn()) {
                throw ValidationException::withMessages(['return' => 'Permintaan pengembalian ini tidak valid atau sudah diproses.']);
            }

            $buku = Buku::lockForUpdate()->findOrFail($peminjaman->buku_id);

            $peminjaman->tgl_kembali_aktual = today();
            $denda = $peminjaman->hitungDenda();
            $peminjaman->denda = $denda;
            $peminjaman->status = 'dikembalikan';
            $peminjaman->return_status = 'approved';
            $peminjaman->return_processed_at = now();
            $peminjaman->return_note = null;
            $peminjaman->save();

            $buku->increment('stok');
            Struk::buatUntukPeminjaman($peminjaman->fresh(['anggota', 'buku']), 'pengembalian', 'Bukti Pengembalian Buku', $denda);

            return $denda;
        });
    }

    public function payFine(Peminjaman $peminjaman): int
    {
        return DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::with(['anggota', 'buku'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);
            $nominal = (int) $peminjaman->denda;

            if ($nominal < 1) {
                throw ValidationException::withMessages(['denda' => 'Denda sudah lunas.']);
            }

            Struk::buatUntukPeminjaman($peminjaman, 'pembayaran_denda', 'Bukti Pembayaran Denda', $nominal);
            $peminjaman->update(['denda' => 0]);

            return $nominal;
        });
    }

    private function ensureCanBorrow(Anggota $anggota, Buku $buku, ?int $ignoreLoanId = null): void
    {
        $activeLoanQuery = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereIn('approval_status', ['pending', 'approved']);

        if ($ignoreLoanId) {
            $activeLoanQuery->where('id', '!=', $ignoreLoanId);
        }

        if ($activeLoanQuery->exists()) {
            throw ValidationException::withMessages(['buku_id' => 'Anggota ini sudah memiliki peminjaman aktif atau pengajuan untuk buku tersebut.']);
        }

        $activeCountQuery = Peminjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereIn('approval_status', ['pending', 'approved']);

        if ($ignoreLoanId) {
            $activeCountQuery->where('id', '!=', $ignoreLoanId);
        }

        if ($activeCountQuery->count() >= self::MAX_ACTIVE_LOANS_PER_MEMBER) {
            throw ValidationException::withMessages([
                'anggota_id' => 'Anggota sudah mencapai batas maksimal ' . self::MAX_ACTIVE_LOANS_PER_MEMBER . ' pinjaman aktif/pending.',
            ]);
        }
    }

    private function ensureStockAvailable(Buku $buku): void
    {
        if ($buku->stok < 1) {
            throw ValidationException::withMessages(['buku_id' => 'Stok buku habis.']);
        }
    }
}
