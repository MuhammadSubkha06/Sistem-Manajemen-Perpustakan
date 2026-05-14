<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Struk;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    // Mengarahkan filter ke daftar peminjaman.
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    // Menampilkan daftar peminjaman sesuai filter.
    public function index(Request $request)
    {
        $query = Peminjaman::with(['buku', 'anggota']);

        if ($request->filled('status')) {
            if ($request->status === 'terlambat') {
                $query->terlambat();
            } elseif (in_array($request->status, ['pending', 'approved', 'rejected'], true)) {
                $query->where('approval_status', $request->status);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('tgl_dari')) {
            $query->whereDate('tgl_pinjam', '>=', $request->tgl_dari);
        }

        if ($request->filled('tgl_sampai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->tgl_sampai);
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    // Menampilkan form tambah peminjaman.
    public function create()
    {
        $anggota = Anggota::orderBy('nama')->get();
        $buku = Buku::tersedia()->orderBy('judul')->get();

        return view('admin.peminjaman.create', compact('anggota', 'buku'));
    }

    // Menyimpan peminjaman yang dibuat admin.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id'          => 'required|exists:anggotas,id',
            'buku_id'             => 'required|exists:buku,id',
            'tgl_pinjam'          => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam',
        ]);

        $sudahPinjam = Peminjaman::where('anggota_id', $validated['anggota_id'])
            ->where('buku_id', $validated['buku_id'])
            ->whereIn('approval_status', ['pending', 'approved'])
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->withErrors(['buku_id' => 'Anggota ini sudah meminjam buku tersebut dan belum dikembalikan.'])->withInput();
        }

        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok < 1) {
            return back()->withErrors(['buku_id' => 'Stok buku habis.'])->withInput();
        }

        $buku->decrement('stok');

        $peminjaman = Peminjaman::create(array_merge($validated, [
            'status' => 'dipinjam',
            'approval_status' => 'approved',
            'approved_at' => now(),
            'return_status' => 'none',
            'denda' => 0,
        ]));

        Struk::buatUntukPeminjaman($peminjaman, 'peminjaman', 'Bukti Peminjaman Buku');

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    // Menampilkan detail peminjaman.
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['buku', 'anggota']);

        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    // Menampilkan form edit peminjaman.
    public function edit(Peminjaman $peminjaman)
    {
        $peminjaman->load(['buku', 'anggota']);

        return view('admin.peminjaman.edit', compact('peminjaman'));
    }

    // Memperbarui tanggal peminjaman.
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam',
        ]);

        $peminjaman->update($validated);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    // Menghapus data peminjaman yang tidak aktif.
    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->isApprovedLoan()) {
            return back()->with('error', 'Peminjaman aktif tidak dapat dihapus.');
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman dihapus.');
    }

    // Menyetujui permohonan peminjaman.
    public function approve(Peminjaman $peminjaman)
    {
        if (!$peminjaman->isPendingApproval()) {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        if ($peminjaman->buku->stok < 1) {
            return back()->with('error', 'Stok buku sudah habis. Permohonan tidak bisa disetujui.');
        }

        $duplicate = Peminjaman::where('anggota_id', $peminjaman->anggota_id)
            ->where('buku_id', $peminjaman->buku_id)
            ->where('id', '!=', $peminjaman->id)
            ->where('approval_status', 'approved')
            ->where('status', 'dipinjam')
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'Anggota sudah memiliki peminjaman aktif untuk buku ini.');
        }

        $peminjaman->buku->decrement('stok');
        $peminjaman->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approval_note' => null,
        ]);

        Struk::buatUntukPeminjaman($peminjaman->fresh(['anggota', 'buku']), 'peminjaman', 'Bukti Peminjaman Buku');

        return back()->with('success', 'Permohonan peminjaman berhasil disetujui.');
    }

    // Menolak permohonan peminjaman.
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if (!$peminjaman->isPendingApproval()) {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'approval_note' => ['nullable', 'string', 'max:255'],
        ]);

        $peminjaman->update([
            'approval_status' => 'rejected',
            'approval_note' => $validated['approval_note'] ?? 'Permohonan ditolak oleh admin.',
        ]);

        return back()->with('success', 'Permohonan peminjaman berhasil ditolak.');
    }
}
