@extends('layouts.app')

@section('style')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>

.header-banner{
    background:linear-gradient(120deg,#0891b2 0%,#0e7490 45%,#22d3ee 100%);
    border-radius:18px;
    padding:32px 36px;
    color:white;
    box-shadow:0 8px 24px rgba(8,145,178,.25);
    position:relative;
    overflow:hidden;
}

.header-banner::after{
    content:"";
    position:absolute;
    right:-40px;
    top:-40px;
    width:180px;
    height:180px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
}

.header-banner::before{
    content:"";
    position:absolute;
    right:60px;
    bottom:-60px;
    width:140px;
    height:140px;
    background:rgba(255,255,255,.06);
    border-radius:50%;
}

.header-banner h2{
    font-weight:700;
    margin-bottom:6px;
    position:relative;
    z-index:1;
}

.header-banner p{
    margin-bottom:0;
    opacity:.9;
    position:relative;
    z-index:1;
}

.card-dashboard{
    border:none;
    border-radius:16px;
    box-shadow:0 2px 15px rgba(0,0,0,.08);
}

.search-wrapper{
    position:relative;
}

.search-wrapper .form-select{
    padding-left:40px;
    border-radius:10px;
    border:1px solid #e2e6ea;
    background:#f8f9fb;
    transition:.2s;
}

.search-wrapper .form-select:focus{
    background:white;
    border-color:#22d3ee;
    box-shadow:0 0 0 .2rem rgba(34,211,238,.2);
}

.search-wrapper .search-icon{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#adb5bd;
    pointer-events:none;
    z-index:5;
}

.weather-card{
    border:none;
    border-radius:16px;
    box-shadow:0 4px 16px rgba(0,0,0,.06);
    height:100%;
    transition:.2s;
}

.weather-card:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(0,0,0,.1);
}

.weather-card .icon-circle{
    width:52px;
    height:52px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.4rem;
    margin:0 auto 12px auto;
}

.weather-card .label{
    font-size:.78rem;
    color:#6c757d;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:4px;
}

.weather-card .value{
    font-size:1.6rem;
    font-weight:700;
    color:#212529;
    margin-bottom:0;
}

