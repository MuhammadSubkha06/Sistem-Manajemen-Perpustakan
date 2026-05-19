@extends('layouts.admin')
@section('title', 'Edit Peminjaman')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Edit Peminjaman</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Anggota</div>
                            <div class="fw-semibold">{{ $peminjaman->anggota->nama ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">  
                            <div class="text-muted small mb-1">Buku</div>
                            <div class="fw-semibold">{{ $peminjaman->buku->judul ?? '-' }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.peminjaman.update', $peminjaman) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Pinjam</label>
                                <input type="date" name="tgl_pinjam"
                                    class="form-control @error('tgl_pinjam') is-invalid @enderror"
                                    value="{{ old('tgl_pinjam', optional($peminjaman->tgl_pinjam)->toDateString()) }}">
                                @error('tgl_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rencana Kembali</label>
                                <input type="date" name="tgl_kembali_rencana"
                                    class="form-control @error('tgl_kembali_rencana') is-invalid @enderror"
                                    value="{{ old('tgl_kembali_rencana', optional($peminjaman->tgl_kembali_rencana)->toDateString()) }}">
                                @error('tgl_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-warning fw-semibold px-4">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection