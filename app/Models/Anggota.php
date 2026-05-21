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
        'suspended_at',
        'suspension_reason',
    ];

    protected $casts = [
        'suspended_at' => 'datetime',
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

    // Method untuk violations
    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    // Mengecek apakah anggota di-suspend
    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    // Suspend anggota dengan alasan
    public function suspend(string $reason = ''): void
    {
        $this->update([
            'suspended_at' => now(),
            'suspension_reason' => $reason,
        ]);

        // Suspend juga user yang terkait
        if ($this->user) {
            $this->user->suspend($reason);
        }
    }

    // Unsuspend anggota
    public function unsuspend(): void
    {
        $this->update([
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        // Unsuspend juga user yang terkait
        if ($this->user) {
            $this->user->unsuspend();
        }
    }

    // Hitung jumlah denda >= 3
    public function getDendaCount(): int
    {
        return $this->violations()
            ->where('type', 'denda')
            ->sum('count');
    }

    // Hitung jumlah pengembalian terlambat >= 3
    public function getLateReturnCount(): int
    {
        return $this->violations()
            ->where('type', 'late_return')
            ->sum('count');
    }

    // Check apakah perlu di-suspend berdasarkan kriteria
    public function shouldBeSuspended(): bool
    {
        return $this->getDendaCount() >= 3 || $this->getLateReturnCount() >= 3;
    }

    // Scope untuk anggota yang tidak di-suspend
    public function scopeActive($query)
    {
        return $query->whereNull('suspended_at');
    }
}
