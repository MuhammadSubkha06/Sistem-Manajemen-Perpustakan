@extends('layouts.admin')
@section('title', 'Laporan Bulanan')

@section('content')

    {{-- ── Header ────────────────────────────────────────────────── --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Peminjaman &amp; Pengembalian</h4>
            <p class="text-muted mb-0">Rekap data peminjaman, pengembalian, dan denda</p>
        </div>
    </div>

    {{-- ── Filter ────────────────────────────────────────────────── --}}
    <div class="card border-0 rounded-3 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.report.index') }}" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tahun</label>
                    <select name="tahun" class="form-select rounded-2" onchange="this.form.submit()">
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" @selected($tahun == $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select rounded-2" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @selected($bulan == $i)>
                                {{ \Carbon\Carbon::createFromDate(2000, $i, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Export</label>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.report.export-pdf', ['tahun' => $tahun, 'bulan' => $bulan]) }}"
                            class="btn btn-danger btn-sm rounded-2 w-100">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </a>
                        <a href="{{ route('admin.report.export-excel', ['tahun' => $tahun, 'bulan' => $bulan]) }}"
                            class="btn btn-success btn-sm rounded-2 w-100">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- ── Ringkasan Bulanan ─────────────────────────────────────── --}}
    <div class="card border-0 rounded-3 shadow-sm mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <h6 class="fw-bold mb-0">
                Ringkasan
                @if($bulan)
                    {{ \Carbon\Carbon::createFromDate(2000, $bulan, 1)->translatedFormat('F') }}
                @endif
                {{ $tahun }}
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Bulan</th>
                            <th class="px-4 py-3 text-center">Total Peminjaman</th>
                            <th class="px-4 py-3 text-center">Total Pengembalian</th>
                            <th class="px-4 py-3 text-end">Total Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ringkasanBulanan as $item)
                            <tr>
                                <td class="px-4 py-3 fw-semibold">
                                    {{ \Carbon\Carbon::createFromDate($item->tahun, $item->bulan, 1)->translatedFormat('F Y') }}
                                </td>
                                <td class="px-4 py-3 text-center">{{ number_format($item->total_pinjam) }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($item->total_kembali) }}</td>
                                <td class="px-4 py-3 text-end">
                                    @if($item->total_denda > 0)
                                        <span class="badge bg-warning text-dark">
                                            Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-5 text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($ringkasanBulanan->count() > 1)
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td class="px-4 py-3">Total</td>
                                <td class="px-4 py-3 text-center">{{ number_format($ringkasanBulanan->sum('total_pinjam')) }}
                                </td>
                                <td class="px-4 py-3 text-center">{{ number_format($ringkasanBulanan->sum('total_kembali')) }}
                                </td>
                                <td class="px-4 py-3 text-end">
                                    Rp {{ number_format($ringkasanBulanan->sum('total_denda'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- ── Detail Peminjaman ─────────────────────────────────────── --}}
    <div class="card border-0 rounded-3 shadow-sm mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Detail Peminjaman</h6>
            <small class="text-muted">{{ $laporanPeminjaman->total() }} data</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3" style="width:50px">No</th>
                            <th class="px-4 py-3">Anggota</th>
                            <th class="px-4 py-3">Buku</th>
                            <th class="px-4 py-3">Tgl Pinjam</th>
                            <th class="px-4 py-3">Tgl Kembali<br><small class="fw-normal text-muted">Rencana</small></th>
                            <th class="px-4 py-3">Tgl Kembali<br><small class="fw-normal text-muted">Aktual</small></th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-end">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanPeminjaman as $item)
                            @php
                                $no = $loop->iteration + ($laporanPeminjaman->currentPage() - 1) * $laporanPeminjaman->perPage();
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-muted">{{ $no }}</td>
                                <td class="px-4 py-3">
                                    <span class="fw-semibold d-block">{{ $item->anggota->nama ?? '-' }}</span>
                                    <small class="text-muted">{{ $item->anggota->nis ?? '-' }}</small>
                                </td>
                                <td class="px-4 py-3">{{ $item->buku->judul ?? '-' }}</td>
                                <td class="px-4 py-3 text-nowrap">{{ $item->tgl_pinjam->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-3 text-nowrap">{{ $item->tgl_kembali_rencana->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-nowrap">
                                    {{ $item->tgl_kembali_aktual?->translatedFormat('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $badgeMap = [
                                            'dipinjam' => 'bg-primary',
                                            'terlambat' => 'bg-danger',
                                            'dikembalikan' => 'bg-success',
                                        ];
                                    @endphp
                                    <span class="badge {{ $badgeMap[$item->status] ?? 'bg-secondary' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    @if($item->denda > 0)
                                        <span class="badge bg-warning text-dark">
                                            Rp {{ number_format($item->denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-5 text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    Tidak ada data peminjaman
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-top">
                {{ $laporanPeminjaman->links() }}
            </div>
        </div>
    </div>

    {{-- ── Statistik Pelanggaran ─────────────────────────────────── --}}
    @if($statistikPelanggaran->isNotEmpty())
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0">Statistik Pelanggaran Anggota</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Nama Anggota</th>
                                <th class="px-4 py-3 text-center">Jml Denda</th>
                                <th class="px-4 py-3 text-end">Total Denda</th>
                                <th class="px-4 py-3 text-center">Terlambat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistikPelanggaran as $item)
                                <tr>
                                    <td class="px-4 py-3 fw-semibold">{{ $item->nama }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-danger">{{ $item->total_denda_count }}×</span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        Rp {{ number_format($item->total_denda_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-warning text-dark">{{ $item->late_return_count }}×</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

@endsection