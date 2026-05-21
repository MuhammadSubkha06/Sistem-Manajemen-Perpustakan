<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role_id',
        'role',
        'suspended_at',
        'suspension_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mengatur tipe data otomatis pada atribut user.
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'suspended_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Mengecek apakah user berperan sebagai admin.
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Mengecek apakah user berperan sebagai anggota.
    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    // Mengambil data anggota yang terhubung ke user.
    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }

    // Mengecek apakah user di-suspend
    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    // Suspend user dengan alasan
    public function suspend(string $reason = ''): void
    {
        $this->update([
            'suspended_at' => now(),
            'suspension_reason' => $reason,
        ]);
    }

    // Unsuspend user
    public function unsuspend(): void
    {
        $this->update([
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);
    }

    // Scope untuk user yang tidak di-suspend
    public function scopeActive($query)
    {
        return $query->whereNull('suspended_at');
    }
}
