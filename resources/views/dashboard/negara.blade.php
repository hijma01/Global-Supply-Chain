@extends('layouts.app')

@section('style')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>

.flag-card{
    height:260px;
}

.info-card{
    height:120px;
}

.flag-card .card-body{
    padding:0;
    height:100%;
    overflow:hidden;
    border-radius:12px;
}

.info-card h2{
    font-size:2rem;
    margin:0;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}

.flag-card img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.header-card{
    background: linear-gradient(135deg,#0f766e,#14b8a6);
    border-radius:18px;
}

.header-card h2{
    color:#fff;
}

.header-card p{
    color:rgba(255,255,255,.9);
}

</style>

@endsection

@section('content')

<div class="card border-0 shadow-sm mb-4 header-card">

    <div class="card-body p-4">

            <h2 class="fw-bold mb-2">
                Data Negara
            </h2>

            <p class="mb-0 text-light">
                Pilih negara untuk melihat informasi ekonomi, cuaca, mata uang,
                populasi, dan lokasi secara real-time.
            </p>

        </div>

    </div>

    <!-- Pilih Negara -->

    <div class="row mb-4">

        <div class="col-md-5">

            <label class="form-label fw-bold">

                Pilih Negara

            </label>

            <select id="negaraSelect" class="form-select shadow-sm">

                <option value="">-- Pilih Negara --</option>

            </select>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-lg-5">

            <div class="card shadow border-0 flag-card">

                <div class="card-body p-2">

                    <img
                        id="bendera"
                        src=""
                        alt="Bendera Negara"
                        style="visibility:hidden;">

                </div>

            </div>

        </div>

        <!-- Statistik -->
        <div class="col-lg-7">

            <div class="row g-3">

                <!-- GDP -->
                <div class="col-md-6">

                    <div class="card bg-primary text-white shadow border-0 info-card">

                        <div class="card-body">

                            <small>GDP</small>

                            <h2 id="gdp">-</h2>

                        </div>

                    </div>

                </div>

                <!-- Inflasi -->
                <div class="col-md-6">

                    <div class="card bg-success text-white shadow border-0 info-card">

                        <div class="card-body">

                            <small>Inflasi</small>

                            <h2 id="inflasi">-</h2>

                        </div>

                    </div>

                </div>

                <!-- Populasi -->
                <div class="col-md-6">

                    <div class="card bg-warning shadow border-0 info-card">

                        <div class="card-body">

                            <small>Populasi</small>

                            <h2 id="populasi">-</h2>

                        </div>

                    </div>

                </div>

                <!-- Mata Uang -->
                <div class="col-md-6">

                    <div class="card bg-info text-white shadow border-0 info-card">

                        <div class="card-body">

                            <small>Mata Uang</small>

                            <h2 id="currency">-</h2>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- Cuaca -->

    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-primary text-white">

            Cuaca Saat Ini

        </div>

        <div class="card-body text-center">

            <h2 id="statusCuaca">

                -

            </h2>

            <h3 id="suhu">

                -

            </h3>

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

    <!-- Peta -->

    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-success text-white">

            Lokasi Negara

        </div>

        <div class="card-body p-0">

            <div id="map" style="height:500px;"></div>
            
        </div>

    </div>

    <div class="row mt-4">

        <!-- GDP -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">
                    <i class="bi bi-graph-up-arrow me-2"></i>
                    GDP Trend
                </div>

                <div class="card-body">
                    <canvas id="gdpChart" height="120"></canvas>
                </div>

            </div>
        </div>

        <!-- Inflasi -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0">

                <div class="card-header bg-success text-white">
                    <i class="bi bi-bar-chart-line me-2"></i>
                    Inflation Trend
                </div>

                <div class="card-body">
                    <canvas id="inflasiChart" height="120"></canvas>
                </div>

            </div>
        </div>

</div>

@endsection

@section('script')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

const negaraSelect = document.getElementById('negaraSelect');

let map = L.map('map').setView([0,0],2);

let circle = null;

L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
    attribution:'© OpenStreetMap contributors',
    maxZoom:19
}).addTo(map);

// marker awal
let marker = L.marker([0,0]).addTo(map);

let gdpChart = null;
let inflasiChart = null;

function getWeatherIcon(risiko,hujan){

    if(risiko=="tinggi"){
        return "⛈";
    }

    if(hujan>0){
        return "🌧";
    }

    return "☀";
}

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

        window.lat = parseFloat(data.lintang);
        window.lng = parseFloat(data.bujur);
        window.namaNegara = data.nama;

        let bendera = document.getElementById("bendera");

        bendera.src = data.url_bendera;

        bendera.style.visibility = "visible";

        document.getElementById("populasi").innerHTML =
            data.populasi
            ? Number(data.populasi).toLocaleString('id-ID')
            : "-";

        document.getElementById("currency").innerHTML =
            data.nama_mata_uang ?? "-";


        if(data.data_ekonomi && data.data_ekonomi.length > 0){

            let ekonomi = data.data_ekonomi[0];

            document.getElementById("gdp").innerHTML =
                Number(ekonomi.pdb).toLocaleString('id-ID');

            document.getElementById("inflasi").innerHTML =
                ekonomi.tingkat_inflasi + " %";

        }else{

            document.getElementById("gdp").innerHTML = "-";
            document.getElementById("inflasi").innerHTML = "-";

        }

        let labels = [];
        let gdpData = [];
        let inflasiData = [];

        data.data_ekonomi.reverse().forEach(item => {

            labels.push(item.tahun ?? item.created_at.substring(0,10));

            gdpData.push(item.pdb);

            inflasiData.push(item.tingkat_inflasi);

        });
        if(gdpChart){
            gdpChart.destroy();
        }

        gdpChart = new Chart(document.getElementById('gdpChart'),{

            type:'line',

            data:{

                labels:labels,

                datasets:[{

                    label:'GDP',

                    data:gdpData,

                    borderColor:'#2563eb',

                    backgroundColor:'rgba(37,99,235,.2)',

                    fill:true,

                    tension:.4

                }]
            },

            options:{

                responsive:true,

                plugins:{
                    legend:{
                        display:true
                    }
                }

            }

        });

        if(inflasiChart){
            inflasiChart.destroy();
        }

        inflasiChart = new Chart(document.getElementById('inflasiChart'),{

            type:'line',

            data:{

                labels:labels,

                datasets:[{

                    label:'Inflasi',

                    data:inflasiData,

                    borderColor:'#16a34a',

                    backgroundColor:'rgba(22,163,74,.2)',

                    fill:true,

                    tension:.4

                }]
            },

            options:{

                responsive:true,

                plugins:{
                    legend:{
                        display:true
                    }
                }

            }

        });

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

    // icon cuaca
    let icon = L.divIcon({

        html: `
        <div style="font-size:35px">
            ${getWeatherIcon(
                res.cuaca.tingkat_risiko_badai,
                res.cuaca.curah_hujan
            )}
        </div>
        `,

        className: "",

        iconSize:[40,40]

    });

    // marker baru
    marker = L.marker(
        [window.lat,window.lng],
        {
            icon:icon
        }
    ).addTo(map);

    // zoom
    map.setView(
        [window.lat,window.lng],
        5
    );

    // popup
    marker.bindPopup(`
    <b>${window.namaNegara}</b><br>
    🌡 Suhu : ${res.cuaca.suhu} °C<br>
    🌧 Curah Hujan : ${res.cuaca.curah_hujan} mm<br>
    💨 Angin : ${res.cuaca.kecepatan_angin} km/jam<br>
    ⚠ Risiko : ${res.cuaca.tingkat_risiko_badai}
    `).openPopup();

})
.catch(error => console.log(error));

});

</script>

@endsection