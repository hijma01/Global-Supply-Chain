@extends('layouts.app')

@section('title','Skor Risiko')

@section('content')
<div class="container-fluid">
    <h4>Jumlah Data Risiko : {{ $risiko->count() }}</h4>

    <div class="mb-4">
        <h3 class="fw-bold">
            Analisis Risiko Supply Chain
        </h3>

        <p class="text-muted">
            Perhitungan risiko berdasarkan cuaca, inflasi,
            kurs mata uang dan sentimen berita.
        </p>
    </div>

    @php

        $totalData = $risiko->count();

        $rendah = $risiko->where('tingkat_risiko','rendah')->count();

        $sedang = $risiko->where('tingkat_risiko','sedang')->count();

        $tinggi = $risiko->where('tingkat_risiko','tinggi')->count();

    @endphp

    <div class="row g-4 mb-4">

        <div class="col-lg-3">
            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Perhitungan
                    </small>

                    <h2 class="fw-bold mt-2">
                        {{ $totalData }}
                    </h2>

                </div>

            </div>
        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-success">
                        Risiko Rendah
                    </small>

                    <h2 class="fw-bold text-success">

                        {{ $rendah }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-warning">
                        Risiko Sedang
                    </small>

                    <h2 class="fw-bold text-warning">

                        {{ $sedang }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-danger">
                        Risiko Tinggi
                    </small>

                    <h2 class="fw-bold text-danger">

                        {{ $tinggi }}

                    </h2>

                </div>

            </div>

        </div>

    </div>
        <div class="row mb-4">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-0">

                    <h5 class="fw-bold mb-0">
                        Distribusi Risiko
                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="grafikRisiko" height="260"></canvas>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-0">

                    <h5 class="fw-bold mb-0">
                        Skor Total Setiap Negara
                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="grafikSkor" height="260"></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    Detail Perhitungan Risiko
                </h5>

                <span class="badge bg-primary">
                    {{ $totalData }} Data
                </span>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th>No</th>

                        <th>Negara</th>

                        <th>Cuaca</th>

                        <th>Inflasi</th>

                        <th>Kurs</th>

                        <th>Berita</th>

                        <th width="220">Skor Total</th>

                        <th>Risiko</th>

                        <th>Dihitung</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($risiko as $item)

                        @php

                            if($item->tingkat_risiko == 'rendah'){
                                $badge='success';
                            }elseif($item->tingkat_risiko == 'sedang'){
                                $badge='warning';
                            }else{
                                $badge='danger';
                            }

                        @endphp

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>

                                <strong>

                                    {{ $item->negara->nama }}

                                </strong>

                            </td>

                            <td>{{ number_format($item->skor_cuaca,2) }}</td>

                            <td>{{ number_format($item->skor_inflasi,2) }}</td>

                            <td>{{ number_format($item->skor_kurs,2) }}</td>

                            <td>{{ number_format($item->skor_sentimen_berita,2) }}</td>

                            <td>

                                <div class="d-flex justify-content-between">

                                    <span>

                                        {{ number_format($item->skor_total,2) }}

                                    </span>

                                    <span>100</span>

                                </div>

                                <div class="progress mt-1" style="height:10px">

                                    <div
                                        class="progress-bar bg-{{ $badge }}"
                                        role="progressbar"
                                        style="width: {{ min($item->skor_total,100) }}%;">

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="badge bg-{{ $badge }}">

                                    {{ ucfirst($item->tingkat_risiko) }}

                                </span>

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($item->dihitung_pada)->format('d M Y H:i') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center py-5">

                                <h5 class="text-muted">

                                    Belum ada data skor risiko.

                                </h5>

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

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const risikoCanvas = document.getElementById('grafikRisiko');
    const skorCanvas = document.getElementById('grafikSkor');

    console.log('Jumlah Data:', {{ $risiko->count() }});
    console.log(risikoCanvas);
    console.log(skorCanvas);

    if (risikoCanvas) {
        new Chart(risikoCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Rendah', 'Sedang', 'Tinggi'],
                datasets: [{
                    data: [{{ $rendah }}, {{ $sedang }}, {{ $tinggi }}],
                    backgroundColor: [
                        '#22c55e',
                        '#f59e0b',
                        '#ef4444'
                    ]
                }]
            }
        });
    }

    if (skorCanvas) {
        new Chart(skorCanvas, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($risiko as $item)
                        "{{ $item->negara->nama }}",
                    @endforeach
                ],
                datasets: [{
                    label: 'Skor Total',
                    data: [
                        @foreach($risiko as $item)
                            {{ $item->skor_total }},
                        @endforeach
                    ]
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

});
</script>
@endsection