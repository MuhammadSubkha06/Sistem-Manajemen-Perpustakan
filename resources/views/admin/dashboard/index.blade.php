@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Beranda Admin</h4>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}.</p>
    </div>
    <span class="badge bg-warning text-dark px-3 py-2">
        <i class="fas fa-calendar-day me-1"></i>{{ now()->translatedFormat('d F Y') }}
    </span>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Total Buku</div>
                <div class="fw-bold fs-5">{{ number_format($totalBuku) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Total Anggota</div>
                <div class="fw-bold fs-5">{{ number_format($totalAnggota) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Sedang Dipinjam</div>
                <div class="fw-bold fs-5">{{ number_format($totalDipinjam) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Total Denda</div>
                <div class="fw-bold fs-5">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 rounded-3 shadow-sm mb-4">
    <div class="card-body p-3">
        <div class="row g-2">
            <div class="col-md-2 col-6"><a href="{{ route('admin.books.create') }}" class="btn btn-outline-warning w-100">Tambah Buku</a></div>
            <div class="col-md-2 col-6"><a href="{{ route('admin.members.create') }}" class="btn btn-outline-primary w-100">Tambah Anggota</a></div>
            <div class="col-md-2 col-6"><a href="{{ route('admin.peminjaman.create') }}" class="btn btn-outline-success w-100">Tambah Pinjam</a></div>
            <div class="col-md-3 col-6"><a href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}" class="btn btn-outline-dark w-100">Approval Pinjam</a></div>
            <div class="col-md-3 col-12"><a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline-secondary w-100">Approval Pengembalian</a></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card border-0 rounded-3 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4">
                <h6 class="fw-bold mb-0">Ringkasan Approval</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Menunggu persetujuan pinjam</span>
                    <span class="fw-semibold">{{ number_format($pendingPeminjaman) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>Menunggu persetujuan return</span>
                    <span class="fw-semibold">{{ number_format($pendingPengembalian) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span>Peminjaman terlambat</span>
                    <span class="fw-semibold">{{ $terlambat->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 rounded-3 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4">
                <h6 class="fw-bold mb-0">Peminjaman Terbaru</h6>
            </div>
            <div class="card-body p-0">
                @forelse($recentPinjam as $pinjam)
                    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">{{ $pinjam->buku->judul ?? '-' }}</div>
                            <div class="text-muted small">{{ $pinjam->anggota->nama ?? '-' }}</div>
                        </div>
                        <span class="badge {{ $pinjam->approval_status === 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                            {{ $pinjam->approval_status === 'pending' ? 'Pending' : ucfirst($pinjam->status) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Belum ada transaksi.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card border-0 rounded-3 shadow-sm mt-4">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center gap-3">
        <h6 class="fw-bold mb-0">Buku Terpopuler</h6>
        <span class="text-muted small">Berdasarkan jumlah peminjaman</span>
    </div>
    <div class="card-body p-4">
        @php
            $maxPeminjaman = max(1, (int) $bukuTerpopuler->max('peminjaman_count'));
        @endphp

        @forelse($bukuTerpopuler as $book)
            @php
                $jumlahPinjam = (int) $book->peminjaman_count;
                $barWidth = $jumlahPinjam > 0 ? max(8, round(($jumlahPinjam / $maxPeminjaman) * 100)) : 0;
                $ranking = $loop->iteration;
                $rankingColors = ['text-warning', 'text-secondary', 'text-danger', 'text-primary', 'text-info'];
                $rankingIcons = ['fa-medal', 'fa-award', 'fa-trophy', 'fa-star', 'fa-bookmark'];
            @endphp
            <div class="popular-book-row" data-ranking="{{ $ranking }}">
                <div class="row g-2 align-items-center">
                    <div class="col-auto" style="width: 40px;">
                        <div class="popular-book-rank">
                            <i class="fas {{ $rankingIcons[$ranking - 1] }} {{ $rankingColors[$ranking - 1] }}"></i>
                            <span class="rank-number">{{ $ranking }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="fw-semibold text-truncate" style="font-size: 0.95rem;">{{ $book->judul }}</div>
                        <div class="text-muted small text-truncate">{{ $book->pengarang ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="popular-book-track">
                            <div class="popular-book-bar" style="width: {{ $barWidth }}%; animation-delay: {{ ($loop->iteration - 1) * 0.1 }}s;"></div>
                        </div>
                    </div>
                    <div class="col-md-1 text-end">
                        <span class="fw-bold badge bg-light text-dark">{{ number_format($jumlahPinjam) }}x</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">Belum ada data peminjaman buku.</div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    /* Popular Books Chart Styling */
    .popular-book-row {
        padding: 0.75rem 0;
        transition: all 0.3s ease;
        border-radius: 8px;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .popular-book-row:hover {
        background: rgba(13, 110, 253, 0.05);
        transform: translateX(4px);
    }

    .popular-book-row + .popular-book-row {
        margin-top: 0.5rem;
    }

    /* Ranking Badge */
    .popular-book-rank {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        animation: scaleIn 0.5s ease;
    }

    .popular-book-rank i {
        font-size: 1.1rem;
        position: absolute;
    }

    .popular-book-rank .rank-number {
        font-size: 0.7rem;
        position: absolute;
        bottom: 2px;
        right: 2px;
        background: white;
        color: #667eea;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Track Container */
    .popular-book-track {
        height: 24px;
        background: linear-gradient(90deg, #f0f2f5 0%, #e8ecf1 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    /* Animated Bar */
    .popular-book-bar {
        height: 100%;
        min-width: 24px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4), inset 0 2px 4px rgba(255, 255, 255, 0.3);
        animation: slideIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        position: relative;
    }

    .popular-book-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        border-radius: 12px;
        animation: shimmer 2s infinite;
    }

    @keyframes slideIn {
        from {
            width: 0;
            opacity: 0;
        }
        to {
            width: var(--bar-width);
            opacity: 1;
        }
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    /* Hover Effect for Bar */
    .popular-book-row:hover .popular-book-bar {
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.3);
    }

    /* Count Badge */
    .popular-book-row .badge {
        font-size: 0.85rem;
        padding: 0.35rem 0.6rem !important;
        border-radius: 6px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@endsection
