@extends('layouts.admin')

@section('content')
<div class="container py-4 d-flex justify-content-center">

    <div class="card shadow-sm border-0 rounded-4" style="width: 500px;">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">Tambah Kategori</h5>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori"
                        class="form-control @error('nama_kategori') is-invalid @enderror">

                    @error('nama_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection