@extends('layouts.app')

@section('style')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>

#map{
    width:100%;
    height:600px;
    border-radius:12px;
}

.card-dashboard{
    border:none;
    border-radius:15px;
    box-shadow:0 2px 15px rgba(0,0,0,.08);
}

.card-stat{
    border:none;
    border-radius:15px;
    color:white;
    background:linear-gradient(135deg,#0d9488,#2dd4bf);
}

.table thead{
    background:#0d9488;
    color:white;
}

.table tbody tr:hover{
    background:#f8f9fa;
}

.text-primary{
    color:#0d9488 !important;
}

.bg-primary{
    background-color:#0d9488 !important;
}

.spinner-border.text-primary{
    color:#0d9488 !important;
}

.header-banner{
    background:linear-gradient(120deg,#0d9488 0%,#0f766e 45%,#2dd4bf 100%);
    border-radius:18px;
    padding:32px 36px;
    color:white;
    box-shadow:0 8px 24px rgba(13,148,136,.25);
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

.search-wrapper{
    position:relative;
}

.search-wrapper .form-control,
.search-wrapper .form-select{
    padding-left:40px;
    border-radius:10px;
    border:1px solid #e2e6ea;
    background:#f8f9fb;
    transition:.2s;
}

.search-wrapper .form-control:focus,
.search-wrapper .form-select:focus{
    background:white;
    border-color:#2dd4bf;
    box-shadow:0 0 0 .2rem rgba(45,212,191,.15);
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

.card-stat-total{
    border:none;
    border-radius:14px;
    color:white;
    background:linear-gradient(135deg,#0d9488,#2dd4bf);
    height:100%;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:14px 20px;
    box-shadow:0 4px 14px rgba(13,148,136,.25);
}

.card-stat-total .label{
    font-size:.8rem;
    opacity:.85;
    margin-bottom:2px;
}

.card-stat-total .value{
    font-size:1.9rem;
    font-weight:700;
    line-height:1;
}

.card-stat-total .icon-circle{
    width:44px;
    height:44px;
    border-radius:50%;
    background:rgba(255,255,255,.18);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.2rem;
}

</style>

@endsection


@section('content')

<div class="container-fluid">

    <!-- Header -->

    <div class="row mb-4">

        <div class="col">

            <div class="header-banner">

                <h2>

                    Dashboard Lokasi Pelabuhan

                </h2>

                <p>

                    Menampilkan lokasi pelabuhan dunia beserta informasi negara,
                    ukuran pelabuhan, dan tipe pelabuhan.

                </p>

            </div>

        </div>

    </div>



    <!-- Search -->

    <div class="card card-dashboard mb-4">

        <div class="card-body">

            <div class="row align-items-end">

                <div class="col-md-5 mb-3">

                    <label class="form-label fw-semibold">

                        Cari Pelabuhan

                    </label>

                    <div class="search-wrapper">

                        <i class="fas fa-magnifying-glass search-icon"></i>

                        <input
                            type="text"
                            id="searchPelabuhan"
                            class="form-control"
                            placeholder="Ketik nama pelabuhan..."
                        >

                    </div>

                </div>


                <div class="col-md-5 mb-3">

                    <label class="form-label fw-semibold">

                        Cari Negara

                    </label>

                    <div class="search-wrapper">

                        <i class="fas fa-globe search-icon"></i>

                        <select
                            id="filterNegara"
                            class="form-select"
                        >

                            <option value="">

                                Semua Negara

                            </option>

                        </select>

                    </div>

                </div>


                <div class="col-md-2 mb-3">

                    <div class="card-stat-total">

                        <div>

                            <div class="label">
                                Total Pelabuhan
                            </div>

                            <div class="value" id="totalPelabuhan">
                                0
                            </div>

                        </div>

                        <div class="icon-circle">

                            <i class="fas fa-anchor"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- MAP -->

    <div class="card card-dashboard mb-4">

        <div class="card-header bg-white">

            <strong>

                Peta Pelabuhan Dunia

            </strong>

        </div>

        <div class="card-body p-2">

            <div
                id="loading"
                class="text-center py-5"
            >

                <div class="spinner-border text-primary">

                </div>

                <p class="mt-3">

                    Memuat data pelabuhan...

                </p>

            </div>

            <div id="map" style="display:none;"></div>

            <div class="mt-3">

                <span class="badge bg-danger">
                    Pelabuhan Besar
                </span>

                <span class="badge bg-warning text-dark">
                    Pelabuhan Sedang
                </span>

                <span class="badge bg-success">
                    Pelabuhan Kecil
                </span>

            </div>

        </div>

    </div>



    <!-- TABLE -->

    <div class="card card-dashboard">

        <div class="card-header bg-white">

            <strong>

                Daftar Pelabuhan

            </strong>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>

                        <tr>

                            <th width="70">

                                No

                            </th>

                            <th>

                                Nama Pelabuhan

                            </th>

                            <th>

                                Negara

                            </th>

                            <th>

                                Tipe

                            </th>

                            <th>

                                Ukuran

                            </th>

                        </tr>

                    </thead>

                    <tbody id="tablePelabuhan">

                        <tr>

                            <td colspan="5" class="text-center text-secondary">

                                Memuat data pelabuhan...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection



@section('script')

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

/*
|--------------------------------------------------------------------------
| Inisialisasi Leaflet
|--------------------------------------------------------------------------
*/

const map = L.map('map').setView([20,0],2);

L.tileLayer(

    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

    {

        attribution:'&copy; OpenStreetMap'

    }

).addTo(map);

let semuaPelabuhan = [];
let markers = [];

/*
|--------------------------------------------------------------------------
| Ambil data API
|--------------------------------------------------------------------------
*/
async function loadPelabuhan()
{
    try {

        const response = await fetch('/api/pelabuhan');

        const data = await response.json();

        if (data.success) {

            semuaPelabuhan = data.data;

        } else {

            semuaPelabuhan = data;

        }

        isiDropdownNegara();

        tampilkanData(semuaPelabuhan);

        document.getElementById('loading').style.display = 'none';

        document.getElementById('map').style.display = 'block';

        map.invalidateSize();

    } catch(error){

        console.error(error);

        document.getElementById('loading').innerHTML =
            '<p class="text-danger">Gagal memuat data pelabuhan.</p>';

    }

}

/*
|--------------------------------------------------------------------------
| Terjemahkan ukuran pelabuhan ke Bahasa Indonesia
|--------------------------------------------------------------------------
*/

function terjemahUkuran(ukuran){

    switch((ukuran ?? "").toLowerCase()){

        case "large":

            return "Besar";

        case "medium":

            return "Sedang";

        case "small":

            return "Kecil";

        default:

            return ukuran ?? "-";

    }

}

/*
|--------------------------------------------------------------------------
| Isi dropdown negara
|--------------------------------------------------------------------------
*/

function isiDropdownNegara(){

    const select = document.getElementById('filterNegara');

    const negara = [...new Set(

        semuaPelabuhan.map(item=>item.negara?.nama)

    )].sort();

    negara.forEach(function(item){

        if(!item) return;

        select.innerHTML +=

        `<option value="${item}">${item}</option>`;

    });

}

/*
|--------------------------------------------------------------------------
| Menampilkan marker + tabel
|--------------------------------------------------------------------------
*/

function tampilkanData(data){

    /*
    |----------------------------------------
    | Hapus marker lama
    |----------------------------------------
    */

    markers.forEach(function(marker){

        map.removeLayer(marker);

    });

    markers = [];

    /*
    |----------------------------------------
    | Total
    |----------------------------------------
    */

    document.getElementById('totalPelabuhan').innerHTML = data.length;

    /*
    |----------------------------------------
    | Table + Marker (satu perulangan)
    |----------------------------------------
    */

    let html = '';

    data.forEach(function(item, index){

        /*
        | ---- Baris tabel untuk setiap item ----
        */

        html += `
        <tr>

            <td>${index + 1}</td>

            <td>${item.nama_pelabuhan}</td>

            <td>${item.negara?.nama ?? '-'}</td>

            <td>
                <span class="badge bg-success">
                    ${item.tipe_pelabuhan ?? '-'}
                </span>
            </td>

            <td>
                <span class="badge bg-primary">
                    ${terjemahUkuran(item.ukuran_pelabuhan)}
                </span>
            </td>

        </tr>
        `;

        /*
        | ---- Marker hanya jika ada koordinat ----
        */

        if(item.lintang && item.bujur){

            let warna = "blue";

            switch((item.ukuran_pelabuhan ?? "").toLowerCase()){

                case "large":

                    warna = "red";

                    break;

                case "medium":

                    warna = "orange";

                    break;

                case "small":

                    warna = "green";

                    break;

            }

            let icon = L.icon({

                iconUrl:`https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${warna}.png`,

                shadowUrl:"https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",

                iconSize:[25,41],

                iconAnchor:[12,41],

                popupAnchor:[1,-34]

            });

            let marker = L.marker(

                [

                    item.lintang,

                    item.bujur

                ],

                {

                    icon:icon

                }

            ).addTo(map);

            marker.bindPopup(`

            <div style="min-width:220px">

            <h6 class="fw-bold text-primary">

            ${item.nama_pelabuhan}

            </h6>

            <hr>

            <p class="mb-1">

            🌍 <b>Negara</b>

            <br>

            ${item.negara?.nama ?? "-"}

            </p>

            <p class="mb-1">

            🚢 <b>Tipe</b>

            <br>

            ${item.tipe_pelabuhan ?? "-"}

            </p>

            <p>

            📦 <b>Ukuran</b>

            <br>

            ${terjemahUkuran(item.ukuran_pelabuhan)}

            </p>

            </div>

            `);

            markers.push(marker);

        }

    });

    /*
    |----------------------------------------
    | Kalau hasil filter kosong
    |----------------------------------------
    */

    if(data.length == 0){

        html = `
        <tr>

            <td colspan="5" class="text-center text-secondary">

                Data pelabuhan tidak ditemukan.

            </td>

        </tr>
        `;

    }

    document.getElementById('tablePelabuhan').innerHTML = html;

    /*
    |----------------------------------------
    | Fokuskan peta ke marker yang tampil
    |----------------------------------------
    */

    if(markers.length > 0){

        let group = L.featureGroup(markers);

        map.fitBounds(
            group.getBounds(),
            {
                padding:[50,50]
            }
        );
    }

}

/*
|--------------------------------------------------------------------------
| Filter (Cari Pelabuhan + Cari Negara)
|--------------------------------------------------------------------------
*/

function filterData(){

    let keyword = document
        .getElementById('searchPelabuhan')
        .value
        .toLowerCase();

    let negara = document
        .getElementById('filterNegara')
        .value;

    let hasil = semuaPelabuhan.filter(function(item){

        let cocokPelabuhan = item.nama_pelabuhan

            .toLowerCase()

            .includes(keyword);

        let cocokNegara =

            negara == '' ||

            item.negara?.nama == negara;

        return cocokPelabuhan && cocokNegara;

    });

    tampilkanData(hasil);

}

/*
|--------------------------------------------------------------------------
| Event
|--------------------------------------------------------------------------
*/

document

.getElementById('searchPelabuhan')

.addEventListener(

'keyup',

filterData

);

document

.getElementById('filterNegara')

.addEventListener(

'change',

filterData

);

/*
|--------------------------------------------------------------------------
| Jalankan
|--------------------------------------------------------------------------
*/

loadPelabuhan();

</script>

@endsection