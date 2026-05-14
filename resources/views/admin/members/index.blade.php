@extends('layouts.admin')
@section('title', 'Data Anggota')

@section('content')

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-users me-2 text-warning"></i>Manajemen Anggota</h4>
        <p class="text-muted mb-0">Kelola data anggota perpustakaan.</p>
    </div>
    <a href="{{ route('admin.members.create') }}" class="btn btn-warning btn-sm fw-semibold">
        <i class="fas fa-plus me-1"></i> Tambah User Anggota
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Total Anggota</div>
                <div class="fw-bold fs-5">{{ number_format($stats['total_anggota']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Anggota Aktif</div>
                <div class="fw-bold fs-5">{{ number_format($stats['aktif_anggota']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Belum Meminjam</div>
                <div class="fw-bold fs-5">{{ number_format($stats['nonaktif_anggota']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small">Pinjam Hari Ini</div>
                <div class="fw-bold fs-5">{{ number_format($stats['dipinjam_hari_ini']) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 rounded-3 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-9">
                <label for="search" class="form-label small text-muted mb-1">Cari Anggota</label>
                <input type="text" id="search" name="search" class="form-control form-control-sm" placeholder="Nama, NIS, atau kelas..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary btn-sm flex-grow-1">Cari</button>
                <a href="{{ route('admin.members.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 rounded-3 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.88rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>No. HP</th>
                        <th class="text-center">Aktif</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td class="ps-4 text-muted">{{ $members->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $member->nama }}</div>
                                <div class="text-muted small">{{ $member->alamat ?: '-' }}</div>
                            </td>
                            <td>{{ $member->nis }}</td>
                            <td>{{ $member->kelas }}</td>
                            <td>{{ $member->no_hp ?: '-' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $member->peminjaman_aktif_count > 0 ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ $member->peminjaman_aktif_count }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('admin.members.show', $member) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.members.destroy', $member) }}" class="d-inline" onsubmit="return confirm('Hapus data anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada data anggota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($members->hasPages())
        <div class="card-footer bg-white py-3 px-4">
            {{ $members->links() }}
        </div>
    @endif
</div>

@endsection
