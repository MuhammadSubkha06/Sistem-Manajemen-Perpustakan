<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class UserBookController extends Controller
{
    // Menampilkan katalog buku untuk anggota.
    public function index(Request $request)
    {
        $query = Buku::with('kategoris');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('kategori_id')) {
            $query->whereHas('kategoris', fn ($q) => $q->where('kategoris.id', $request->kategori_id));
        }

        $books = $query->latest()->paginate(12)->withQueryString();
        $kategoriList = Kategori::orderBy('nama_kategori')->get();

        return view('user.books.index', compact('books', 'kategoriList'));
    }

    // Menampilkan detail buku untuk anggota.
    public function show(Buku $book)
    {
        $book->load('kategoris');

        return view('user.books.show', compact('book'));
    }
}
