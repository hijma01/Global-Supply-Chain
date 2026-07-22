@extends('layouts.app')

@section('style')

<style>

.header-banner{
    background:linear-gradient(120deg,#059669 0%,#047857 45%,#34d399 100%);
    border-radius:18px;
    padding:32px 36px;
    color:white;
    box-shadow:0 8px 24px rgba(5,150,105,.25);
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
    border-color:#34d399;
    box-shadow:0 0 0 .2rem rgba(52,211,153,.2);
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

.card-kurs{
    border:none;
    border-radius:18px;
    background:linear-gradient(135deg,#059669,#34d399);
    color:white;
    box-shadow:0 10px 26px rgba(5,150,105,.28);
    overflow:hidden;
    position:relative;
}

.card-kurs::after{
    content:"";
    position:absolute;
    right:-30px;
    top:-30px;
    width:150px;
    height:150px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
}

.card-kurs .icon-uang{
    width:42px;
    height:42px;
    border-radius:50%;
    background:rgba(255,255,255,.18);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.1rem;
    margin:0 auto 10px auto;
}

.card-kurs .label-kecil{
    font-size:.75rem;
    opacity:.85;
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:4px;
}

.card-kurs h1{
    font-weight:800;
    font-size:2rem;
    margin-bottom:6px;
    position:relative;
    z-index:1;
}

.card-kurs .mata-uang-badge{
    display:inline-block;
    background:rgba(255,255,255,.18);
    border-radius:30px;
    padding:4px 14px;
    font-size:.9rem;
    font-weight:600;
    margin-bottom:8px;
}

.card-kurs small{
    opacity:.8;
}

.card-grafik .card-header{
    background:linear-gradient(120deg,#059669,#34d399);
    color:white;
    border:none;
    border-radius:16px 16px 0 0 !important;
    font-weight:600;
    padding:16px 20px;
}

.card-grafik .card-header i{
    margin-right:8px;
}

.empty-state{
    color:#adb5bd;
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

                    Nilai Tukar Mata Uang

                </h2>

                <p>

                    Pantau nilai tukar mata uang secara real-time.

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

    <!-- Nilai Tukar -->

    <div class="row">

        <div class="col-md-12">

            <div class="card card-kurs">

                <div class="card-body text-center py-4">

                    <div class="icon-uang">

                        <i class="fas fa-money-bill-transfer"></i>

                    </div>

                    <div class="label-kecil">

                        Nilai Tukar Saat Ini

                    </div>

                    <h1 id="kurs">

                        -

                    </h1>

                    <div class="mata-uang-badge" id="mataUang">

                        -

                    </div>

                    <br>

                    <small id="tanggal">

                        -

                    </small>

                </div>

            </div>

        </div>

    </div>

    <!-- Grafik -->

    <div class="row mt-4">

        <div class="col-md-12">

            <div class="card card-dashboard card-grafik">

                <div class="card-header">

                    <i class="fas fa-chart-line"></i>

                    Grafik Perubahan Kurs

                </div>

                <div class="card-body">

                    <canvas id="grafikKurs"></canvas>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<!-- Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const negaraSelect = document.getElementById('negaraSelect');


// ============================
// Ambil daftar negara
// ============================

fetch('/api/negara')

.then(response => response.json())

.then(data=>{

    data.forEach(negara=>{

        negaraSelect.innerHTML += `
            <option value="${negara.id}">
                ${negara.nama}
            </option>
        `;

    });

});


// ============================
// Inisialisasi Grafik
// ============================

const grafik = new Chart(

document.getElementById('grafikKurs'),

{

    type:'line',

    data:{

        labels:[],

        datasets:[{

            label:'Nilai Tukar',

            data:[],

            borderColor:'#059669',

            backgroundColor:'rgba(5,150,105,0.12)',

            pointBackgroundColor:'#059669',

            pointBorderColor:'#ffffff',

            pointRadius:5,

            pointHoverRadius:7,

            borderWidth:3,

            tension:0.4,

            fill:true

        }]

    },

    options:{

        responsive:true,

        plugins:{

            legend:{

                display:true

            }

        },

        scales:{

            y:{

                grid:{

                    color:'#e8f7f0'

                }

            },

            x:{

                grid:{

                    display:false

                }

            }

        }

    }

});


// ============================
// Ketika Negara Dipilih
// ============================

negaraSelect.addEventListener('change', function () {

    let id = this.value;

    console.log("ID Negara:", id);

    fetch('/api/nilai-tukar/realtime/'+id)

    .then(response=>response.json())

    .then(res=>{

        console.log(res);

        if (!res.success) {

        document.getElementById("kurs").innerHTML = "-";
        document.getElementById("mataUang").innerHTML = "-";
        document.getElementById("tanggal").innerHTML = "-";

        alert(res.message);

        return;
    }

    document.getElementById("kurs").innerHTML =
        Number(res.nilai_tukar.nilai_kurs).toLocaleString();

    document.getElementById("mataUang").innerHTML =
        res.nilai_tukar.mata_uang_dasar +
        " → " +
        res.nilai_tukar.mata_uang_tujuan;

    document.getElementById("tanggal").innerHTML =
        "Terakhir diperbarui : " +
        res.nilai_tukar.tanggal;

    let kurs = Number(res.nilai_tukar.nilai_kurs);

    grafik.data.labels = [
        "Sen",
        "Sel",
        "Rab",
        "Kam",
        "Jum",
        "Sab",
        "Min"
    ];

    grafik.data.datasets[0].data = [
        kurs - 120,
        kurs - 90,
        kurs - 70,
        kurs - 40,
        kurs - 15,
        kurs + 10,
        kurs
    ];

    grafik.update();

})

.catch(error => {

    console.error(error);

    alert("Terjadi kesalahan saat mengambil data kurs.");

});

});

</script>

@endsection