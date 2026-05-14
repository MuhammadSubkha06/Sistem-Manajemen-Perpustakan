@extends('layouts.admin')
@section('title', 'Detail Anggota')

@section('content')

<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.members.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Detail Anggota</h4>
            <small class="text-muted">Informasi profil dan aktivitas peminjaman anggota.</small>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.members.history', $member) }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-clock-rotate-left me-1"></i> Riwayat
        </a>
        <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning btn-sm fw-semibold">
            <i class="fas fa-pen me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 rounded-3 shadow-sm overflow-hidden">
            <div class="card-body p-4 text-center" style="background: linear-gradient(135deg, #1f2937, #334155);">
                <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center fw-bold text-dark mb-3" style="width: 76px; height: 76px; font-size: 1.8rem;">
                    {{ strtoupper(substr($member->nama, 0, 1)) }}
                </div>
                <h5 class="text-white fw-bold mb-1">{{ $member->nama }}</h5>
                <div class="text-white-50 small">NIS {{ $member->nis }}</div>
            </div>
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Kelas</span>
                        <span class="fw-semibold">{{ $member->kelas }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Nomor HP</span>
                        <span class="fw-semibold">{{ $member->no_hp ?: '-' }}</span>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="text-muted mb-1">Alamat</div>
                        <div class="fw-semibold">{{ $member->alamat ?: '-' }}</div>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span class="text-muted">Pinjaman Aktif</span>
                        <span class="badge bg-warning text-dark">{{ $member->peminjamanAktif->count() }} buku</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 rounded-3 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="fas fa-book-open me-2 text-warning"></i>Riwayat Peminjaman</h6>
                <span class="badge bg-light text-dark">{{ $member->peminjaman->count() }} transaksi</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: .88rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Rencana Kembali</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($member->peminjaman as $pinjam)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $pinjam->buku->judul ?? '-' }}</td>
                                    <td>{{ optional($pinjam->tgl_pinjam)->format('d/m/Y') ?: '-' }}</td>
                                    <td>{{ optional($pinjam->tgl_kembali_rencana)->format('d/m/Y') ?: '-' }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $pinjam->status === 'dikembalikan' ? 'badge-status-dikembalikan' : ($pinjam->status === 'terlambat' ? 'badge-status-terlambat' : 'badge-status-dipinjam') }}">
                                            {{ ucfirst($pinjam->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">Rp {{ number_format($pinjam->denda ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                        Belum ada riwayat peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
