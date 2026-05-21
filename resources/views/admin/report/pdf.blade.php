<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $filename }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #222;
            padding: 24px 28px;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        /* ── Section title ── */
        h2 {
            font-size: 12px;
            margin: 20px 0 8px;
        }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10px;
        }

        thead th {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        tbody td {
            padding: 5px 8px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }

        tbody tr:nth-child(even) td {
            background-color: #fafafa;
        }

        /* ── Badge-like cells ── */
        .badge-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .badge-warning {
            color: #856404;
            font-weight: bold;
        }

        .badge-success {
            color: #198754;
            font-weight: bold;
        }

        .badge-primary {
            color: #0d6efd;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ── Summary box ── */
        .summary-box {
            background: #fffbea;
            border-left: 4px solid #ffc107;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 10px;
        }

        .summary-box strong {
            margin-right: 20px;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 28px;
            text-align: right;
            font-size: 9px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        /* ── Page break ── */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    {{-- ── Header ───────────────────────────────────────────── --}}
    <div class="header">
        <h1>Laporan Peminjaman &amp; Pengembalian Buku</h1>
        <p>Sistem Manajemen Perpustakaan</p>
        <p>
            Periode:
            @if($bulan)
                {{ \Carbon\Carbon::createFromDate(2000, $bulan, 1)->translatedFormat('F Y') }}
            @else
                Tahun {{ $tahun }}
            @endif
        </p>
        <p>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>

    {{-- ── Summary Numbers ──────────────────────────────────── --}}
    <div class="summary-box">
        <strong>Total Peminjaman: {{ $laporanPeminjaman->count() }}</strong>
        <strong>Total Dikembalikan: {{ $laporanPeminjaman->whereNotNull('tgl_kembali_aktual')->count() }}</strong>
        <strong>Total Denda: Rp {{ number_format($laporanPeminjaman->sum('denda'), 0, ',', '.') }}</strong>
    </div>

    {{-- ── Ringkasan Bulanan ────────────────────────────────── --}}
    @if($ringkasanBulanan->isNotEmpty())
        <h2>Ringkasan Data</h2>
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-center">Total Peminjaman</th>
                    <th class="text-center">Total Pengembalian</th>
                    <th class="text-right">Total Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ringkasanBulanan as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromDate($item->tahun, $item->bulan, 1)->translatedFormat('F Y') }}</td>
                        <td class="text-center">{{ number_format($item->total_pinjam) }}</td>
                        <td class="text-center">{{ number_format($item->total_kembali) }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_denda, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ── Detail Peminjaman ───────────────────────────────── --}}
    <div class="{{ $ringkasanBulanan->isNotEmpty() ? 'page-break' : '' }}">
        <h2>Detail Peminjaman</h2>
        <table>
            <thead>
                <tr>
                    <th style="width:30px">No</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th class="text-right">Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanPeminjaman as $item)
                    @php
                        $statusClass = match ($item->status) {
                            'dipinjam' => 'badge-primary',
                            'terlambat' => 'badge-danger',
                            'dikembalikan' => 'badge-success',
                            default => '',
                        };
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            {{ $item->anggota->nama ?? '-' }}<br>
                            <span style="color:#888">{{ $item->anggota->nis ?? '' }}</span>
                        </td>
                        <td>{{ $item->buku->judul ?? '-' }}</td>
                        <td class="text-center">{{ $item->tgl_pinjam->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $item->tgl_kembali_aktual?->format('d-m-Y') ?? '-' }}</td>
                        <td class="{{ $statusClass }}">{{ ucfirst($item->status) }}</td>
                        <td class="text-right">
                            {{ $item->denda > 0 ? 'Rp ' . number_format($item->denda, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="color:#888">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Statistik Pelanggaran ───────────────────────────── --}}
    @if(isset($statistikPelanggaran) && $statistikPelanggaran->isNotEmpty())
        <div class="page-break">
            <h2>Statistik Pelanggaran Anggota</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Anggota</th>
                        <th class="text-center">Jml Denda</th>
                        <th class="text-right">Total Denda</th>
                        <th class="text-center">Terlambat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statistikPelanggaran as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td class="text-center badge-danger">{{ $item->total_denda_count }}×</td>
                            <td class="text-right">Rp {{ number_format($item->total_denda_amount, 0, ',', '.') }}</td>
                            <td class="text-center badge-warning">{{ $item->late_return_count }}×</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>Laporan dibuat otomatis oleh Sistem Manajemen Perpustakaan &mdash;
            {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>

</body>

</html>