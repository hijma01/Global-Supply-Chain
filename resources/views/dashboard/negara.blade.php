@extends('layouts.app')

@section('style')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

@endsection

@section('content')

<div class="container-fluid">

    <div class="row mb-4">

        <div class="col">

            <h2>Data Negara</h2>

            <p class="text-secondary">
                Pilih negara untuk melihat informasi ekonomi, cuaca dan lokasi.
            </p>

        </div>

    </div>

    {{-- Pilih Negara --}}

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


    {{-- Statistik --}}

    <div class="row">

        <div class="col-md-3 mb-3">

            <div class="card bg-primary text-white shadow border-0">

                <div class="card-body">

                    <h6>GDP</h6>

                    <h3 id="gdp">-</h3>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card bg-success text-white shadow border-0">

                <div class="card-body">

                    <h6>Inflasi</h6>

                    <h3 id="inflasi">-</h3>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card bg-warning shadow border-0">

                <div class="card-body">

                    <h6>Populasi</h6>

                    <h3 id="populasi">-</h3>

                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card bg-info text-white shadow border-0">

                <div class="card-body">

                    <h6>Mata Uang</h6>

                    <h3 id="currency">-</h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Cuaca --}}

    <div class="card shadow border-0 mt-4">

        <div class="card-body text-center">

            <h4>Cuaca Saat Ini</h4>

            <h2 id="statusCuaca">-</h2>

            <h3 id="suhu">-</h3>

            <div class="row mt-4">

                <div class="col-md-4">

                    <h6>Curah Hujan</h6>

                    <h5 id="hujan">-</h5>

                </div>

                <div class="col-md-4">

                    <h6>Kecepatan Angin</h6>

                    <h5 id="angin">-</h5>

                </div>

                <div class="col-md-4">

                    <h6>Risiko Badai</h6>

                    <h5 id="badai">-</h5>

                </div>

            </div>

        </div>

    </div>


    {{-- Peta --}}

    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-success text-white">

            Lokasi Negara

        </div>

        <div class="card-body">

            <div id="map" style="height:500px;"></div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

const negaraSelect = document.getElementById('negaraSelect');
let map = L.map('map').setView([0, 0], 2);

L.tileLayer(
    'https://tiles.openfreemap.org/styles/liberty/{z}/{x}/{y}.png',
    {
        attribution: '&copy; OpenFreeMap & OpenStreetMap contributors',
        maxZoom: 19
    }
).addTo(map);

let marker = L.marker([0,0]).addTo(map);


// =======================
// Daftar Negara
// =======================

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


// =======================
// Saat Negara Dipilih
// =======================

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

         marker.setLatLng([
        data.lintang,
        data.bujur
        ]);

        map.setView([
            data.lintang,
            data.bujur
        ], 5);

        marker.bindPopup(data.nama).openPopup();


    })

    .catch(error => {

        console.log(error);

    });

// =======================
// Data Negara
// =======================

fetch('/api/negara/'+id)

.then(res=>res.json())

.then(data=>{

document.getElementById('namaNegara').innerHTML=data.nama;

document.getElementById('kodeNegara').innerHTML=data.kode_negara;

document.getElementById('ibukota').innerHTML=data.ibu_kota;

document.getElementById('wilayah').innerHTML=data.wilayah;

document.getElementById('subWilayah').innerHTML=data.sub_wilayah;

document.getElementById('currency').innerHTML=data.nama_mata_uang;

document.getElementById('populasi').innerHTML=
Number(data.populasi).toLocaleString();


let lat=parseFloat(data.lintang);

let lng=parseFloat(data.bujur);

if(marker){

map.removeLayer(marker);

}

marker=L.marker([lat,lng]).addTo(map);

marker.bindPopup(data.nama).openPopup();

map.setView([lat,lng],5);

});


// =======================
// Data Ekonomi Real-time
// =======================

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


// =======================
// Cuaca Real-time
// =======================

fetch('/api/cuaca/realtime/' + id)
.then(response => response.json())
.then(res => {

    console.log(res);

    if(!res.success){
        document.getElementById("statusCuaca").innerHTML = "-";
        document.getElementById("suhu").innerHTML = "-";
        document.getElementById("hujan").innerHTML = "-";
        document.getElementById("angin").innerHTML = "-";
        document.getElementById("badai").innerHTML = "-";
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
    document.getElementById("hujan").innerHTML = 
        res.cuaca.curah_hujan + " mm";
    document.getElementById("angin").innerHTML = 
        res.cuaca.kecepatan_angin + " km/jam";
    document.getElementById("badai").innerHTML = 
        res.cuaca.tingkat_risiko_badai;

})
.catch(error => console.log(error));

});

</script>

@endsection