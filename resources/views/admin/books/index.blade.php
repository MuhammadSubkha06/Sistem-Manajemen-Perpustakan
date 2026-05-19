@extends('layouts.admin')
@section('title', 'Manajemen Buku')

@push('styles')
<style>
    /* ─── Grid ───────────────────────────────────────────── */
    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.4rem;
    }

    /* ─── Card ───────────────────────────────────────────── */
    .book-card {
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #eef0f3;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        transition: transform .22s ease, box-shadow .22s ease;
        display: flex;
        flex-direction: column;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 32px rgba(0,0,0,.11);
    }

    /* ─── Cover ──────────────────────────────────────────── */
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
        transition: transform .35s ease;
    }
    .book-card:hover .book-cover img { transform: scale(1.06); }

    .book-cover::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.5) 0%, transparent 55%);
        pointer-events: none;
    }
    .book-cover::before {
        content: '';
        position: absolute; top: 0; left: 0;
        width: 8px; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,.18), transparent);
        z-index: 1; pointer-events: none;
    }

    .cover-placeholder {
        width: 100%; height: 100%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: .45rem; color: #b0b7c3;
        background: linear-gradient(145deg, #fef9ec, #fef3c7);
    }
    .cover-placeholder span {
        font-size: .58rem; letter-spacing: .08em;
        text-transform: uppercase; color: #c9a94a;
    }
    .cover-fallback {
        position: absolute;
        inset: 0;
    }

    /* Badges on cover */
    .cover-cat {
        position: absolute; top: 9px; left: 9px; z-index: 2;
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(4px);
        border-radius: 6px; padding: 3px 9px;
        font-size: .67rem; font-weight: 700; color: #374151;
        max-width: 130px; overflow: hidden;
        text-overflow: ellipsis; white-space: nowrap;
        box-shadow: 0 1px 4px rgba(0,0,0,.1);
    }
    .cover-stok {
        position: absolute; top: 9px; right: 9px; z-index: 2;
        width: 27px; height: 27px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .67rem; font-weight: 700;
        box-shadow: 0 1px 5px rgba(0,0,0,.25);
    }

    /* ─── Info ───────────────────────────────────────────── */
    .book-info {
        padding: .8rem 1rem;
        background: #fff; flex: 1;
    }
    .b-title {
        font-size: .9rem; font-weight: 700; color: #111;
        line-height: 1.35; margin: 0 0 .28rem;
        display: -webkit-box;
        -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .b-author {
        font-size: .76rem; color: #adb5bd;
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; margin: 0;
    }

    /* ─── Actions ────────────────────────────────────────── */
    .book-actions {
        display: flex;
        border-top: 1px solid #f1f3f5;
    }
    .book-actions a,
    .book-actions button {
        flex: 1; padding: .52rem 0;
        font-size: .76rem; font-weight: 600;
        border: none; background: transparent;
        cursor: pointer; text-align: center;
        text-decoration: none; transition: background .15s;
        display: flex; align-items: center;
        justify-content: center; gap: 4px;
    }
    .act-view { color: #2563eb; border-right: 1px solid #f1f3f5; }
    .act-edit { color: #d97706; border-right: 1px solid #f1f3f5; }
    .act-del  { color: #dc2626; }
    .book-actions a:hover      { background: #f8f9ff; }
    .book-actions button:hover { background: #fff5f5; }

    /* ─── Empty State ────────────────────────────────────── */
    .empty-grid {
        grid-column: 1 / -1;
        text-align: center;
        padding: 5rem 1rem;
        color: #ced4da;
    }
    .empty-grid .empty-icon {
        width: 80px; height: 80px;
        background: #f8f9fa;
        border-radius: 50%;
        display: inline-flex;
        align-items: center; justify-content: center;
        margin-bottom: 1.2rem;
        font-size: 1.8rem; color: #dee2e6;
    }

    /* ─── Custom Pagination ──────────────────────────────── */
    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: 2rem;
        padding: 1rem 1.25rem;
        background: #fff;
        border: 1px solid #eef0f3;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
    }

    .pagination-info {
        font-size: .82rem;
        color: #9ca3af;
    }
    .pagination-info strong {
        color: #374151;
    }

    .pagination-nav {
        display: flex;
        align-items: center;
        gap: .35rem;
        list-style: none;
        margin: 0; padding: 0;
    }
    .pagination-nav li a,
    .pagination-nav li span {
        display: inline-flex;
        align-items: center; justify-content: center;
        min-width: 34px; height: 34px;
        padding: 0 .5rem;
        border-radius: 8px;
        font-size: .82rem; font-weight: 600;
        text-decoration: none;
        transition: all .15s;
        border: 1px solid transparent;
    }

    /* Normal page links */
    .pagination-nav li a {
        color: #374151;
        background: #f8f9fa;
        border-color: #eef0f3;
    }
    .pagination-nav li a:hover {
        background: #fef3c7;
        border-color: #fcd34d;
        color: #92400e;
    }

    /* Active page */
    .pagination-nav li.active span {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
        box-shadow: 0 3px 10px rgba(245,158,11,.35);
    }

    /* Disabled prev/next */
    .pagination-nav li.disabled span {
        color: #d1d5db;
        background: #f9fafb;
        border-color: #f3f4f6;
        cursor: not-allowed;
    }

    /* Prev / Next arrows */
    .pagination-nav li.prev a,
    .pagination-nav li.next a {
        background: #fff;
        border-color: #e5e7eb;
        color: #374151;
        font-size: .8rem;
    }
    .pagination-nav li.prev a:hover,
    .pagination-nav li.next a:hover {
        background: #f59e0b;
        border-color: #f59e0b;
        color: #fff;
    }

    /* Dots */
    .pagination-nav li.dots span {
        background: transparent;
        border-color: transparent;
        color: #9ca3af;
        pointer-events: none;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="fas fa-book me-2 text-warning"></i>Manajemen Buku
        </h4>
        <small class="text-muted">{{ $books->total() }} buku terdaftar</small>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-warning btn-sm fw-semibold px-3">
        <i class="fas fa-plus me-1"></i> Tambah Buku
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter --}}
<div class="card border-0 rounded-3 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari judul, pengarang, ISBN..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="kategori_id" class="form-select form-select-sm">
                    <option value="">— Semua Kategori —</option>
                    @foreach($kategoriList as $k)
                        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Book Grid --}}
<div class="book-grid">
    @forelse($books as $book)
        <div class="book-card">

            <div class="book-cover">
                @if($book->cover)
                    <img
                        src="{{ $book->coverUrl('M') }}"
                        alt="{{ $book->judul }}"
                        loading="lazy"
                        decoding="async"
                        referrerpolicy="no-referrer"
                        onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');"
                    >
                    <div class="cover-placeholder cover-fallback d-none">
                        <i class="fas fa-book-open fa-2x"></i>
                        <span>No Cover</span>
                    </div>
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

            <div class="book-actions">
                <a href="{{ route('admin.books.show', $book) }}" class="act-view">
                    <i class="fas fa-eye"></i> Detail
                </a>
                <a href="{{ route('admin.books.edit', $book) }}" class="act-edit">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                      onsubmit="return confirm('Hapus buku ini?')"
                      style="flex:1;display:flex;">
                    @csrf @method('DELETE')
                    <button type="submit" class="act-del" style="flex:1;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>

        </div>
    @empty
        <div class="empty-grid">
            <div class="empty-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <p class="fw-semibold mb-1 text-muted">Belum ada buku ditemukan</p>
            <small class="text-muted">Coba ubah filter atau tambahkan buku baru</small>
        </div>
    @endforelse
</div>

{{-- ─── Custom Pagination ───────────────────────── --}}
@if($books->hasPages())
    <div class="pagination-wrapper">

        {{-- Info teks --}}
        <div class="pagination-info">
            Menampilkan
            <strong>{{ $books->firstItem() }}–{{ $books->lastItem() }}</strong>
            dari <strong>{{ $books->total() }}</strong> buku
        </div>

        {{-- Nav --}}
        <ul class="pagination-nav">

            {{-- Prev --}}
            <li class="prev {{ $books->onFirstPage() ? 'disabled' : '' }}">
                @if($books->onFirstPage())
                    <span><i class="fas fa-chevron-left" style="font-size:.7rem"></i></span>
                @else
                    <a href="{{ $books->previousPageUrl() }}">
                        <i class="fas fa-chevron-left" style="font-size:.7rem"></i>
                    </a>
                @endif
            </li>

            {{-- Pages --}}
            @php
                $current   = $books->currentPage();
                $last      = $books->lastPage();
                $window    = 2; // pages on each side
                $showFirst = $current > $window + 1;
                $showLast  = $current < $last - $window;
            @endphp

            {{-- First page --}}
            @if($showFirst)
                <li {{ $current == 1 ? 'class=active' : '' }}>
                    <a href="{{ $books->url(1) }}">1</a>
                </li>
                @if($current > $window + 2)
                    <li class="dots"><span>…</span></li>
                @endif
            @endif

            {{-- Window around current --}}
            @for($p = max(1, $current - $window); $p <= min($last, $current + $window); $p++)
                <li class="{{ $p == $current ? 'active' : '' }}">
                    @if($p == $current)
                        <span>{{ $p }}</span>
                    @else
                        <a href="{{ $books->url($p) }}">{{ $p }}</a>
                    @endif
                </li>
            @endfor

            {{-- Last page --}}
            @if($showLast)
                @if($current < $last - $window - 1)
                    <li class="dots"><span>…</span></li>
                @endif
                <li>
                    <a href="{{ $books->url($last) }}">{{ $last }}</a>
                </li>
            @endif

            {{-- Next --}}
            <li class="next {{ !$books->hasMorePages() ? 'disabled' : '' }}">
                @if(!$books->hasMorePages())
                    <span><i class="fas fa-chevron-right" style="font-size:.7rem"></i></span>
                @else
                    <a href="{{ $books->nextPageUrl() }}">
                        <i class="fas fa-chevron-right" style="font-size:.7rem"></i>
                    </a>
                @endif
            </li>

        </ul>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.querySelector('form[method="GET"]');
        if (filterForm) {
            const searchInput = filterForm.querySelector('input[name="search"]');
            const categorySelect = filterForm.querySelector('select[name="kategori_id"]');
            let searchTimeout;

            // Auto-submit when category changes
            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    setTimeout(() => {
                        filterForm.submit();
                    }, 100);
                });
            }

            // Auto-submit search with debounce (after user stops typing for 500ms)
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500);
                });
            }
        }
    });
</script>
@endpush

@endsection
