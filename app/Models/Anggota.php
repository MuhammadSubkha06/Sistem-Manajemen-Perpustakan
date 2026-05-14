<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'kelas',
        'no_hp',
        'alamat',
    ];

    // Menghubungkan anggota ke akun user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mengambil semua riwayat peminjaman anggota.
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Mengambil pinjaman anggota yang masih aktif.
    public function peminjamanAktif()
    {
        return $this->hasMany(Peminjaman::class)
            ->where('status', 'dipinjam')
            ->where('approval_status', 'approved');
    }

    public function struks()
    {
        return $this->hasMany(Struk::class);
    }
}
