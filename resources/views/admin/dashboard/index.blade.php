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
        <h6 class="fw-bold mb-0">
            <i class="fas fa-fire text-warning me-2"></i>5 Buku Terpopuler
        </h6>
        <span class="text-muted small">Berdasarkan jumlah peminjaman</span>
    </div>
    <div class="card-body p-4">
        @if($bukuTerpopuler->count() > 0)
            @php
                $maxPeminjaman = max(1, (int) $bukuTerpopuler->max('peminjaman_count'));
            @endphp

            <div class="popular-books-container">
                @foreach($bukuTerpopuler as $book)
                    @php
                        $jumlahPinjam = (int) $book->peminjaman_count;
                        $barWidth = $jumlahPinjam > 0 ? max(10, round(($jumlahPinjam / $maxPeminjaman) * 100)) : 0;
                        $ranking = $loop->iteration;
                        $medalIcons = ['fa-medal', 'fa-award', 'fa-trophy', 'fa-star', 'fa-bookmark'];
                        $medalColors = ['#FFD700', '#C0C0C0', '#CD7F32', '#FFC107', '#FF9800'];
                    @endphp
                    <div class="popular-book-item" data-ranking="{{ $ranking }}">
                        <div class="d-flex align-items-center gap-3">
                            <!-- Ranking Badge -->
                            <div class="popular-book-medal" style="color: {{ $medalColors[$ranking - 1] }};">
                                <i class="fas {{ $medalIcons[$ranking - 1] }}"></i>
                                <span class="medal-number">{{ $ranking }}</span>
                            </div>

                            <!-- Book Info -->
                            <div class="popular-book-info flex-grow-1 min-width-0">
                                <div class="fw-semibold text-truncate" style="font-size: 0.95rem;">
                                    {{ $book->judul }}
                                </div>
                                <div class="text-muted small text-truncate">
                                    {{ $book->pengarang ?? '-' }}
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="popular-book-bar-container" style="min-width: 250px;">
                                <div class="popular-book-bar-track">
                                    <div class="popular-book-bar-fill" style="width: {{ $barWidth }}%; animation-delay: {{ ($loop->iteration - 1) * 0.1 }}s;">
                                        <span class="bar-label">{{ $jumlahPinjam }}x</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-book fa-2x mb-3 d-block opacity-50"></i>
                <p>Belum ada data peminjaman buku.</p>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* Popular Books Container */
    .popular-books-container {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }

    /* Individual Book Item */
    .popular-book-item {
        padding: 1rem;
        background: linear-gradient(135deg, #fff9e6 0%, #fffbf0 100%);
        border-left: 4px solid #FFC107;
        border-radius: 8px;
        transition: all 0.3s ease;
        animation: slideInLeft 0.5s ease forwards;
        animation-delay: calc(var(--ranking, 1) * 0.1s);
    }

    .popular-book-item:hover {
        background: linear-gradient(135deg, #fff5cc 0%, #ffede0 100%);
        transform: translateX(8px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
    }

    /* Medal Icon */
    .popular-book-medal {
        position: relative;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fff9e6 0%, #ffe082 100%);
        border-radius: 50%;
        font-size: 1.5rem;
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        flex-shrink: 0;
    }

    .medal-number {
        position: absolute;
        bottom: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        background: #FFC107;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        border: 2px solid white;
    }

    /* Book Info */
    .popular-book-info {
        min-width: 0;
    }

    /* Progress Bar Container */
    .popular-book-bar-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Progress Bar Track */
    .popular-book-bar-track {
        flex-grow: 1;
        height: 28px;
        background: linear-gradient(90deg, #f5f5f5 0%, #eeeeee 100%);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        border: 1px solid #e0e0e0;
    }

    /* Progress Bar Fill */
    .popular-book-bar-fill {
        height: 100%;
        min-width: 28px;
        background: linear-gradient(90deg, #FFC107 0%, #FFB300 50%, #FFA500 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.4), inset 0 1px 2px rgba(255, 255, 255, 0.3);
        animation: fillWidth 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        position: relative;
        overflow: hidden;
    }

    .popular-book-bar-fill::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }

    .bar-label {
        position: relative;
        z-index: 1;
        font-size: 0.75rem;
        font-weight: bold;
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    /* Animations */
    @keyframes fillWidth {
        from {
            width: 0;
            opacity: 0;
        }
        to {
            width: var(--bar-width);
            opacity: 1;
        }
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
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

    /* Hover Effect */
    .popular-book-item:hover .popular-book-bar-fill {
        box-shadow: 0 4px 16px rgba(255, 193, 7, 0.6), inset 0 1px 2px rgba(255, 255, 255, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .popular-book-bar-container {
            min-width: 150px;
        }

        .popular-book-item {
            flex-direction: column;
        }

        .bar-label {
            font-size: 0.65rem;
        }
    }
</style>
@endpush

@endsection
