@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold">Edit Pengguna</h3>
        <p class="text-muted mb-0">
            Perbarui data pengguna yang dipilih.
        </p>
    </div>

    <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.pengguna.update', $pengguna->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">Nama</label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $pengguna->nama) }}"
                    class="form-control @error('nama') is-invalid @enderror">

                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $pengguna->email) }}"
                    class="form-control @error('email') is-invalid @enderror">

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Kosongkan jika tidak ingin mengubah password">

                <small class="text-muted">
                    Biarkan kosong jika password tidak ingin diubah.
                </small>

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Peran</label>

                <select
                    name="peran"
                    class="form-select @error('peran') is-invalid @enderror">

                    <option value="user"
                        {{ old('peran', $pengguna->peran) == 'user' ? 'selected' : '' }}>
                        User
                    </option>

                    <option value="admin"
                        {{ old('peran', $pengguna->peran) == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                </select>

                @error('peran')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="text-end">

                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </button>

                <button type="submit" class="btn btn-warning text-white">
                    <i class="bi bi-pencil-square"></i>
                    Perbarui
                </button>

            </div>

        </form>

    </div>

</div>

@endsection