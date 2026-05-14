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
                                            <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}">
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
    </div>
</div>

@endsection
