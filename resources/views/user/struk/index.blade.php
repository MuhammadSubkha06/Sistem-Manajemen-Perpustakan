@extends('layouts.user')
@section('title', 'Struk')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
    <div>
        <h1 class="page-title">Struk Saya</h1>
        <p class="page-subtitle">Bukti peminjaman, pengembalian, dan pembayaran denda dari admin.</p>
    </div>
</div>

<div class="simple-card bg-white">
    <div class="list-group list-group-flush">
        @forelse($struks as $struk)
            <div class="list-group-item p-3">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <div>
                        <div class="fw-bold">{{ $struk->judul }}</div>
                        <div class="text-muted small">
                            {{ $struk->kode }} · {{ optional($struk->issued_at)->format('d/m/Y H:i') }}
                        </div>
                        <div class="text-muted small mt-1">
                            {{ $struk->peminjaman->buku->judul ?? ($struk->payload['buku']['judul'] ?? '-') }}
                        </div>
                    </div>
                    <div class="text-md-end">
                        <span class="badge bg-light text-dark border mb-2">
                            {{ str_replace('_', ' ', ucfirst($struk->jenis)) }}
                        </span>
                        @if($struk->nominal > 0)
                            <div class="fw-semibold text-danger small mb-2">
                                Rp {{ number_format($struk->nominal, 0, ',', '.') }}
                            </div>
                        @endif
                        <a href="{{ route('user.struk.show', $struk) }}" class="btn btn-outline-primary btn-sm">
                            Lihat Struk
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-muted">Belum ada struk.</div>
        @endforelse
    </div>
</div>

@if($struks->hasPages())
    <div class="mt-4">{{ $struks->links() }}</div>
@endif

@endsection
