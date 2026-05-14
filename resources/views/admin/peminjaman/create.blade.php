@extends('layouts.admin')
@section('title', 'Tambah Peminjaman')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Tambah Peminjaman</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
        <div class="card border-0 rounded-3 shadow-sm">
            <div class="card-body p-4">

                @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-3">
                    <ul class="mb-0 ps-3" style="font-size:.85rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.peminjaman.store') }}">
                    @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Anggota <span class="text-danger">*</span></label>
                <select name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror">
                    <option value="">— Pilih Anggota —</option>
                    @foreach($anggota as $a)
                    <option value="{{ $a->id }}" {{ old('anggota_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->nama }} ({{ $a->nis }} — {{ $a->kelas }})
                    </option>
                    @endforeach
                </select>
                @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Buku <span class="text-danger">*</span></label>
                <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror">
                    <option value="">— Pilih Buku —</option>
                    @foreach($buku as $b)
                    <option value="{{ $b->id }}" {{ old('buku_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->judul }} (Stok: {{ $b->stok }})
                    </option>
                    @endforeach
                </select>
                @error('buku_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Tgl Pinjam <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_pinjam" class="form-control"
                        value="{{ old('tgl_pinjam', today()->toDateString()) }}">
                </div>
                <div class="col-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Rencana Kembali <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_kembali_rencana" class="form-control @error('tgl_kembali_rencana') is-invalid @enderror"
                        value="{{ old('tgl_kembali_rencana') }}">
                    @error('tgl_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-warning fw-semibold px-4">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
