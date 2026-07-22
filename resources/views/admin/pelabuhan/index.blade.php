@extends('layouts.admin')

@section('title', 'Kelola Pelabuhan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold">Kelola Pelabuhan</h3>
        <p class="text-muted mb-0">
            Daftar seluruh data pelabuhan yang terdaftar pada sistem.
        </p>
    </div>

    <a href="{{ route('admin.pelabuhan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Tambah Pelabuhan
    </a>

</div>

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    {{ session('success') }}

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

@endif

<div class="card shadow-sm border-0">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-primary text-center">

                    <tr>

                        <th width="60">No</th>

                        <th>Nama Pelabuhan</th>

                        <th>Negara</th>

                        <th>Lintang</th>

                        <th>Bujur</th>

                        <th>Ukuran</th>

                        <th>Tipe</th>

                        <th width="170">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($pelabuhan as $item)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration + ($pelabuhan->firstItem() - 1) }}
                        </td>

                        <td>{{ $item->nama_pelabuhan }}</td>

                        <td>
                            {{ $item->negara->nama ?? '-' }}
                        </td>

                        <td>{{ $item->lintang }}</td>

                        <td>{{ $item->bujur }}</td>

                        <td>{{ $item->ukuran_pelabuhan ?? '-' }}</td>

                        <td>{{ $item->tipe_pelabuhan ?? '-' }}</td>

                        <td class="text-center">

                            <a href="{{ route('admin.pelabuhan.edit', $item->id) }}"
                               class="btn btn-warning btn-sm">

                                <i class="bi bi-pencil-square"></i>

                            </a>

                            <form action="{{ route('admin.pelabuhan.destroy', $item->id) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data pelabuhan ini?')">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center">

                            Belum ada data pelabuhan.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $pelabuhan->links() }}

        </div>

    </div>

</div>

@endsection