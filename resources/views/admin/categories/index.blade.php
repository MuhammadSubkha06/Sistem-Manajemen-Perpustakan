@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📚 Manajemen Kategori</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary shadow-sm">
            + Tambah Kategori
        </a>
    </div>

    <!-- CARD -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kategori</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $i => $k)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $k->nama_kategori }}</td>
                            <td class="text-center">

                                <a href="{{ route('admin.categories.edit', $k->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('admin.categories.destroy', $k->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada data kategori
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection