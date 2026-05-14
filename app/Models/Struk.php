<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'anggota_id',
        'peminjaman_id',
        'jenis',
        'judul',
        'nominal',
        'payload',
        'issued_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'issued_at' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public static function buatUntukPeminjaman(Peminjaman $peminjaman, string $jenis, string $judul, int $nominal = 0): self
    {
        $peminjaman->loadMissing(['anggota', 'buku']);

        return self::updateOrCreate(
            [
                'peminjaman_id' => $peminjaman->id,
                'jenis' => $jenis,
            ],
            [
                'kode' => self::kodeBaru($jenis, $peminjaman->id),
                'anggota_id' => $peminjaman->anggota_id,
                'judul' => $judul,
                'nominal' => $nominal,
                'issued_at' => now(),
                'payload' => [
                    'anggota' => [
                        'nis' => $peminjaman->anggota->nis ?? '-',
                        'nama' => $peminjaman->anggota->nama ?? '-',
                        'kelas' => $peminjaman->anggota->kelas ?? '-',
                    ],
                    'buku' => [
                        'judul' => $peminjaman->buku->judul ?? '-',
                        'pengarang' => $peminjaman->buku->pengarang ?? '-',
                        'isbn' => $peminjaman->buku->isbn ?? '-',
                    ],
                    'tanggal' => [
                        'pinjam' => optional($peminjaman->tgl_pinjam)->format('d/m/Y'),
                        'kembali_rencana' => optional($peminjaman->tgl_kembali_rencana)->format('d/m/Y'),
                        'kembali_aktual' => optional($peminjaman->tgl_kembali_aktual)->format('d/m/Y'),
                    ],
                    'status' => [
                        'approval' => $peminjaman->approval_status,
                        'peminjaman' => $peminjaman->status,
                        'pengembalian' => $peminjaman->return_status,
                    ],
                ],
            ]
        );
    }

    private static function kodeBaru(string $jenis, int $peminjamanId): string
    {
        $prefix = match ($jenis) {
            'peminjaman' => 'PJM',
            'pengembalian' => 'KMB',
            'pembayaran_denda' => 'DND',
            default => 'STR',
        };

        return $prefix . '-' . now()->format('YmdHis') . '-' . str_pad((string) $peminjamanId, 4, '0', STR_PAD_LEFT);
    }
}
