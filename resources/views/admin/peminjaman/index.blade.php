@extends('layouts.admin')
@section('title', 'Peminjaman')

@section('content')

<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-arrow-right-arrow-left me-2 text-warning"></i>Manajemen Peminjaman</h4>
        <p class="text-muted mb-0">Tinjau permohonan pinjam, setujui transaksi, dan pantau status peminjaman anggota.</p>
    </div>
    <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-warning btn-sm fw-semibold">
        <i class="fas fa-plus me-1"></i> Tambah Manual
    </a>
</div>

<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Tanggal Dari</label>
                <input type="date" name="tgl_dari" class="form-control form-control-sm" value="{{ request('tgl_dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Tanggal Sampai</label>
                <input type="date" name="tgl_sampai" class="form-control form-control-sm" value="{{ request('tgl_sampai') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.84rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th>Status Approval</th>
                        <th>Status Buku</th>
                        <th class="text-end">Denda</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                        @php
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
                        <tr>
                            <td class="ps-4 text-muted">{{ $peminjaman->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $p->anggota->nama ?? '-' }}</div>
                                <div class="text-muted small">{{ $p->anggota->kelas ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $p->buku->judul ?? '-' }}</div>
                                <div class="text-muted small">{{ $p->buku->pengarang ?? '-' }}</div>
                            </td>
                            <td>{{ optional($p->tgl_pinjam)->format('d/m/Y') ?: '-' }}</td>
                            <td class="{{ $p->isTerlambat() ? 'text-danger fw-semibold' : '' }}">
                                {{ optional($p->tgl_kembali_rencana)->format('d/m/Y') ?: '-' }}
                            </td>
                            <td>
                                <span class="badge {{ $approvalBadge }} rounded-pill">
                                    {{ $p->approval_status === 'pending' ? 'Menunggu' : ($p->approval_status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                                </span>
                                @if($p->approval_note)
                                    <div class="text-muted small mt-1">{{ $p->approval_note }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $loanBadge }} rounded-pill">
                                    {{ ucfirst($p->status) }}
                                </span>
                                @if($p->return_status === 'pending')
                                    <div class="text-primary small mt-1">Pengembalian menunggu approval</div>
                                @elseif($p->return_status === 'rejected')
                                    <div class="text-danger small mt-1">{{ $p->return_note ?: 'Pengajuan return ditolak' }}</div>
                                @endif
                            </td>
                            <td class="text-end {{ $p->denda > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">
                                Rp {{ number_format($p->denda, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4">
                                @if($p->approval_status === 'pending')
                                    <div class="d-flex flex-column gap-2">
                                        <form method="POST" action="{{ route('admin.peminjaman.approve', $p) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success w-100">
                                                <i class="fas fa-check me-1"></i> Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.peminjaman.reject', $p) }}">
                                            @csrf
                                            <input type="hidden" name="approval_note" value="Permohonan ditolak oleh admin.">
                                            <button class="btn btn-sm btn-outline-danger w-100">
                                                <i class="fas fa-xmark me-1"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($peminjaman->hasPages())
        <div class="card-footer bg-white py-3 px-4">{{ $peminjaman->links() }}</div>
    @endif
</div>

@endsection
