@extends('layouts.user')
@section('title', 'Dashboard')

@section('content')

@php
    $cards = [
        ['label' => 'Dipinjam', 'value' => $stats['sedang_pinjam']],
        ['label' => 'Menunggu', 'value' => $stats['menunggu_persetujuan']],
        ['label' => 'Return', 'value' => $stats['pengembalian_pending']],
        ['label' => 'Denda', 'value' => 'Rp ' . number_format($stats['denda_aktif'], 0, ',', '.')],
    ];
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
    <div>
        <h1 class="page-title">Halo, {{ auth()->user()->name }}</h1>
        <p class="page-subtitle">Pantau peminjaman dan cari buku dari satu halaman.</p>
    </div>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary btn-sm align-self-md-start">
        <i class="fas fa-plus me-1"></i> Ajukan Pinjam
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach($cards as $card)
        <div class="col-6 col-lg-3">
            <div class="simple-card bg-white p-3 h-100">
                <div class="text-muted small">{{ $card['label'] }}</div>
                <div class="fw-bold fs-5 mt-1">{{ $card['value'] }}</div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="simple-card bg-white">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">Katalog Terbaru</h2>
                <a href="{{ route('user.books.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="p-3">
                <div class="row g-3">
                    @forelse($katalogBuku->take(4) as $book)
                        <div class="col-sm-6">
                            <a href="{{ route('user.books.show', $book) }}" class="text-decoration-none text-dark">
                                <div class="d-flex gap-3">
                                    <div class="book-cover-sm d-flex align-items-center justify-content-center">
                                        @if($book->cover)
                                            <img
                                                src="{{ $book->coverUrl('S') }}"
                                                alt="{{ $book->judul }}"
                                                loading="lazy"
                                                decoding="async"
                                                referrerpolicy="no-referrer"
                                                onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');"
                                            >
                                            <i class="fas fa-book-open text-primary d-none"></i>
                                        @else
                                            <i class="fas fa-book-open text-primary"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-semibold small">{{ $book->judul }}</div>
                                        <div class="text-muted small">{{ $book->pengarang }}</div>
                                        <span class="badge {{ $book->stok > 0 ? 'bg-success' : 'bg-danger' }} mt-2">
                                            {{ $book->stok > 0 ? "Stok {$book->stok}" : 'Habis' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-muted small">Belum ada buku.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="simple-card bg-white mt-4">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">Buku Terpopuler</h2>
                <a href="{{ route('user.books.index') }}" class="btn btn-outline-primary btn-sm">Katalog</a>
            </div>
            <div class="p-3">
                @php
                    $maxPeminjaman = max(1, (int) $bukuTerpopuler->max('peminjaman_count'));
                @endphp

                @forelse($bukuTerpopuler as $book)
                    @php
                        $jumlahPinjam = (int) $book->peminjaman_count;
                        $barWidth = $jumlahPinjam > 0 ? max(8, round(($jumlahPinjam / $maxPeminjaman) * 100)) : 0;
                        $ranking = $loop->iteration;
                        $rankingIcons = ['fa-medal', 'fa-award', 'fa-trophy', 'fa-star', 'fa-bookmark'];
                    @endphp
                    <div class="popular-book-row" data-ranking="{{ $ranking }}">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                <div class="popular-book-rank-small">
                                    <i class="fas {{ $rankingIcons[$ranking - 1] }}"></i>
                                </div>
                                <div class="fw-semibold small text-truncate" style="font-size: 0.9rem;">{{ $book->judul }}</div>
                            </div>
                            <div class="text-muted small flex-shrink-0 badge bg-light text-dark">{{ number_format($jumlahPinjam) }}x</div>
                        </div>
                        <div class="popular-book-track">
                            <div class="popular-book-bar" style="width: {{ $barWidth }}%; animation-delay: {{ ($loop->iteration - 1) * 0.1 }}s;"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted small">Belum ada data peminjaman buku.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="simple-card bg-white">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">Aktivitas Terbaru</h2>
                <a href="{{ route('user.peminjaman.index') }}" class="btn btn-outline-primary btn-sm">Detail</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($riwayat as $p)
                    <div class="list-group-item px-3 py-3">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-semibold small">{{ $p->buku->judul ?? '-' }}</div>
                                <div class="text-muted small">{{ optional($p->tgl_pinjam)->format('d/m/Y') ?: '-' }}</div>
                            </div>
                            <span class="badge h-100 {{ $p->approval_status === 'pending' ? 'bg-warning text-dark' : ($p->approval_status === 'rejected' ? 'bg-danger' : 'bg-success') }}">
                                {{ $p->approval_status === 'pending' ? 'Menunggu' : ($p->approval_status === 'rejected' ? 'Ditolak' : 'Disetujui') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-muted small">Belum ada aktivitas peminjaman.</div>
                @endforelse
            </div>
        </div>

        <div class="simple-card bg-white mt-4">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="h6 fw-bold mb-0">Struk Terbaru</h2>
                <a href="{{ route('user.struk.index') }}" class="btn btn-outline-primary btn-sm">Semua</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($strukTerbaru as $struk)
                    <div class="list-group-item px-3 py-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-semibold small">{{ $struk->judul }}</div>
                                <div class="text-muted small">
                                    {{ $struk->peminjaman->buku->judul ?? ($struk->payload['buku']['judul'] ?? '-') }}
                                </div>
                                <div class="text-muted small">
                                    {{ optional($struk->issued_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <a href="{{ route('user.struk.show', $struk) }}" class="btn btn-outline-secondary btn-sm flex-shrink-0">
                                Lihat
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-muted small">Belum ada struk dari admin.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Popular Books Chart - Compact Version */
    .popular-book-row {
        padding: 0.6rem 0.5rem;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .popular-book-row:hover {
        background: rgba(13, 110, 253, 0.05);
        transform: translateX(3px);
    }

    .popular-book-row + .popular-book-row {
        margin-top: 0.5rem;
    }

    /* Ranking Badge - Small */
    .popular-book-rank-small {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        color: white;
        font-size: 0.8rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        animation: scaleIn 0.5s ease;
    }

    /* Track Container */
    .popular-book-track {
        height: 18px;
        background: linear-gradient(90deg, #f0f2f5 0%, #e8ecf1 100%);
        border-radius: 9px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.08);
        position: relative;
    }

    /* Animated Bar */
    .popular-book-bar {
        height: 100%;
        min-width: 18px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        border-radius: 9px;
        box-shadow: 0 2px 6px rgba(102, 126, 234, 0.35), inset 0 1px 3px rgba(255, 255, 255, 0.3);
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
        border-radius: 9px;
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

    /* Hover Effect */
    .popular-book-row:hover .popular-book-bar {
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5), inset 0 1px 3px rgba(255, 255, 255, 0.3);
    }

    /* Count Badge */
    .popular-book-row .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem !important;
        border-radius: 4px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@endsection
