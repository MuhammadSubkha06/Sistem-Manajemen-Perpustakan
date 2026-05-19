@extends('layouts.admin')
@section('title', 'Detail Struk')

@section('content')

@php
    $payload = $struk->payload ?? [];
    $anggota = $payload['anggota'] ?? [];
    $buku = $payload['buku'] ?? [];
    $tanggal = $payload['tanggal'] ?? [];
@endphp

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.struk.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Detail Struk</h4>
            <small class="text-muted">{{ $struk->kode }}</small>
        </div>
    </div>

    <div class="d-flex gap-2">
        @if($struk->is_approved)
            <span class="badge bg-success align-self-center px-3 py-2">Sudah disetujui</span>
        @else
            <form method="POST" action="{{ route('admin.struk.approve', $struk) }}" onsubmit="return confirm('Setujui pengiriman struk ini ke anggota?')">
                @csrf
                <button class="btn btn-success btn-sm fw-semibold">
                    <i class="fas fa-check me-1"></i> Setujui Struk
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-4">
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

                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th style="width:190px;">Judul Buku</th>
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
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">Status Pengiriman</h6>
            </div>
            <div class="card-body">
                @if($struk->is_approved)
                    <div class="alert alert-success mb-0">
                        Struk sudah dikirim ke halaman anggota.
                        <div class="small mt-2">
                            Disetujui: {{ optional($struk->approved_at)->format('d/m/Y H:i') }}<br>
                            Oleh: {{ $struk->approver->name ?? '-' }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-dark mb-0">
                        Struk belum bisa dilihat anggota sampai admin menyetujui pengiriman.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
