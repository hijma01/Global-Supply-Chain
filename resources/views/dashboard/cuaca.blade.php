@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row mb-4">
        <div class="col">
            <h2>Cuaca Global</h2>
            <p class="text-secondary">
                Pantau kondisi cuaca negara secara real-time.
            </p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-5">
            <label class="form-label fw-bold">
                Pilih Negara
            </label>

            <select id="negaraSelect" class="form-select">
                <option value="">-- Pilih Negara --</option>
            </select>
        </div>
    </div>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h6>🌡 Suhu</h6>
                    <h3 id="suhu">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h6>🌧 Curah Hujan</h6>
                    <h3 id="hujan">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h6>💨 Kecepatan Angin</h6>
                    <h3 id="angin">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h6>⛈ Risiko Badai</h6>
                    <h3 id="badai">-</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-md-12">

            <div class="card dashboard-card">

                <div class="card-body text-center">

                    <h5>Status Cuaca Saat Ini</h5>

                    <h2 id="statusCuaca">-</h2>

                </div>

            </div>

        </div>

    </div>

    <div class="row mt-4">

        <div class="col-md-12">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Lokasi Negara</h5>

                    <div id="map" style="height:500px;"></div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script>

const negaraSelect = document.getElementById('negaraSelect');

const suhu = document.getElementById('suhu');
const hujan = document.getElementById('hujan');
const angin = document.getElementById('angin');
const badai = document.getElementById('badai');
const statusCuaca = document.getElementById('statusCuaca');


fetch('/api/negara')

.then(response => {

    if (!response.ok) {

        throw new Error('Gagal mengambil daftar negara.');

    }

    return response.json();

})

.then(data => {

    data.forEach(negara => {

        const option = document.createElement('option');

        option.value = negara.id;

        option.textContent = negara.nama;

        negaraSelect.appendChild(option);

    });

})

.catch(error => {

    console.error(error);

    alert('Tidak dapat mengambil daftar negara.');

});


negaraSelect.addEventListener('change', function () {

    const id = this.value;

    if (!id) {

        suhu.textContent = "-";
        hujan.textContent = "-";
        angin.textContent = "-";
        badai.textContent = "-";
        statusCuaca.textContent = "-";

        return;

    }

    fetch('/api/cuaca/realtime/' + id)

    .then(response => {

        if (!response.ok) {

            throw new Error('Gagal mengambil data cuaca.');

        }

        return response.json();

    })

    .then(data => {

        console.log(data);

        if (!data.success) {

            alert('Data cuaca tidak tersedia.');

            return;

        }

        if (!data.cuaca) {

            alert('Data cuaca kosong.');

            return;

        }

        const cuaca = data.cuaca;

        suhu.textContent =
            (cuaca.suhu ?? '-') + ' °C';

        hujan.textContent =
            (cuaca.curah_hujan ?? '-') + ' mm';

        angin.textContent =
            (cuaca.kecepatan_angin ?? '-') + ' km/j';

        badai.textContent =
            cuaca.tingkat_risiko_badai ?? '-';


        let status = "☀ Cerah";

        if ((cuaca.curah_hujan ?? 0) > 0) {

            status = "🌧 Hujan";

        }

        if (cuaca.tingkat_risiko_badai === "sedang") {

            status = "🌦 Waspada";

        }

        if (cuaca.tingkat_risiko_badai === "tinggi") {

            status = "⛈ Badai";

        }

        statusCuaca.textContent = status;

    })

    .catch(error => {

        console.error(error);

        alert('Terjadi kesalahan saat mengambil data cuaca.');

    });

});

</script>

@endsection