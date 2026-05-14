@extends('layouts.admin')
@section('title', 'Denda')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-triangle-exclamation me-2 text-warning"></i>Manajemen Denda</h4>
    <span class="badge bg-danger px-3 py-2">
        Total: Rp {{ number_format($totalDenda, 0, ',', '.') }}
    </span>
</div>

<div class="card border-0 rounded-3 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.83rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Kembali Rencana</th>
                        <th>Tgl Kembali Aktual</th>
                        <th class="text-end">Denda</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($denda as $d)
                    <tr>
                        <td class="ps-4 text-muted">{{ $denda->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $d->anggota->nama ?? '-' }}</td>
                        <td>{{ $d->buku->judul ?? '-' }}</td>
                        <td>{{ $d->tgl_kembali_rencana->format('d/m/Y') }}</td>
                        <td>{{ $d->tgl_kembali_aktual?->format('d/m/Y') ?? '-' }}</td>
                        <td class="text-end text-danger fw-semibold">
                            Rp {{ number_format($d->denda, 0, ',', '.') }}
                        </td>
                        <td class="text-center pe-4">
                            <form method="POST" action="{{ route('admin.denda.bayar', $d) }}"
                                  onsubmit="return confirm('Tandai denda ini sebagai lunas?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-check me-1"></i> Lunas
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">Tidak ada denda</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($denda->hasPages())
    <div class="card-footer bg-white py-3 px-4">{{ $denda->links() }}</div>
    @endif
</div>

@endsection