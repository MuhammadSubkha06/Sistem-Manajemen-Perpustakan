@extends('layouts.admin')
@section('title', 'Detail Buku')

@push('styles')
<style>
    /* Cover portrait card */
    .cover-portrait {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 16px 40px rgba(0,0,0,.18);
        aspect-ratio: 2 / 3;
        position: relative;
        background: #e5e7eb;
    }
    .cover-portrait img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }
    /* Spine effect */
    .cover-portrait::before {
        content: '';
        position: absolute; top: 0; left: 0;
        width: 10px; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,.22), transparent);
        z-index: 1; pointer-events: none;
    }
    .cover-none {
        width: 100%; height: 100%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: .75rem; color: #9ca3af;
        background: linear-gradient(135deg, #fef3c7, #fffbeb);
    }

    /* Meta grid */
    .meta-box {
        background: #f9fafb;
        border-radius: 10px;
        padding: 1rem 1.25rem;
    }
    .meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .5rem 0;
        border-bottom: 1px solid #f3f4f6;
        font-size: .875rem;
    }
    .meta-row:last-child { border-bottom: none; }
    .meta-label { color: #9ca3af; font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
    .meta-value { font-weight: 600; color: #111; text-align: right; }

    /* Loan history table */
    .loan-table th { font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: #9ca3af; font-weight: 600; }
    .loan-table td { font-size: .85rem; vertical-align: middle; }
</style>
@endpush

@section('content')

{{-- Breadcrumb / back --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.books.index') }}" class="text-decoration-none">Manajemen Buku</a>
            </li>
            <li class="breadcrumb-item active text-truncate" style="max-width:220px">{{ $book->judul }}</li>
        </ol>
    </nav>
</div>

<div class="row g-4 align-items-start">

    {{-- Cover --}}
    <div class="col-5 col-md-4 col-lg-3">
        <div class="cover-portrait">
            @if($book->cover)
                @if(str_starts_with($book->cover, 'http'))
                    <img src="{{ $book->cover }}" alt="{{ $book->judul }}">
                @else
                    <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}">
                @endif
            @else
                <div class="cover-none">
                    <i class="fas fa-book-open fa-3x text-warning"></i>
                    <small class="text-muted">Tidak ada cover</small>
                </div>
            @endif
        </div>
    </div>

    {{-- Info --}}
    <div class="col-7 col-md-8 col-lg-9">
        <div class="card border-0 rounded-3 shadow-sm p-4">

            {{-- Kategori chips --}}
            <div class="mb-3">
                @forelse($book->kategoris as $k)
                    <span class="badge bg-light text-dark border fw-semibold me-1">{{ $k->nama_kategori }}</span>
                @empty
                    <span class="badge bg-light text-muted border">Tanpa kategori</span>
                @endforelse
            </div>

            <h4 class="fw-bold mb-1">{{ $book->judul }}</h4>
            <p class="text-muted mb-3">
                <i class="fas fa-pen-nib me-1" style="font-size:.75rem"></i>{{ $book->pengarang }}
            </p>

            {{-- Stok badge --}}
            @if($book->stok > 0)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 mb-3">
                    <i class="fas fa-circle-check me-1"></i> Tersedia &mdash; {{ $book->stok }} buku
                </span>
            @else
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 mb-3">
                    <i class="fas fa-circle-xmark me-1"></i> Stok Habis
                </span>
            @endif

            {{-- Meta --}}
            <div class="meta-box mb-3">
                <div class="meta-row">
                    <span class="meta-label">Penerbit</span>
                    <span class="meta-value">{{ $book->penerbit ?: '—' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Tahun Terbit</span>
                    <span class="meta-value">{{ $book->tahun_terbit ?: '—' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">ISBN</span>
                    <span class="meta-value">{{ $book->isbn ?: '—' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Stok</span>
                    <span class="meta-value">{{ $book->stok }}</span>
                </div>
            </div>

            {{-- Deskripsi --}}
            <p class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.07em">Deskripsi</p>
            <p class="text-secondary mb-4" style="font-size:.9rem;line-height:1.7">
                {{ $book->deskripsi ?: 'Belum ada deskripsi buku.' }}
            </p>

            {{-- Actions --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning btn-sm fw-semibold">
                    <i class="fas fa-pen me-1"></i> Edit Buku
                </a>
                <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm fw-semibold">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Loan History --}}
@if($book->peminjaman->count())
    <h5 class="fw-bold mt-4 mb-3">
        <i class="fas fa-clock-rotate-left me-2 text-warning"></i>Riwayat Peminjaman
    </h5>
    <div class="card border-0 rounded-3 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 loan-table">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3">#</th>
                        <th class="py-3">Anggota</th>
                        <th class="py-3">Tgl Pinjam</th>
                        <th class="py-3">Tgl Kembali</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($book->peminjaman as $i => $p)
                        <tr>
                            <td class="ps-4">{{ $i + 1 }}</td>
                            <td>{{ $p->anggota->name ?? '—' }}</td>
                            <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '—' }}</td>
                            <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '—' }}</td>
                            <td>
                                @php
                                    $map = ['dipinjam' => 'warning', 'dikembalikan' => 'success', 'terlambat' => 'danger'];
                                    $cls = $map[$p->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $cls }}">{{ ucfirst($p->status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection