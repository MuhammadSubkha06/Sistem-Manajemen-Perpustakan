@extends('layouts.admin')
@section('title', 'Riwayat Anggota')

@section('content')

<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.members.show', $member) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Riwayat Peminjaman Anggota</h4>
            <small class="text-muted">{{ $member->nama }} • NIS {{ $member->nis }}</small>
        </div>
    </div>
    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning btn-sm fw-semibold">
        <i class="fas fa-pen me-1"></i> Edit Anggota
    </a>
</div>

<div class="card border-0 rounded-3 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="text-muted small mb-1">Nama</div>
                <div class="fw-semibold">{{ $member->nama }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Kelas</div>
                <div class="fw-semibold">{{ $member->kelas }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">No. HP</div>
                <div class="fw-semibold">{{ $member->no_hp ?: '-' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Total Transaksi</div>
                <div class="fw-semibold">{{ $member->peminjaman->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 rounded-3 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: .88rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Target Kembali</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($member->peminjaman as $pinjam)
                        <tr>
                            <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $pinjam->buku->judul ?? '-' }}</td>
                            <td>{{ optional($pinjam->tgl_pinjam)->format('d/m/Y') ?: '-' }}</td>
                            <td>{{ optional($pinjam->tgl_kembali_rencana)->format('d/m/Y') ?: '-' }}</td>
                            <td>{{ optional($pinjam->tgl_kembali_aktual)->format('d/m/Y') ?: '-' }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $pinjam->status === 'dikembalikan' ? 'badge-status-dikembalikan' : ($pinjam->status === 'terlambat' ? 'badge-status-terlambat' : 'badge-status-dipinjam') }}">
                                    {{ ucfirst($pinjam->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">Rp {{ number_format($pinjam->denda ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                Belum ada riwayat peminjaman untuk anggota ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
