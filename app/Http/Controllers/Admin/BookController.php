<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Menampilkan daftar buku dengan filter pencarian dan kategori.
    public function index(Request $request)
    {
        $query = Buku::with('kategoris');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                    ->orWhere('pengarang', 'like', "%{$request->search}%")
                    ->orWhere('isbn', 'like', "%{$request->search}%");
            });
        }

        if ($request->kategori_id) {
            $query->whereHas(
                'kategoris',
                fn($q) =>
                $q->where('kategoris.id', $request->kategori_id) 
            );
        }

        $books = $query->latest()->paginate(20);
        
        $kategoriList = Kategori::all();

        return view('admin.books.index', compact('books', 'kategoriList'));
    }

    // Menampilkan form tambah buku.
    public function create()
    {
        $kategoriList = Kategori::orderBy('nama_kategori')->get();

        return view('admin.books.create', compact('kategoriList'));
    }

    // Menyimpan data buku baru.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'pengarang' => 'required|string|max:100',
            'penerbit' => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20|unique:buku,isbn',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_url' => 'nullable|url|max:2048',
            'cover_source' => 'required|in:file,url',
            'kategori_ids' => 'nullable|array',
            'kategori_ids.*' => 'exists:kategoris,id',
        ]);

        $validated['cover'] = $this->resolveCover($request, null);

        $buku = Buku::create($validated);

        if ($request->filled('kategori_ids')) {
            $buku->kategoris()->sync($request->kategori_ids);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    // Menampilkan detail buku.
    public function show(Buku $book)
    {
        $book->load(['kategoris', 'peminjaman.anggota']);

        return view('admin.books.show', compact('book'));
    }

    // Menampilkan form edit buku.
    public function edit(Buku $book)
    {
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $selectedKategori = $book->kategoris->pluck('id')->toArray();

        return view('admin.books.edit', compact('book', 'kategoriList', 'selectedKategori'));
    }

    // Memperbarui data buku.
    public function update(Request $request, Buku $book)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'pengarang' => 'required|string|max:100',
            'penerbit' => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_url' => 'nullable|url|max:2048',
            'cover_source' => 'required|in:file,url',
            'remove_cover' => 'nullable|boolean',
            'kategori_ids' => 'nullable|array',
            'kategori_ids.*' => 'exists:kategoris,id',
        ]);

        // Hapus cover lama jika diminta atau akan diganti dengan yang baru
        $newCover = $this->resolveCover($request, $book);

        if ($newCover !== $book->cover) {
            $this->deleteStoredCover($book->cover);
        }

        $validated['cover'] = $newCover;

        $book->update($validated);
        $book->kategoris()->sync($request->kategori_ids ?? []);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    // Menghapus buku jika tidak sedang dipinjam.
    public function destroy(Buku $book)
    {
        if ($book->peminjaman()->where('status', 'dipinjam')->exists()) {
            return back()->with('error', 'Buku tidak dapat dihapus karena masih dipinjam.');
        }

        $this->deleteStoredCover($book->cover);

        $book->kategoris()->detach();
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Tentukan nilai kolom `cover` berdasarkan input dari request.
     *
     * Prioritas:
     *  1. Jika cover_source = 'file' dan ada file yang diupload → simpan file, kembalikan path.
     *  2. Jika cover_source = 'url' dan ada URL → kembalikan URL string.
     *  3. Jika remove_cover dicentang → kembalikan null.
     *  4. Fallback → pertahankan nilai lama (jika edit) atau null (jika create).
     *
     * @param  Request   $request
     * @param  Buku|null $book     Buku yang sedang diedit, null jika create.
     * @return string|null
     */
    private function resolveCover(Request $request, ?Buku $book): ?string
    {
        // 1. Upload file
        if ($request->cover_source === 'file' && $request->hasFile('cover')) {
            return $request->file('cover')->store('covers', 'public');
        }

        // 2. URL gambar
        if ($request->cover_source === 'url' && $request->filled('cover_url')) {
            return $request->cover_url;
        }

        // 3. Hapus cover (hanya pada edit)
        if ($book && $request->boolean('remove_cover')) {
            return null;
        }

        // 4. Pertahankan cover lama
        return $book?->cover;
    }

    /**
     * Hapus file cover dari storage (hanya jika bukan URL eksternal).
     *
     * @param  string|null $cover
     * @return void
     */
    private function deleteStoredCover(?string $cover): void
    {
        if ($cover && !str_starts_with($cover, 'http')) {
            Storage::disk('public')->delete($cover);
        }
    }
}