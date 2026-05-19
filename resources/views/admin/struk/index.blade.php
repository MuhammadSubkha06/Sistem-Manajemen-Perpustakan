@extends('layouts.admin')
@section('title', 'Approval Struk')

@section('content')

<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-receipt me-2 text-warning"></i>Approval Struk</h4>
        <p class="text-muted mb-0">Setujui pengiriman struk agar bisa dilihat oleh anggota.</p>
    </div>
    <span class="badge bg-warning text-dark px-3 py-2">
        {{ number_format($pendingCount) }} menunggu approval
    </span>
</div>

<div class="card border-0 rounded-3 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Jenis</label>
                <select name="jenis" class="form-select form-select-sm">
                    <option value="">Semua jenis</option>
                    <option value="peminjaman" {{ request('jenis') === 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                    <option value="pengembalian" {{ request('jenis') === 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                    <option value="pembayaran_denda" {{ request('jenis') === 'pembayaran_denda' ? 'selected' : '' }}>Pembayaran Denda</option>
                </select>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.struk.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 rounded-3 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.86rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Anggota</th>
                        <th>Transaksi</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($struks as $struk)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $struk->kode }}</div>
                                @if($struk->nominal > 0)
                                    <div class="text-danger small">Rp {{ number_format($struk->nominal, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $struk->anggota->nama ?? '-' }}</div>
                                <div class="text-muted small">{{ $struk->anggota->kelas ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $struk->judul }}</div>
                                <div class="text-muted small">{{ $struk->peminjaman->buku->judul ?? ($struk->payload['buku']['judul'] ?? '-') }}</div>
                            </td>
                            <td>{{ str_replace('_', ' ', ucfirst($struk->jenis)) }}</td>
                            <td>{{ optional($struk->issued_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($struk->is_approved)
                                    <span class="badge bg-success">Disetujui</span>
                                    <div class="text-muted small mt-1">{{ optional($struk->approved_at)->format('d/m/Y H:i') }}</div>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.struk.show', $struk) }}" class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                    @unless($struk->is_approved)
                                        <form method="POST" action="{{ route('admin.struk.approve', $struk) }}" onsubmit="return confirm('Setujui pengiriman struk ini ke anggota?')">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Setujui</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada struk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($struks->hasPages())
        <div class="card-footer bg-white py-3 px-4">{{ $struks->links() }}</div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.querySelector('form[method="GET"]');
        if (filterForm) {
            const inputs = filterForm.querySelectorAll('select, input');
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Delay submission slightly to ensure value is set
                    setTimeout(() => {
                        filterForm.submit();
                    }, 100);
                });
            });
        }
    });
</script>
@endpush

@endsection