.icon-suhu{ background:rgba(239,68,68,.12); color:#ef4444; }
.icon-hujan{ background:rgba(8,145,178,.12); color:#0891b2; }
.icon-angin{ background:rgba(100,116,139,.12); color:#64748b; }
.icon-badai{ background:rgba(245,158,11,.12); color:#f59e0b; }

.status-card{
    border:none;
    border-radius:18px;
    background:linear-gradient(135deg,#0891b2,#22d3ee);
    color:white;
    box-shadow:0 10px 26px rgba(8,145,178,.28);
    overflow:hidden;
    position:relative;
}

.status-card::after{
    content:"";
    position:absolute;
    right:-30px;
    top:-30px;
    width:150px;
    height:150px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
}

.status-card .label-kecil{
    font-size:.8rem;
    opacity:.85;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:6px;
    position:relative;
    z-index:1;
}

.status-card h2{
    font-weight:800;
    margin-bottom:0;
    position:relative;
    z-index:1;
}

.card-map .card-header{
    background:linear-gradient(120deg,#0891b2,#22d3ee);
    color:white;
    border:none;
    border-radius:16px 16px 0 0 !important;
    font-weight:600;
    padding:16px 20px;
}

.card-map .card-header i{
    margin-right:8px;
}

#map{
    width:100%;
    height:500px;
    border-radius:0 0 16px 16px;
}

.empty-map{
    height:500px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    color:#adb5bd;
    background:#f8f9fb;
    border-radius:0 0 16px 16px;
}

.empty-map i{
    font-size:2.2rem;
    margin-bottom:10px;
}

</style>

@endsection

@section('content')

<div class="container-fluid">

    <!-- Judul -->
    <div class="row mb-4">
        <div class="col">

            <div class="header-banner">

                <h2>

                    Cuaca Global

                </h2>

                <p>

                    Pantau kondisi cuaca negara secara real-time.

                </p>

            </div>

        </div>
    </div>

    <!-- Pilih Negara -->
    <div class="row mb-4">
        <div class="col-md-5">

            <div class="card card-dashboard">

                <div class="card-body">

                    <label class="form-label fw-semibold">
                        Pilih Negara
                    </label>

                    <div class="search-wrapper">

                        <i class="fas fa-globe search-icon"></i>

                        <select id="negaraSelect" class="form-select">
                            <option value="">-- Pilih Negara --</option>
                        </select>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Kartu Statistik Cuaca -->
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card weather-card">
                <div class="card-body text-center">
                    <div class="icon-circle icon-suhu">
                        <i class="fas fa-temperature-half"></i>
                    </div>
                    <div class="label">Suhu</div>
                    <h3 class="value" id="suhu">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card weather-card">
                <div class="card-body text-center">
                    <div class="icon-circle icon-hujan">
                        <i class="fas fa-cloud-rain"></i>
                    </div>
                    <div class="label">Curah Hujan</div>
                    <h3 class="value" id="hujan">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card weather-card">
                <div class="card-body text-center">
                    <div class="icon-circle icon-angin">
                        <i class="fas fa-wind"></i>
                    </div>
                    <div class="label">Kecepatan Angin</div>
                    <h3 class="value" id="angin">-</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card weather-card">
                <div class="card-body text-center">
                    <div class="icon-circle icon-badai">
                        <i class="fas fa-cloud-bolt"></i>
                    </div>
                    <div class="label">Risiko Badai</div>
                    <h3 class="value" id="badai">-</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Status Cuaca -->
    <div class="row mt-2">

        <div class="col-md-12">

            <div class="card status-card">

                <div class="card-body text-center py-4">

                    <div class="label-kecil">
                        Status Cuaca Saat Ini
                    </div>

                    <h2 id="statusCuaca">-</h2>

                </div>

            </div>

        </div>

    </div>

    <!-- Peta Lokasi -->
    <div class="row mt-4">

        <div class="col-md-12">

            <div class="card card-dashboard card-map">

                <div class="card-header">
                    <i class="fas fa-map-location-dot"></i>
                    Lokasi Negara
                </div>

                <div class="card-body p-0">

                    <div id="mapEmpty" class="empty-map">
                        <i class="fas fa-earth-asia"></i>
                        <div>Pilih negara untuk menampilkan lokasi di peta</div>
                    </div>

                    <div id="map" style="display:none;"></div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

const negaraSelect = document.getElementById('negaraSelect');

const suhu = document.getElementById('suhu');
const hujan = document.getElementById('hujan');
const angin = document.getElementById('angin');
const badai = document.getElementById('badai');
const statusCuaca = document.getElementById('statusCuaca');

let daftarNegara = [];

/*
|--------------------------------------------------------------------------
| Inisialisasi Peta (Leaflet)
|--------------------------------------------------------------------------
*/

let map = null;
let markerAktif = null;

function pastikanMapSiap(){

    if(map) return;

    map = L.map('map').setView([20, 0], 2);

    L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            attribution:'&copy; OpenStreetMap'

        }

    ).addTo(map);

}

function tampilkanLokasiDiPeta(negara){

    document.getElementById('mapEmpty').style.display = 'none';
    document.getElementById('map').style.display = 'block';

    pastikanMapSiap();

    map.invalidateSize();

    if(markerAktif){

        map.removeLayer(markerAktif);

        markerAktif = null;

    }

    const lat = negara?.lintang;
    const lng = negara?.bujur;

    if(!lat || !lng){

        return;

    }

    markerAktif = L.marker([lat, lng]).addTo(map);

    markerAktif.bindPopup(

        `<b>${negara.nama}</b>`

    ).openPopup();

    map.setView([lat, lng], 5);

}

/*
|--------------------------------------------------------------------------
| Ambil daftar negara
|--------------------------------------------------------------------------
*/

fetch('/api/negara')

.then(response => {

    if (!response.ok) {

        throw new Error('Gagal mengambil daftar negara.');

    }

    return response.json();

})

.then(data => {

    daftarNegara = data;

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

        document.getElementById('map').style.display = 'none';
        document.getElementById('mapEmpty').style.display = 'flex';

        if(markerAktif && map){

            map.removeLayer(markerAktif);

            markerAktif = null;

        }

        return;

    }

    const negaraTerpilih = daftarNegara.find(

        item => String(item.id) === String(id)

    );

    if(negaraTerpilih){

        tampilkanLokasiDiPeta(negaraTerpilih);

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