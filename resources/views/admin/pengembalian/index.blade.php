@extends('layouts.admin')
@section('title', 'Pengembalian')

@section('content')

<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-rotate-left me-2 text-warning"></i>Approval Pengembalian</h4>
        <p class="text-muted mb-0">Semua pengembalian buku dari anggota harus ditinjau admin sebelum transaksi ditutup.</p>
    </div>
    <span class="badge bg-warning text-dark px-3 py-2">
        {{ $peminjaman->total() }} permintaan
    </span>
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
                        <th>Target Kembali</th>
                        <th>Permintaan Return</th>
                        <th class="text-end">Estimasi Denda</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                        @php $terlambat = $p->isTerlambat(); @endphp
                        <tr class="{{ $terlambat ? 'table-danger' : '' }}">
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
                            <td class="{{ $terlambat ? 'text-danger fw-semibold' : '' }}">
                                {{ optional($p->tgl_kembali_rencana)->format('d/m/Y') ?: '-' }}
                                @if($terlambat)
                                    <div class="small text-danger">Terlambat {{ now()->diffInDays($p->tgl_kembali_rencana) }} hari</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill">Menunggu Approval</span>
                                <div class="text-muted small mt-1">
                                    {{ optional($p->return_requested_at)->format('d/m/Y H:i') ?: '-' }}
                                </div>
                            </td>
                            <td class="text-end {{ $terlambat ? 'text-danger fw-semibold' : 'text-muted' }}">
                                Rp {{ number_format($p->hitungDenda(), 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex flex-column gap-2">
                                    <form method="POST" action="{{ route('admin.pengembalian.proses', $p) }}" onsubmit="return confirm('Setujui pengembalian buku ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-success w-100">
                                            <i class="fas fa-check me-1"></i> Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.pengembalian.reject', $p) }}" onsubmit="return confirm('Tolak permintaan pengembalian ini?')">
                                        @csrf
                                        <input type="hidden" name="return_note" value="Permintaan pengembalian ditolak oleh admin.">
                                        <button class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-xmark me-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle text-success fa-2x mb-2"></i><br>
                                Belum ada permintaan pengembalian yang menunggu approval.
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
