@extends('layouts.app')

@section('style')

<style>

.stat-card{
    border-radius:18px;
    transition:.3s;
}

.stat-card:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 25px rgba(0,0,0,.15)!important;
}

.icon-box{
    width:60px;
    height:60px;
    border-radius:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
}

.favorite-card{
    border-radius:18px;
    transition:.3s;
    overflow:hidden;
}

.favorite-card:hover{

    transform:translateY(-5px);

    box-shadow:0 15px 30px rgba(0,0,0,.12)!important;

}

.flag-box{

    width:130px;
    height:90px;

    margin:auto;

    background:#fff;

    border-radius:12px;

    border:1px solid #e9ecef;

    display:flex;

    justify-content:center;

    align-items:center;

    box-shadow:0 3px 12px rgba(0,0,0,.08);

}

.country-flag{

    width:110px;

    height:70px;

    object-fit:contain;

}

.info-box{
    background:linear-gradient(135deg,#eef6ff,#dcecff);
    border:1px solid #c7defc;
    border-radius:14px;
    padding:16px;
    height:100%;
    transition:.3s ease;
    box-shadow:0 3px 10px rgba(0,0,0,.05);
}

.info-box:hover{
    background:linear-gradient(135deg,#dcecff,#c8e0ff);
    border-color:#3b82f6;
    transform:translateY(-4px);
}

.badge{

    font-size:13px;

    padding:8px 14px;

    border-radius:30px;

}

.btn-success{

    border-radius:12px;

    font-weight:600;

}

.btn-outline-danger{

    border-radius:12px;

}

.form-select{

    height:50px;

    border-radius:12px;

}

.card-header{

    border-bottom:none;

}


@media(max-width:991px){

    .flag-box{

        margin-bottom:20px;

    }

    .text-end{

        text-align:center!important;

    }

}


@media(max-width:768px){

    .icon-box{

        width:50px;

        height:50px;

    }

    .favorite-card{

        text-align:center;

    }

    .favorite-card h4{

        margin-top:20px;

    }

}

</style>

@endsection



@section('script')

<script>

document.addEventListener('DOMContentLoaded',function(){

    const forms=document.querySelectorAll('form[action*="pantauan"]');

    forms.forEach(function(form){

        if(form.querySelector('input[name="_method"]')){

            form.addEventListener('submit',function(e){

                e.preventDefault();

                if(confirm('Apakah Anda yakin ingin menghapus negara ini dari daftar pantauan?')){

                    form.submit();

                }

            });

        }

    });

});

</script>

@endsection

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-dark">
                Favorite Monitoring List
            </h2>

            <p class="text-secondary mb-0">
                Pantau negara supply favorit Anda secara real-time.
            </p>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    <!-- Statistik -->
    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card stat-card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Favorit
                            </small>

                            <h3 class="fw-bold mt-2">
                                {{ $totalFavorit }}
                            </h3>

                        </div>

                        <div class="icon-box bg-warning">

                            <i class="fas fa-star text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card stat-card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Aman
                            </small>

                            <h3 class="fw-bold text-success mt-2">
                                {{ $totalAman }}
                            </h3>

                        </div>

                        <div class="icon-box bg-success">

                            <i class="fas fa-shield-alt text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card stat-card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Waspada
                            </small>

                            <h3 class="fw-bold text-warning mt-2">
                                {{ $totalWaspada }}
                            </h3>

                        </div>

                        <div class="icon-box bg-warning">

                            <i class="fas fa-exclamation-triangle text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card stat-card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Risiko Tinggi
                            </small>

                            <h3 class="fw-bold text-danger mt-2">
                                {{ $totalRisiko }}
                            </h3>

                        </div>

                        <div class="icon-box bg-danger">

                            <i class="fas fa-radiation text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- Tambah Negara -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-bold">

                <i class="fas fa-plus-circle text-success me-2"></i>

                Tambah Negara ke Pantauan

            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('pantauan.store') }}" method="POST">
                @csrf

                <input type="hidden" id="pengguna_id" name="pengguna_id">

                <div class="row g-2 align-items-center">
                    <div class="col-md-10">
                        <select name="negara_id" class="form-select" required>
                            <option value="" selected disabled>Pilih Negara</option>

                            @foreach($negara as $n)
                                <option value="{{ $n->id }}">{{ $n->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </form>

            <script>
                const pengguna = JSON.parse(localStorage.getItem('pengguna'));

                if (pengguna) {
                    document.getElementById('pengguna_id').value = pengguna.id;
                }
            </script>

        </div>

    </div>



    <!-- Daftar Favorit -->

    <div class="row">

        @forelse($pantauan as $item)

            @php

                $risiko = strtolower(optional($item->negara->risikoTerbaru)->tingkat_risiko);

                if($risiko == 'rendah'){

                    $badge = 'success';

                    $status = 'Aman';

                    $icon = '🟢';

                }elseif($risiko == 'sedang'){

                    $badge = 'warning';

                    $status = 'Waspada';

                    $icon = '🟡';

                }else{

                    $badge = 'danger';

                    $status = 'Risiko Tinggi';

                    $icon = '🔴';

                }

            @endphp
            <div class="col-12 mb-4">

                <div class="card border-0 shadow-sm favorite-card">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <!-- Bendera -->
                            <div class="col-lg-2 col-md-3 text-center">

                                <div class="flag-box">

                                    <img
                                        src="{{ $item->negara->url_bendera }}"
                                        class="country-flag img-fluid"
                                        alt="{{ $item->negara->nama }}">

                                </div>

                            </div>

                            <!-- Informasi Negara -->
                            <div class="col-lg-10 col-md-9">

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <div>

                                        <h4 class="fw-bold mb-1">

                                            {{ $item->negara->nama }}

                                        </h4>

                                        <span class="badge bg-{{ $badge }}">

                                            {{ $icon }} {{ $status }}

                                        </span>

                                    </div>

                                </div>


                                <div class="row g-3">

                                    <!-- PDB -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                GDP

                                            </small>

                                            <h6 class="fw-bold mt-2">

                                                {{ number_format(optional($item->negara->ekonomiTerbaru)->pdb ?? 0,2) }}

                                            </h6>

                                        </div>

                                    </div>


                                    <!-- Inflasi -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                Inflasi

                                            </small>

                                            <h6 class="fw-bold mt-2">

                                                {{ optional($item->negara->ekonomiTerbaru)->tingkat_inflasi ?? '-' }} %

                                            </h6>

                                        </div>

                                    </div>


                                    <!-- Suhu -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                Suhu

                                            </small>

                                            <h6 class="fw-bold mt-2">

                                                {{ optional($item->negara->cuacaTerbaru)->suhu ?? '-' }} °C

                                            </h6>

                                        </div>

                                    </div>


                                    <!-- Curah Hujan -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                Curah Hujan

                                            </small>

                                            <h6 class="fw-bold mt-2">

                                                {{ optional($item->negara->cuacaTerbaru)->curah_hujan ?? '-' }} mm

                                            </h6>

                                        </div>

                                    </div>


                                    <!-- Kecepatan Angin -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                Kecepatan Angin

                                            </small>

                                            <h6 class="fw-bold mt-2">

                                                {{ optional($item->negara->cuacaTerbaru)->kecepatan_angin ?? '-' }} km/jam

                                            </h6>

                                        </div>

                                    </div>


                                    <!-- Risiko Badai -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                Risiko Badai

                                            </small>

                                            <h6 class="fw-bold mt-2 text-capitalize">

                                                {{ optional($item->negara->cuacaTerbaru)->tingkat_risiko_badai ?? '-' }}

                                            </h6>

                                        </div>

                                    </div>


                                    <!-- Skor Risiko -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                Skor Risiko

                                            </small>

                                            <h6 class="fw-bold mt-2">

                                                {{ optional($item->negara->risikoTerbaru)->skor_total ?? '-' }}

                                            </h6>

                                        </div>

                                    </div>


                                    <!-- Terakhir Update -->
                                    <div class="col-lg-3 col-md-6">

                                        <div class="info-box">

                                            <small class="text-muted">

                                                Terakhir Update

                                            </small>

                                            <h6 class="fw-bold mt-2">

                                                {{ optional($item->negara->cuacaTerbaru)->updated_at?->diffForHumans() ?? '-' }}

                                            </h6>

                                        </div>

                                    </div>

                                </div>


                                <div class="text-end mt-4">

                                    <form
                                        action="{{ route('pantauan.destroy',$item->id) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm">

                                            <i class="fas fa-trash me-1"></i>

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @empty

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="fas fa-star fa-4x text-warning mb-3"></i>

                        <h4>

                            Belum Ada Negara Favorit

                        </h4>

                        <p class="text-muted">

                            Tambahkan negara supply favorit Anda menggunakan form di atas.

                        </p>

                    </div>

                </div>

            </div>

            @endforelse

        </div>

</div>

@endsection