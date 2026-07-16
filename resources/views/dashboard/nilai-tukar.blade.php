@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Judul -->
    <div class="row mb-4">

        <div class="col">

            <h2>Nilai Tukar Mata Uang</h2>

            <p class="text-secondary">
                Pantau nilai tukar mata uang secara real-time.
            </p>

        </div>

    </div>

    <!-- Pilih Negara -->
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

    <!-- Nilai Tukar -->

    <div class="row">

        <div class="col-md-12">

            <div class="card shadow border-0">

                <div class="card-body text-center">

                    <h5>Nilai Tukar Saat Ini</h5>

                    <h1 id="kurs">

                        -

                    </h1>

                    <p id="mataUang">

                        -

                    </p>

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

            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">

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

            borderWidth:3,

            tension:0.4,

            fill:false

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


// ============================
// Ketika Negara Dipilih
// ============================

negaraSelect.addEventListener('change',function(){

    let id=this.value;

    if(id=="") return;

    fetch('/api/nilai-tukar/realtime/'+id)

    .then(response=>response.json())

    .then(res=>{

        console.log(res);

        if(!res.success){

            document.getElementById("kurs").innerHTML="-";

            document.getElementById("mataUang").innerHTML="-";

            document.getElementById("tanggal").innerHTML="-";

            return;

        }

        document.getElementById("kurs").innerHTML=

            Number(res.nilai_tukar.nilai_kurs).toLocaleString();

        document.getElementById("mataUang").innerHTML=

            res.nilai_tukar.mata_uang_dasar+

            " → "+

            res.nilai_tukar.mata_uang_tujuan;

        document.getElementById("tanggal").innerHTML=

            "Terakhir diperbarui : "+

            res.nilai_tukar.tanggal;


        // sementara grafik menggunakan contoh data
        grafik.data.labels=[
            "Sen",
            "Sel",
            "Rab",
            "Kam",
            "Jum",
            "Sab",
            "Min"
        ];

        let kurs=Number(res.nilai_tukar.nilai_kurs);

        grafik.data.datasets[0].data=[

            kurs-120,

            kurs-90,

            kurs-70,

            kurs-40,

            kurs-15,

            kurs+10,

            kurs

        ];

        grafik.update();

    });

});

</script>

@endsection