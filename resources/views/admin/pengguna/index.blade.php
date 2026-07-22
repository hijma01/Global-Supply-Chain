@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold">Kelola Pengguna</h3>
        <p class="text-muted mb-0">
            Daftar seluruh pengguna yang terdaftar pada sistem.
        </p>
    </div>

    <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Tambah Pengguna
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

                        <th>Nama</th>

                        <th>Email</th>

                        <th>Peran</th>

                        <th width="170">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($pengguna as $item)

                    <tr>

                        <td class="text-center">
                            {{ $loop->iteration + ($pengguna->firstItem() - 1) }}
                        </td>

                        <td>{{ $item->nama }}</td>

                        <td>{{ $item->email }}</td>

                        <td class="text-center">

                            @if($item->peran == 'admin')

                                <span class="badge bg-danger">
                                    Admin
                                </span>

                            @else

                                <span class="badge bg-success">
                                    User
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            <a href="{{ route('admin.pengguna.edit',$item->id) }}"
                               class="btn btn-warning btn-sm">

                                <i class="bi bi-pencil-square"></i>

                            </a>

                            <form action="{{ route('admin.pengguna.destroy',$item->id) }}"
                                  method="POST"
                                  class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus pengguna ini?')">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center">

                            Belum ada data pengguna.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $pengguna->links() }}

        </div>

    </div>

</div>

@endsection