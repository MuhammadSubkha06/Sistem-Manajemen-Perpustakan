<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'status',
        'approval_status',
        'approval_note',
        'approved_at',
        'return_status',
        'return_note',
        'return_requested_at',
        'return_processed_at',
        'denda',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual' => 'date',
        'approved_at' => 'datetime',
        'return_requested_at' => 'datetime',
        'return_processed_at' => 'datetime',
    ];

    // Mengambil data buku dari peminjaman.
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    // Mengambil data anggota dari peminjaman.
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function struks()
    {
        return $this->hasMany(Struk::class);
    }

    // Menghitung denda berdasarkan jumlah hari terlambat.
    public function hitungDenda(): int
    {
        $aktual = $this->tgl_kembali_aktual ?? Carbon::today();
        $selisih = $this->tgl_kembali_rencana->diffInDays($aktual, false);

        return $selisih > 0 ? $selisih * 1000 : 0;
    }

    // Mengecek apakah pinjaman sudah melewati batas waktu.
    public function isTerlambat(): bool
    {
        return $this->isApprovedLoan()
            && Carbon::today()->isAfter($this->tgl_kembali_rencana);
    }

    // Mengecek apakah peminjaman masih menunggu persetujuan.
    public function isPendingApproval(): bool
    {
        return $this->approval_status === 'pending';
    }

    // Mengecek apakah peminjaman sudah disetujui dan masih dipinjam.
    public function isApprovedLoan(): bool
    {
        return $this->approval_status === 'approved'
            && $this->status === 'dipinjam';
    }

    // Mengecek apakah pengembalian masih menunggu persetujuan.
    public function hasPendingReturn(): bool
    {
        return $this->return_status === 'pending';
    }

    // Mengambil peminjaman aktif yang sudah disetujui.
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'dipinjam')
            ->where('approval_status', 'approved');
    }

    // Mengambil peminjaman yang menunggu persetujuan.
    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // Mengambil pengembalian yang menunggu persetujuan.
    public function scopePendingReturn($query)
    {
        return $query->where('return_status', 'pending');
    }

    // Mengambil peminjaman yang sudah terlambat.
    public function scopeTerlambat($query)
    {
        return $query->where('approval_status', 'approved')
            ->where(function ($q) {
                $q->where('status', 'terlambat')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'dipinjam')
                            ->where('tgl_kembali_rencana', '<', now()->toDateString());
                    });
            });
    }
}
