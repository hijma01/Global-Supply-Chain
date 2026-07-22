@extends('layouts.admin')

@section('title', 'Tambah Pelabuhan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold">Tambah Pelabuhan</h3>
        <p class="text-muted mb-0">
            Tambahkan data pelabuhan baru ke dalam sistem.
        </p>
    </div>

    <a href="{{ route('admin.pelabuhan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.pelabuhan.store') }}" method="POST">

            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Pelabuhan</label>

                <input type="text"
                    name="nama_pelabuhan"
                    class="form-control @error('nama_pelabuhan') is-invalid @enderror"
                    value="{{ old('nama_pelabuhan') }}">

                @error('nama_pelabuhan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Negara</label>

                <select name="negara_id"
                        class="form-select @error('negara_id') is-invalid @enderror">

                    <option value="">-- Pilih Negara --</option>

                    @foreach($negara as $item)

                        <option value="{{ $item->id }}"
                            {{ old('negara_id') == $item->id ? 'selected' : '' }}>

                            {{ $item->nama }}

                        </option>

                    @endforeach

                </select>

                @error('negara_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="mb-3">

                        <label class="form-label">Lintang</label>

                        <input type="number"
                               step="0.000001"
                               name="lintang"
                               value="{{ old('lintang') }}"
                               class="form-control @error('lintang') is-invalid @enderror">

                        @error('lintang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="mb-3">

                        <label class="form-label">Bujur</label>

                        <input type="number"
                               step="0.000001"
                               name="bujur"
                               value="{{ old('bujur') }}"
                               class="form-control @error('bujur') is-invalid @enderror">

                        @error('bujur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Ukuran Pelabuhan</label>

                <input type="text"
                       name="ukuran_pelabuhan"
                       value="{{ old('ukuran_pelabuhan') }}"
                       class="form-control">

            </div>

            <div class="mb-4">

                <label class="form-label">Tipe Pelabuhan</label>

                <input type="text"
                       name="tipe_pelabuhan"
                       value="{{ old('tipe_pelabuhan') }}"
                       class="form-control">

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