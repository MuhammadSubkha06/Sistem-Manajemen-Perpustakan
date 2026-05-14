<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan semua kategori buku.
    public function index()
    {
        $categories = Kategori::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    // Menampilkan form tambah kategori.
    public function create()
    {
        return view('admin.categories.create');
    }

    // Menyimpan kategori baru.
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    // Menampilkan form edit kategori.
    public function edit(Kategori $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Memperbarui data kategori.
    public function update(Request $request, Kategori $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $category->id,
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    // Menghapus kategori jika belum dipakai buku.
    public function destroy(Kategori $category)
    {
        if ($category->buku()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh buku.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
