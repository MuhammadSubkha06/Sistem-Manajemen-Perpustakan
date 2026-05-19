<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function coverUrl(string $size = 'M'): ?string
    {
        if (!$this->cover) {
            return null;
        }

        if (str_starts_with($this->cover, 'http')) {
            return $this->optimizedExternalCoverUrl($this->cover, $size);
        }

        return Storage::url($this->cover);
    }

    private function optimizedExternalCoverUrl(string $url, string $size): string
    {
        $size = in_array($size, ['S', 'M', 'L'], true) ? $size : 'M';
        $url = preg_replace('/^http:\/\//i', 'https://', $url);

        if (str_contains($url, 'covers.openlibrary.org')) {
            return preg_replace('/-(S|M|L)\.jpg(\?.*)?$/i', '-' . $size . '.jpg$2', $url) ?: $url;
        }

        return $url;
    }
}
