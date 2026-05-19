@extends('layouts.user')
@section('title', 'Detail Peminjaman')

@section('content')

@php
    $strukPeminjaman = $peminjaman->struks->firstWhere('jenis', 'peminjaman');
    $strukPengembalian = $peminjaman->struks->firstWhere('jenis', 'pengembalian');
@endphp

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('user.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Detail Peminjaman</h4>
</div>

<div class="card simple-card bg-white">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="text-muted small mb-1">Buku</div>
                <div class="fw-semibold">{{ $peminjaman->buku->judul ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Status Approval</div>
                <div class="fw-semibold">{{ ucfirst($peminjaman->approval_status) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Tanggal Pengajuan</div>
                <div class="fw-semibold">{{ optional($peminjaman->tgl_pinjam)->format('d/m/Y') ?: '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Rencana Kembali</div>
                <div class="fw-semibold">{{ optional($peminjaman->tgl_kembali_rencana)->format('d/m/Y') ?: '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Status Buku</div>
                <div class="fw-semibold">{{ ucfirst($peminjaman->status) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Status Pengembalian</div>
                <div class="fw-semibold">
                    @if($peminjaman->return_status === 'pending')
                        Menunggu persetujuan admin
                    @elseif($peminjaman->return_status === 'approved')
                        Disetujui admin
                    @elseif($peminjaman->return_status === 'rejected')
                        Ditolak admin
                    @else
                        Belum diajukan
                    @endif
                </div>
            </div>
            <div class="col-12">
                <div class="text-muted small mb-1">Catatan Admin</div>
                <div class="fw-semibold">{{ $peminjaman->approval_note ?: $peminjaman->return_note ?: '-' }}</div>
            </div>
        </div>

        @if($strukPeminjaman || $strukPengembalian)
            <div class="border-top mt-4 pt-4">
                <div class="fw-semibold mb-2">Struk Transaksi</div>
                <div class="d-flex flex-wrap gap-2">
                    @if($strukPeminjaman)
                        <a href="{{ route('user.struk.show', $strukPeminjaman) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-receipt me-1"></i> Bukti Peminjaman
                        </a>
                    @endif
                    @if($strukPengembalian)
                        <a href="{{ route('user.struk.show', $strukPengembalian) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-receipt me-1"></i> Bukti Pengembalian
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if($peminjaman->approval_status === 'approved' && in_array($peminjaman->status, ['dipinjam', 'terlambat'], true) && $peminjaman->return_status !== 'pending')
            <form method="POST" action="{{ route('user.peminjaman.request-return', $peminjaman) }}" class="mt-4" onsubmit="return confirm('Ajukan pengembalian buku ini?')">
                @csrf
                <button class="btn btn-primary btn-sm">
                    <i class="fas fa-rotate-left me-1"></i> Ajukan Pengembalian
                </button>
            </form>
        @elseif($peminjaman->return_status === 'pending')
            <div class="alert alert-info mt-4 mb-0">
                Pengembalian buku sudah diajukan dan sedang menunggu persetujuan admin.
            </div>
        @endif
    </div>
</div>

@endsection
