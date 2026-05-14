<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'stok',
        'cover',
        'deskripsi',
    ];

    // Mengambil kategori yang dimiliki buku.
    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'buku_kategori');
    }

    // Mengambil semua data peminjaman buku.
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    // Mencari buku berdasarkan judul, pengarang, atau ISBN.
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
                ->orWhere('pengarang', 'like', "%{$keyword}%")
                ->orWhere('isbn', 'like', "%{$keyword}%");
        });
    }

    // Mengambil buku yang stoknya masih tersedia.
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }
}
