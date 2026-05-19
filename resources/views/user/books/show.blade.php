@extends('layouts.user')
@section('title', $book->judul)

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=DM+Sans:wght@400;500;600&display=swap');

    .show-wrap { font-family: 'DM Sans', sans-serif; }

    /* ── Cover Card ── */
    .cover-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,.18);
        aspect-ratio: 2/3;
        position: relative;
        background: #dde4ef;
    }
    .cover-card img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
    }
    .cover-placeholder-lg {
        width: 100%; height: 100%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 1rem; color: #9aa;
        background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
    }
    .cover-placeholder-lg i { font-size: 4rem; }
    .cover-fallback-lg {
        position: absolute;
        inset: 0;
    }

    /* spine effect */
    .cover-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 12px; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,.22), transparent);
        z-index: 1;
        pointer-events: none;
    }

    /* ── Info panel ── */
    .info-panel {
        background: #fff;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 2px 16px rgba(0,0,0,.06);
        height: 100%;
    }

    .book-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 900;
        color: #111;
        line-height: 1.2;
        margin-bottom: .4rem;
    }
    .book-author {
        color: #777;
        font-size: .95rem;
        margin-bottom: 1.25rem;
    }

    /* Category chips */
    .chip {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .04em;
        border: 1.5px solid #e0e0e0;
        color: #555;
        background: #fafafa;
        margin: 0 4px 4px 0;
    }

    /* Meta grid */
    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem 1.5rem;
        margin: 1.5rem 0;
        padding: 1.25rem;
        background: #f8f9fc;
        border-radius: 14px;
    }
    .meta-item .label {
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #aaa;
        margin-bottom: 2px;
    }
    .meta-item .value {
        font-size: .9rem;
        font-weight: 600;
        color: #222;
    }

    /* Stock badge */
    .stock-available {
        display: inline-flex; align-items: center; gap: .35rem;
        background: #dcfce7; color: #15803d;
        padding: 4px 12px; border-radius: 20px;
        font-size: .8rem; font-weight: 600;
    }
    .stock-empty {
        display: inline-flex; align-items: center; gap: .35rem;
        background: #fee2e2; color: #b91c1c;
        padding: 4px 12px; border-radius: 20px;
        font-size: .8rem; font-weight: 600;
    }

    /* Description */
    .desc-section h6 {
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #aaa;
        margin-bottom: .5rem;
    }
    .desc-section p {
        color: #444;
        font-size: .9rem;
        line-height: 1.7;
    }

    /* Borrow button */
    .btn-borrow {
        background: #111;
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: .75rem 1.75rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: .95rem;
        letter-spacing: .02em;
        transition: background .2s, transform .15s;
    }
    .btn-borrow:hover:not(:disabled):not(.disabled) {
        background: #333;
        color: #fff;
        transform: translateY(-2px);
    }
    .btn-borrow.disabled, .btn-borrow:disabled {
        background: #ccc; color: #fff; cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="show-wrap">

    {{-- Back nav --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('user.books.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <span class="text-muted small">Katalog Buku</span>
        <span class="text-muted small">/</span>
        <span class="small fw-semibold text-truncate" style="max-width:220px">{{ $book->judul }}</span>
    </div>

    <div class="row g-4 align-items-start">

        {{-- Cover --}}
        <div class="col-5 col-md-4 col-lg-3">
            <div class="cover-card">
                @if($book->cover)
                    <img
                        src="{{ $book->coverUrl('L') }}"
                        alt="{{ $book->judul }}"
                        decoding="async"
                        referrerpolicy="no-referrer"
                        onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');"
                    >
                    <div class="cover-placeholder-lg cover-fallback-lg d-none">
                        <i class="fas fa-book-open text-primary"></i>
                        <small class="text-muted">Tidak ada cover</small>
                    </div>
                @else
                    <div class="cover-placeholder-lg">
                        <i class="fas fa-book-open text-primary"></i>
                        <small class="text-muted">Tidak ada cover</small>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div class="col-7 col-md-8 col-lg-9">
            <div class="info-panel">

                {{-- Chips --}}
                <div class="mb-3">
                    @forelse($book->kategoris as $k)
                        <span class="chip">{{ $k->nama_kategori }}</span>
                    @empty
                        <span class="chip text-muted">Tanpa kategori</span>
                    @endforelse
                </div>

                <h1 class="book-title">{{ $book->judul }}</h1>
                <p class="book-author"><i class="fas fa-pen-nib me-1" style="font-size:.8rem"></i>{{ $book->pengarang }}</p>

                {{-- Stock --}}
                @if($book->stok > 0)
                    <span class="stock-available">
                        <i class="fas fa-circle-check" style="font-size:.75rem"></i>
                        Tersedia &mdash; {{ $book->stok }} buku
                    </span>
                @else
                    <span class="stock-empty">
                        <i class="fas fa-circle-xmark" style="font-size:.75rem"></i>
                        Stok Habis
                    </span>
                @endif

                {{-- Meta --}}
                <div class="meta-grid">
                    <div class="meta-item">
                        <div class="label">Penerbit</div>
                        <div class="value">{{ $book->penerbit ?: '—' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="label">Tahun Terbit</div>
                        <div class="value">{{ $book->tahun_terbit ?: '—' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="label">ISBN</div>
                        <div class="value">{{ $book->isbn ?: '—' }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="label">Stok</div>
                        <div class="value">{{ $book->stok }}</div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="desc-section mb-4">
                    <h6>Deskripsi</h6>
                    <p>{{ $book->deskripsi ?: 'Belum ada deskripsi buku.' }}</p>
                </div>

                {{-- CTA --}}
                <a href="{{ route('user.peminjaman.create') }}"
                   class="btn btn-borrow {{ $book->stok < 1 ? 'disabled' : '' }}">
                    <i class="fas fa-paper-plane me-2"></i> Ajukan Peminjaman
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
