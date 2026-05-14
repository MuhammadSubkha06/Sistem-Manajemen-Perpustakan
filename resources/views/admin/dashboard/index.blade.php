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

@endsection
