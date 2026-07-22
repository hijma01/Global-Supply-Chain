@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold">Tambah Pengguna</h3>
        <p class="text-muted mb-0">
            Tambahkan data pengguna baru ke dalam sistem.
        </p>
    </div>

    <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.pengguna.store') }}" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">Nama</label>

                <input
                    type="text"
                    name="nama"
                    class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama">

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
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email">

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan password">

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

                    <option value="">-- Pilih Peran --</option>

                    <option value="user"
                        {{ old('peran') == 'user' ? 'selected' : '' }}>
                        User
                    </option>

                    <option value="admin"
                        {{ old('peran') == 'admin' ? 'selected' : '' }}>
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

                <button type="submit" class="btn btn-primary">

                    <i class="bi bi-save"></i>

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection