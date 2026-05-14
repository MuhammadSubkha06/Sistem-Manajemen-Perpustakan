@extends('layouts.user')
@section('title', 'Detail Struk')

@section('content')

@php
    $payload = $struk->payload ?? [];
    $anggota = $payload['anggota'] ?? [];
    $buku = $payload['buku'] ?? [];
    $tanggal = $payload['tanggal'] ?? [];
@endphp

<div class="d-flex align-items-center justify-content-between gap-2 mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('user.struk.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="page-title">Detail Struk</h1>
    </div>
    <button class="btn btn-primary btn-sm" onclick="window.print()">
        <i class="fas fa-print me-1"></i> Cetak
    </button>
</div>

<div class="receipt simple-card bg-white mx-auto">
    <div class="p-4 border-bottom text-center">
        <img src="{{ asset('images/logo40.png') }}" alt="Logo Perpustakaan 40" class="mx-auto mb-2" style="width:54px;height:54px;object-fit:cover;border-radius:10px;">
        <div class="fw-bold fs-5">Perpustakaan 40</div>
        <div class="text-muted small">Bukti transaksi perpustakaan</div>
    </div>

    <div class="p-4">
        <div class="d-flex justify-content-between gap-3 mb-4">
            <div>
                <div class="text-muted small">Nomor Struk</div>
                <div class="fw-bold">{{ $struk->kode }}</div>
            </div>
            <div class="text-end">
                <div class="text-muted small">Tanggal Terbit</div>
                <div class="fw-bold">{{ optional($struk->issued_at)->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="alert alert-light border rounded-3">
            <div class="fw-bold">{{ $struk->judul }}</div>
            <div class="text-muted small">{{ str_replace('_', ' ', ucfirst($struk->jenis)) }}</div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="text-muted small">Nama Anggota</div>
                <div class="fw-semibold">{{ $anggota['nama'] ?? $struk->anggota->nama ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">NIS</div>
                <div class="fw-semibold">{{ $anggota['nis'] ?? $struk->anggota->nis ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Kelas</div>
                <div class="fw-semibold">{{ $anggota['kelas'] ?? $struk->anggota->kelas ?? '-' }}</div>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-sm">
                <tbody>
                    <tr>
                        <th style="width: 190px;">Judul Buku</th>
                        <td>{{ $buku['judul'] ?? $struk->peminjaman->buku->judul ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pengarang</th>
                        <td>{{ $buku['pengarang'] ?? $struk->peminjaman->buku->pengarang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>{{ $buku['isbn'] ?? $struk->peminjaman->buku->isbn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pinjam</th>
                        <td>{{ $tanggal['pinjam'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Rencana Kembali</th>
                        <td>{{ $tanggal['kembali_rencana'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Kembali Aktual</th>
                        <td>{{ $tanggal['kembali_aktual'] ?? '-' }}</td>
                    </tr>
                    @if($struk->nominal > 0)
                        <tr>
                            <th>Nominal</th>
                            <td class="fw-bold">Rp {{ number_format($struk->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="text-muted small">
            Struk ini dibuat otomatis saat admin memproses transaksi terkait.
        </div>
    </div>
</div>

@push('styles')
<style>
    .receipt { max-width: 760px; }
    @media print {
        nav, .btn, .alert-dismissible { display: none !important; }
        body { background: #fff; }
        .main-content { max-width: none; padding: 0; }
        .receipt { border: 0 !important; max-width: none; }
    }
</style>
@endpush

@endsection
