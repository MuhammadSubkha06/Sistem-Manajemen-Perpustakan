@extends('layouts.user')
@section('title', 'Peminjaman Saya')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
    <div>
        <h1 class="page-title">Peminjaman Saya</h1>
        <p class="page-subtitle">Status pengajuan, pinjaman aktif, dan pengembalian.</p>
    </div>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary btn-sm align-self-md-start">
        <i class="fas fa-plus me-1"></i> Ajukan Pinjam
    </a>
</div>

<div class="simple-card bg-white">
    <div class="list-group list-group-flush">
        @forelse($peminjaman as $p)
            @php
                $strukPeminjaman = $p->struks->firstWhere('jenis', 'peminjaman');
                $strukPengembalian = $p->struks->firstWhere('jenis', 'pengembalian');
                $approvalBadge = match($p->approval_status) {
                    'pending' => 'bg-warning text-dark',
                    'approved' => 'bg-success',
                    default => 'bg-danger',
                };
                $loanBadge = match($p->status) {
                    'dikembalikan' => 'badge-status-dikembalikan',
                    'terlambat' => 'badge-status-terlambat',
                    default => 'badge-status-dipinjam',
                };
            @endphp

            <div class="list-group-item p-3">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                    <div>
                        <h2 class="h6 fw-bold mb-1">{{ $p->buku->judul ?? '-' }}</h2>
                        <div class="text-muted small">{{ $p->buku->pengarang ?? '-' }}</div>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge {{ $approvalBadge }}">
                                {{ $p->approval_status === 'pending' ? 'Menunggu Approval' : ($p->approval_status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                            </span>
                            <span class="badge {{ $loanBadge }}">{{ ucfirst($p->status) }}</span>
                            @if($p->return_status === 'pending')
                                <span class="badge bg-primary">Pengembalian menunggu admin</span>
                            @elseif($p->return_status === 'approved')
                                <span class="badge bg-success">Pengembalian disetujui</span>
                            @elseif($p->return_status === 'rejected')
                                <span class="badge bg-danger">Pengembalian ditolak</span>
                            @endif
                        </div>
                        @if($p->approval_note || $p->return_note)
                            <div class="text-muted small mt-2">{{ $p->approval_note ?: $p->return_note }}</div>
                        @endif
                    </div>

                    <div class="text-lg-end small">
                        <div>Pengajuan: {{ optional($p->tgl_pinjam)->format('d/m/Y') ?: '-' }}</div>
                        <div class="{{ $p->isTerlambat() ? 'text-danger fw-semibold' : 'text-muted' }}">
                            Kembali: {{ optional($p->tgl_kembali_rencana)->format('d/m/Y') ?: '-' }}
                        </div>
                        <div class="{{ $p->denda > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                            Denda: Rp {{ number_format($p->denda, 0, ',', '.') }}
                        </div>

                        <div class="mt-3">
                            <div class="d-flex flex-wrap justify-content-lg-end gap-2 mb-2">
                                @if($strukPeminjaman)
                                    <a href="{{ route('user.struk.show', $strukPeminjaman) }}" class="btn btn-outline-secondary btn-sm">
                                        Struk Pinjam
                                    </a>
                                @endif
                                @if($strukPengembalian)
                                    <a href="{{ route('user.struk.show', $strukPengembalian) }}" class="btn btn-outline-secondary btn-sm">
                                        Struk Kembali
                                    </a>
                                @endif
                            </div>

                            @if($p->approval_status === 'approved' && $p->status === 'dipinjam' && $p->return_status !== 'pending')
                                <form method="POST" action="{{ route('user.peminjaman.request-return', $p) }}" onsubmit="return confirm('Ajukan pengembalian buku ini?')">
                                    @csrf
                                    <button class="btn btn-outline-primary btn-sm">Ajukan Return</button>
                                </form>
                            @elseif($p->return_status === 'pending')
                                <span class="text-muted">Menunggu persetujuan admin</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-muted">Belum ada riwayat peminjaman.</div>
        @endforelse
    </div>
</div>

@if($peminjaman->hasPages())
    <div class="mt-4">{{ $peminjaman->links() }}</div>
@endif

@endsection
