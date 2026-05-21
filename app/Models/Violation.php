<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_id',
        'type',
        'count',
        'total_amount',
        'description',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'count'        => 'integer',
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeDenda($query)
    {
        return $query->where('type', 'denda');
    }

    public function scopeLateReturn($query)
    {
        return $query->where('type', 'late_return');
    }

    public function scopeDamage($query)
    {
        return $query->where('type', 'damage');
    }

    // ─── Helpers ───────────────────────────────────────────────

    /**
     * Human-readable label for violation type.
     */
    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'denda'       => 'Denda',
            'late_return' => 'Pengembalian Terlambat',
            'damage'      => 'Kerusakan Buku',
            default       => ucfirst($this->type),
        };
    }

    /**
     * Bootstrap color variant for badges.
     */
    public function getBadgeClass(): string
    {
        return match ($this->type) {
            'denda'       => 'bg-danger',
            'late_return' => 'bg-warning text-dark',
            'damage'      => 'bg-info text-dark',
            default       => 'bg-secondary',
        };
    }

    /**
     * Formatted total_amount in Rupiah, or '-' when null.
     */
    public function getFormattedAmount(): string
    {
        if (is_null($this->total_amount)) {
            return '-';
        }

        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}