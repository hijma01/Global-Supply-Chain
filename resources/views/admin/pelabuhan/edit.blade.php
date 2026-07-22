@extends('layouts.admin')

@section('title', 'Edit Pelabuhan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold">Edit Pelabuhan</h3>
        <p class="text-muted mb-0">
            Perbarui data pelabuhan.
        </p>
    </div>

    <a href="{{ route('admin.pelabuhan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Kembali
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.pelabuhan.update', $pelabuhan->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Pelabuhan</label>

                <input type="text"
                       name="nama_pelabuhan"
                       class="form-control @error('nama_pelabuhan') is-invalid @enderror"
                       value="{{ old('nama_pelabuhan', $pelabuhan->nama_pelabuhan) }}">

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
                            {{ old('negara_id', $pelabuhan->negara_id) == $item->id ? 'selected' : '' }}>

                            {{ $item->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="mb-3">

                        <label class="form-label">Lintang</label>

                        <input type="number"
                               step="0.000001"
                               name="lintang"
                               value="{{ old('lintang', $pelabuhan->lintang) }}"
                               class="form-control">

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="mb-3">

                        <label class="form-label">Bujur</label>

                        <input type="number"
                               step="0.000001"
                               name="bujur"
                               value="{{ old('bujur', $pelabuhan->bujur) }}"
                               class="form-control">

                    </div>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Ukuran Pelabuhan</label>

                <input type="text"
                       name="ukuran_pelabuhan"
                       value="{{ old('ukuran_pelabuhan', $pelabuhan->ukuran_pelabuhan) }}"
                       class="form-control">

            </div>

            <div class="mb-4">

                <label class="form-label">Tipe Pelabuhan</label>

                <input type="text"
                       name="tipe_pelabuhan"
                       value="{{ old('tipe_pelabuhan', $pelabuhan->tipe_pelabuhan) }}"
                       class="form-control">

            </div>

            <div class="text-end">

                <button type="submit" class="btn btn-warning text-white">
                    <i class="bi bi-pencil-square"></i>
                    Perbarui
                </button>

            </div>

        </form>

    </div>

</div>

@endsection