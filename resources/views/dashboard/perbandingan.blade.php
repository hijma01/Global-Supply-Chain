@extends('layouts.app')

@section('content')

@section('style')

<style>

.btn-bandingkan{
    background:#F97316;
    color:white;
    border:none;
    transition:.3s;
}

.btn-bandingkan:hover{
    background:#EA580C;
    color:white;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(249,115,22,.35);
}

</style>

@endsection

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">

        <div class="col">

            <h2 class="fw-bold" style="color:#0F766E;">
                <i class="bi bi-bar-chart-line-fill"></i>
                Perbandingan Negara
            </h2>

            <p class="text-secondary">
                Bandingkan data ekonomi, cuaca, mata uang, dan skor risiko antar negara.
            </p>

        </div>

    </div>


    <!-- Form Perbandingan -->
    <div class="card border-0 shadow-lg rounded-4 mb-4">

        <div class="card-header text-white"
            style="background:linear-gradient(135deg,#0F766E,#14B8A6);">
            <h5 class="mb-0">
                <i class="bi bi-globe2"></i>
                Pilih Negara
            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('perbandingan.bandingkan') }}" method="POST">

                @csrf

                <div class="row">

                    <!-- Negara Pertama -->
                    <div class="col-md-5 mb-3">

                        <label class="form-label fw-bold">
                            Negara Pertama
                        </label>

                        <select
                            name="negara_a_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Negara --
                            </option>

                            @foreach($negara as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ isset($negaraA) && $negaraA->id == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- VS -->
                    <div class="col-md-2 d-flex align-items-end justify-content-center">

                        <h2
                            class="fw-bold"
                            style="color:#F97316;font-size:48px;">
                            VS
                        </h2>

                    </div>


                    <!-- Negara Kedua -->
                    <div class="col-md-5 mb-3">

                        <label class="form-label fw-bold">
                            Negara Kedua
                        </label>

                        <select
                            name="negara_b_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Negara --
                            </option>

                            @foreach($negara as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ isset($negaraB) && $negaraB->id == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="text-center mt-3">

                    <button
                        type="submit"
                        class="btn btn-lg px-5 btn-bandingkan">

                        <i class="bi bi-search"></i>
                        Bandingkan

                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- Card Hasil -->
    @if(isset($negaraA) && isset($negaraB))

    <div class="card border-0 shadow-lg rounded-4">

        <div
            class="card-header text-white"
            style="background:linear-gradient(135deg,#16A34A,#22C55E);">

            <h5 class="mb-0">

                <i class="bi bi-table"></i>

                Hasil Perbandingan

            </h5>

        </div>

        <div class="card-body">

        @php

            $ekonomiA = $negaraA->dataEkonomi->last();
            $ekonomiB = $negaraB->dataEkonomi->last();

            $cuacaA = $negaraA->cacheCuaca->last();
            $cuacaB = $negaraB->cacheCuaca->last();

            $risikoA = $negaraA->skorRisiko->last();
            $risikoB = $negaraB->skorRisiko->last();

        @endphp

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th width="25%">Parameter</th>

                        <th class="text-center">

                            {{ $negaraA->nama }}

                        </th>

                        <th class="text-center">

                            {{ $negaraB->nama }}

                        </th>

                    </tr>

                </thead>

                <tbody>

                    <!-- Bendera -->

                    <tr>

                        <th>Bendera</th>

                        <td class="text-center">

                            <img
                                src="{{ $negaraA->url_bendera }}"
                                width="80">

                        </td>

                        <td class="text-center">

                            <img
                                src="{{ $negaraB->url_bendera }}"
                                width="80">

                        </td>

                    </tr>

                    <!-- Nama Negara -->

                    <tr>

                        <th>Nama Negara</th>

                        <td>{{ $negaraA->nama }}</td>

                        <td>{{ $negaraB->nama }}</td>

                    </tr>

                    <!-- PDB -->

                    <tr>

                        <th>PDB (GDP)</th>

                        <td>

                            {{ number_format($ekonomiA->pdb ?? 0,2) }}

                        </td>

                        <td>

                            {{ number_format($ekonomiB->pdb ?? 0,2) }}

                        </td>

                    </tr>

                    <!-- Inflasi -->

                    <tr>

                        <th>Tingkat Inflasi</th>

                        <td>

                            {{ $ekonomiA->tingkat_inflasi ?? '-' }} %

                        </td>

                        <td>

                            {{ $ekonomiB->tingkat_inflasi ?? '-' }} %

                        </td>

                    </tr>

                    <!-- Mata Uang -->

                    <tr>

                        <th>Mata Uang</th>

                        <td>

                            {{ $negaraA->nama_mata_uang }}

                            ({{ $negaraA->kode_mata_uang }})

                        </td>

                        <td>

                            {{ $negaraB->nama_mata_uang }}

                            ({{ $negaraB->kode_mata_uang }})

                        </td>

                    </tr>

                    <!-- Suhu -->

                    <tr>

                        <th>Suhu</th>

                        <td>

                            {{ $cuacaA->suhu ?? '-' }} °C

                        </td>

                        <td>

                            {{ $cuacaB->suhu ?? '-' }} °C

                        </td>

                    </tr>

                    <!-- Curah Hujan -->

                    <tr>

                        <th>Curah Hujan</th>

                        <td>

                            {{ $cuacaA->curah_hujan ?? '-' }} mm

                        </td>

                        <td>

                            {{ $cuacaB->curah_hujan ?? '-' }} mm

                        </td>

                    </tr>

                    <!-- Kecepatan Angin -->

                    <tr>

                        <th>Kecepatan Angin</th>

                        <td>

                            {{ $cuacaA->kecepatan_angin ?? '-' }} km/jam

                        </td>

                        <td>

                            {{ $cuacaB->kecepatan_angin ?? '-' }} km/jam

                        </td>

                    </tr>

                    <!-- Skor Risiko -->

                    <tr>

                        <th>Skor Risiko</th>

                        <td>

                            {{ $risikoA->skor_total ?? '-' }}

                        </td>

                        <td>

                            {{ $risikoB->skor_total ?? '-' }}

                        </td>

                    </tr>

                    <!-- Tingkat Risiko -->

                    <tr>

                        <th>Tingkat Risiko</th>

                        <td>

                            <span class="badge bg-warning text-dark">

                                {{ $risikoA->tingkat_risiko ?? '-' }}

                            </span>

                        </td>

                        <td>

                            <span class="badge bg-warning text-dark">

                                {{ $risikoB->tingkat_risiko ?? '-' }}

                            </span>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    @endif

</div>

@endsection