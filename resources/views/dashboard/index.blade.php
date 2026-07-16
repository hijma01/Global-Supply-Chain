@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Judul -->
    <div class="row mb-4">
        <div class="col">
            <h2>Global Supply Chain Dashboard</h2>
            <p class="text-secondary">
                Pantau data ekonomi, cuaca, nilai tukar, pelabuhan, dan risiko global
            </p>
        </div>
    </div>

    <!-- Dropdown Negara -->
    <div class="row mb-4">
        <div class="col-md-5">
            <label class="form-label fw-bold">Pilih Negara</label>

            <select class="form-select" id="negaraSelect">
                <option value="">-- Pilih Negara --</option>
            </select>

        </div>
    </div>

    <!-- Statistik -->
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>GDP</h6>
                    <h3 id="gdp">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Inflasi</h6>
                    <h3 id="inflasi">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Populasi</h6>
                    <h3 id="populasi">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Mata Uang</h6>
                    <h3 id="currency">-</h3>
                </div>
            </div>
        </div>

    </div>
    <div class="row mt-4">

    <div class="col-md-12">

        <div class="card dashboard-card">

            <div class="card-body text-center">

                <h5>Cuaca Saat Ini</h5>

                <h2 id="statusCuaca">-</h2>

                <h4 id="suhu">-</h4>

            </div>

        </div>

    </div>

</div>

    <!-- Visualisasi Data -->

    <div class="row mt-4">

        <div class="col">

            <h4>Visualisasi Data</h4>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-4">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Grafik GDP</h5>

                    <canvas id="gdpChart"></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-4">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Grafik Inflasi</h5>

                    <canvas id="inflasiChart"></canvas>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-4">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Grafik Nilai Tukar</h5>

                    <canvas id="nilaiTukarChart"></canvas>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-4">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Grafik Risiko</h5>

                    <canvas id="risikoChart"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('script')

<script>

const negaraSelect = document.getElementById('negaraSelect');

// ===========================
// Ambil daftar negara
// ===========================

fetch('/api/negara')

.then(response => response.json())

.then(data => {

    data.forEach(negara => {

        negaraSelect.innerHTML += `

            <option value="${negara.id}">

                ${negara.nama}

            </option>

        `;

    });

})

.catch(error => {

    console.log(error);

});

// ===========================
// Ketika negara dipilih
// ===========================

negaraSelect.addEventListener('change', function () {

    let id = this.value;

    if(id == "") return;

    fetch('/api/negara/' + id)

    .then(response => response.json())

    .then(data => {

        console.log(data);

        document.getElementById("populasi").innerHTML =
            data.populasi
            ? Number(data.populasi).toLocaleString()
            : "-";

        document.getElementById("currency").innerHTML =
            data.nama_mata_uang ?? "-";


    })

    .catch(error => {

        console.log(error);

    });
// ===========================
// Ambil Data Ekonomi Real-time
// ===========================

fetch('/api/data-ekonomi/realtime/' + id)
.then(response => response.json())
.then(res => {

    console.log(res);

    if(!res.success){
        document.getElementById("gdp").innerHTML = "-";
        document.getElementById("inflasi").innerHTML = "-";
        return;
    }

    document.getElementById("gdp").innerHTML =
        Number(res.data_ekonomi.pdb).toLocaleString();

    document.getElementById("inflasi").innerHTML =
        res.data_ekonomi.tingkat_inflasi + " %";

})
.catch(error => console.log(error));

    // ===========================
// Ambil Cuaca Real-time
// ===========================

fetch('/api/cuaca/realtime/' + id)
.then(response => response.json())
.then(res => {

    console.log(res);

    if(!res.success){
        document.getElementById("statusCuaca").innerHTML = "-";
        document.getElementById("suhu").innerHTML = "-";
        return;
    }

    let status = "☀ Cerah";

    if(res.cuaca.curah_hujan > 0){
        status = "🌧 Hujan";
    }

    if(res.cuaca.tingkat_risiko_badai == "tinggi"){
        status = "⛈ Badai";
    }

    document.getElementById("statusCuaca").innerHTML = status;

    document.getElementById("suhu").innerHTML =
        res.cuaca.suhu + " °C";

})
.catch(error => console.log(error));

});

</script>

@endsection