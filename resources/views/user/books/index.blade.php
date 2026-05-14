@extends('layouts.user')
@section('title', 'Katalog Buku')

@push('styles')
<style>
    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 1.25rem;
    }

    .book-card {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        border: 1px solid rgba(0,0,0,.07);
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
        transition: transform .2s ease, box-shadow .2s ease;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
    }
    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,0,0,.13);
        color: inherit;
        text-decoration: none;
    }

    /* Cover */
    .book-cover {
        position: relative;
        aspect-ratio: 2 / 3;
        overflow: hidden;
        background: #f3f4f6;
        flex-shrink: 0;
    }
    .book-cover img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform .3s ease;
    }
    .book-card:hover .book-cover img { transform: scale(1.05); }

    .book-cover::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.55) 0%, transparent 50%);
        pointer-events: none;
    }
    .book-cover::before {
        content: '';
        position: absolute; top: 0; left: 0;
        width: 8px; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,.2), transparent);
        z-index: 1; pointer-events: none;
    }

    .cover-placeholder {
        width: 100%; height: 100%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: .4rem; color: #9ca3af;
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
    }
    .cover-placeholder span { font-size: .58rem; letter-spacing: .06em; text-transform: uppercase; }

    .cover-cat {
        position: absolute; top: 9px; left: 9px; z-index: 2;
        background: rgba(255,255,255,.9);
        border-radius: 6px; padding: 3px 10px;
        font-size: .7rem; font-weight: 700; color: #374151;
        max-width: 140px; overflow: hidden;
        text-overflow: ellipsis; white-space: nowrap;
    }
    .cover-stok {
        position: absolute; top: 9px; right: 9px; z-index: 2;
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .68rem; font-weight: 700;
    }

    /* Info */
    .book-info {
        padding: .8rem 1rem .85rem;
        background: #fff; flex: 1;
    }
    .b-title {
        font-size: .95rem; font-weight: 700; color: #111;
        line-height: 1.35; margin: 0 0 .3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .b-author {
        font-size: .8rem; color: #9ca3af;
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; margin: 0;
    }

    .empty-grid {
        grid-column: 1 / -1;
        text-align: center; padding: 4rem 1rem; color: #d1d5db;
    }
</style>
@endpush

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-0">Katalog Buku</h4>
        <small class="text-muted">{{ $books->total() }} buku tersedia · Temukan yang kamu suka</small>
    </div>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary btn-sm align-self-start align-self-md-end">
        <i class="fas fa-plus me-1"></i> Ajukan Pinjam
    </a>
</div>

{{-- Filter --}}
<div class="card border-0 rounded-3 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari judul, pengarang, ISBN..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-fill">Cari</button>
                <a href="{{ route('user.books.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Grid --}}
<div class="book-grid">
    @forelse($books as $book)
        <a href="{{ route('user.books.show', $book) }}" class="book-card">

            <div class="book-cover">
                @if($book->cover)
                    @if(str_starts_with($book->cover, 'http'))
                        <img src="{{ $book->cover }}" alt="{{ $book->judul }}" loading="lazy">
                    @else
                        <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}" loading="lazy">
                    @endif
                @else
                    <div class="cover-placeholder">
                        <i class="fas fa-book-open fa-2x"></i>
                        <span>No Cover</span>
                    </div>
                @endif

                @if($book->kategoris->first())
                    <span class="cover-cat">{{ $book->kategoris->first()->nama_kategori }}</span>
                @endif
                <span class="cover-stok text-white {{ $book->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                    {{ $book->stok }}
                </span>
            </div>

            <div class="book-info">
                <p class="b-title">{{ $book->judul }}</p>
                <p class="b-author">{{ $book->pengarang }}</p>
            </div>

        </a>
    @empty
        <div class="empty-grid">
            <i class="fas fa-search fa-3x mb-3 d-block"></i>
            <p class="fw-semibold mb-0">Buku tidak ditemukan</p>
            <small>Coba kata kunci atau kategori lain</small>
        </div>
    @endforelse
</div>

@if($books->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $books->links() }}
    </div>
@endif

@endsection