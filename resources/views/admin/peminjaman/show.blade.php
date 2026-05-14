@extends('layouts.admin')
@section('title', 'Detail Peminjaman')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Detail Peminjaman</h4>
</div>

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="text-muted small mb-1">Anggota</div>
                <div class="fw-semibold">{{ $peminjaman->anggota->nama ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Buku</div>
                <div class="fw-semibold">{{ $peminjaman->buku->judul ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Tanggal Pinjam</div>
                <div class="fw-semibold">{{ optional($peminjaman->tgl_pinjam)->format('d/m/Y') ?: '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Rencana Kembali</div>
                <div class="fw-semibold">{{ optional($peminjaman->tgl_kembali_rencana)->format('d/m/Y') ?: '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Status Approval</div>
                <div class="fw-semibold">{{ ucfirst($peminjaman->approval_status) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Status Pengembalian</div>
                <div class="fw-semibold">{{ ucfirst($peminjaman->return_status) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Status Buku</div>
                <div class="fw-semibold">{{ ucfirst($peminjaman->status) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small mb-1">Denda</div>
                <div class="fw-semibold">Rp {{ number_format($peminjaman->denda ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small mb-1">Catatan</div>
                <div class="fw-semibold">{{ $peminjaman->approval_note ?: $peminjaman->return_note ?: '-' }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
