@extends('layouts.app')
@section('style')

<style>

.page-title{
    font-weight:700;
    color:#1f2937;
}

.page-subtitle{
    color:#6b7280;
    font-size:.95rem;
}

.stat-card{
    border-radius:18px;
    transition:.3s;
    overflow:hidden;
}

.stat-card:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 30px rgba(0,0,0,.08)!important;
}

.icon-box{
    width:60px;
    height:60px;
    border-radius:16px;
    display:flex;
    justify-content:center;
    align-items:center;
    color:#fff;
    font-size:24px;
}

.logistics{
    border-left:5px solid #16a34a;
}

.trade{
    border-left:5px solid #2563eb;
}

.shipping{
    border-left:5px solid #f59e0b;
}

.economy{
    border-left:5px solid #dc2626;
}

.news-card{

    border-radius:18px;

    transition:.30s;

    overflow:hidden;

    height:100%;

}

.news-card:hover{

    transform:translateY(-8px);

    box-shadow:0 18px 35px rgba(0,0,0,.12)!important;

}

.news-title{

    font-size:1.08rem;

    line-height:1.45;

    min-height:58px;

}

.news-card p{

    font-size:.93rem;

    line-height:1.7;

}

.badge{

    padding:8px 14px;

    border-radius:50px;

    font-size:.75rem;

    font-weight:600;

    letter-spacing:.4px;

}

#searchNews{

    border-radius:12px;

    height:48px;

}

#filterKategori,

#filterSentimen{

    border-radius:12px;

    height:48px;

}

.btn{

    border-radius:12px;

    font-weight:600;

}

.btn-outline-primary:hover{

    transform:scale(1.02);

}

.card-footer{

    padding:18px;

}


.pagination{

    gap:8px;

}

.page-link{

    border:none;

    border-radius:10px !important;

    color:#0d6efd;

    padding:10px 16px;

}

.page-item.active .page-link{

    background:#0d6efd;

}

.empty-icon{

    font-size:70px;

    color:#bcbcbc;

}

@media (max-width:992px){

.news-title{

    min-height:auto;

}

}

@media (max-width:768px){

.icon-box{

    width:50px;

    height:50px;

    font-size:20px;

}

.stat-card h3{

    font-size:1.6rem;

}

}

@media (max-width:576px){

.news-card{

    margin-bottom:20px;

}

.page-title{

    font-size:1.6rem;

}

}

</style>

@endsection

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-newspaper text-primary"></i>
                Global Supply Chain News
            </h2>

            <p class="text-muted mb-0">
                Menampilkan berita terkait Logistics, Trade, Shipping, dan Economy
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <button class="btn btn-primary">
                <i class="fa-solid fa-rotate"></i>
                Refresh
            </button>

        </div>

    </div>

    <!-- Statistik -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm stat-card logistics">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Logistics
                            </small>

                            <h3 class="fw-bold mt-2">
                                {{ $berita->where('kategori','logistik')->count() }}
                            </h3>

                            <small>Berita</small>

                        </div>

                        <div class="icon-box bg-success">

                            <i class="fa-solid fa-truck-fast"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm stat-card trade">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Trade
                            </small>

                            <h3 class="fw-bold mt-2">
                                {{ $berita->where('kategori','perdagangan')->count() }}
                            </h3>

                            <small>Berita</small>

                        </div>

                        <div class="icon-box bg-primary">

                            <i class="fa-solid fa-globe"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm stat-card shipping">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Shipping
                            </small>

                            <h3 class="fw-bold mt-2">
                                {{ $berita->where('kategori','pelayaran')->count() }}
                            </h3>

                            <small>Berita</small>

                        </div>

                        <div class="icon-box bg-warning">

                            <i class="fa-solid fa-ship"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm stat-card economy">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Economy
                            </small>

                            <h3 class="fw-bold mt-2">
                                {{ $berita->where('kategori','ekonomi')->count() }}
                            </h3>

                            <small>Berita</small>

                        </div>

                        <div class="icon-box bg-danger">

                            <i class="fa-solid fa-chart-line"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Filter dan Pencarian -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-lg-4 mb-3">

                    <input
                        type="text"
                        class="form-control"
                        id="searchNews"
                        placeholder="Cari judul berita...">

                </div>

                <div class="col-lg-3 mb-3">

                    <select
                        class="form-select"
                        id="filterKategori">

                        <option value="semua">
                            Semua Kategori
                        </option>

                        <option value="logistik">
                            Logistics
                        </option>

                        <option value="perdagangan">
                            Trade
                        </option>

                        <option value="pelayaran">
                            Shipping
                        </option>

                        <option value="ekonomi">
                            Economy
                        </option>

                    </select>

                </div>

                <div class="col-lg-2 mb-3">

                    <select
                        class="form-select"
                        id="filterSentimen">

                        <option value="semua">
                            Semua Sentimen
                        </option>

                        <option value="positif">
                            Positif
                        </option>

                        <option value="netral">
                            Netral
                        </option>

                        <option value="negatif">
                            Negatif
                        </option>

                    </select>

                </div>

                <div class="col-lg-3">

                    <div class="alert alert-light border mb-0">

                        Total Berita :

                        <strong>

                            {{ $berita->total() }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Daftar Berita -->
    <div class="row" id="newsContainer">
    @forelse($berita as $item)

<div
    class="col-xl-6 col-lg-6 mb-4 news-item"
    data-kategori="{{ $item->kategori }}"
    data-sentimen="{{ $item->label_sentimen }}">

    <div class="card h-100 border-0 shadow-sm news-card">

        <div class="card-body">

            <!-- Badge -->
            <div class="d-flex justify-content-between align-items-start mb-3">

                <div>

                    @php

                        $warnaKategori = match($item->kategori){

                            'logistik' => 'success',

                            'perdagangan' => 'primary',

                            'pelayaran' => 'warning',

                            'ekonomi' => 'danger',

                            default => 'secondary'

                        };

                    @endphp

                    <span class="badge bg-{{ $warnaKategori }}">

                        {{ ucfirst($item->kategori) }}

                    </span>

                </div>

                <div>

                    @php

                        $warnaSentimen = match($item->label_sentimen){

                            'positif' => 'success',

                            'negatif' => 'danger',

                            default => 'secondary'

                        };

                    @endphp

                    <span class="badge bg-{{ $warnaSentimen }}">

                        {{ ucfirst($item->label_sentimen) }}

                    </span>

                </div>

            </div>

            <!-- Judul -->

            <h5 class="fw-bold mb-3 news-title">

                {{ $item->judul }}

            </h5>

            <!-- Deskripsi -->

            <p class="text-muted mb-4">

                {{ \Illuminate\Support\Str::limit($item->deskripsi,180) }}

            </p>

            <!-- Informasi -->

            <div class="row small text-muted mb-3">

                <div class="col-6">

                    <i class="fa-solid fa-calendar-days me-1"></i>

                    {{ optional($item->diterbitkan_pada)->format('d M Y H:i') }}

                </div>

                <div class="col-6 text-end">

                    <i class="fa-solid fa-newspaper me-1"></i>

                    {{ $item->sumber }}

                </div>

            </div>

            <!-- Sentimen -->

            <div class="row mb-3">

                <div class="col-6">

                    <small class="text-success">

                        <i class="fa-solid fa-face-smile"></i>

                        Positif :

                        <strong>

                            {{ $item->skor_positif }}

                        </strong>

                    </small>

                </div>

                <div class="col-6 text-end">

                    <small class="text-danger">

                        <i class="fa-solid fa-face-frown"></i>

                        Negatif :

                        <strong>

                            {{ $item->skor_negatif }}

                        </strong>

                    </small>

                </div>

            </div>

        </div>

        <div class="card-footer bg-white border-0">

            <div class="d-grid">

                <a
                    href="{{ $item->url }}"
                    target="_blank"
                    class="btn btn-outline-primary">

                    <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    Baca Selengkapnya

                </a>

            </div>

        </div>

    </div>

</div>

@empty

<div class="col-12">

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <i class="fa-solid fa-newspaper fa-4x text-secondary mb-3"></i>

            <h4>

                Belum ada berita

            </h4>

            <p class="text-muted">

                Jalankan

                <strong>

                    php artisan berita:update

                </strong>

                untuk mengambil berita terbaru.

            </p>

        </div>

    </div>

</div>

@endforelse

</div>

@if($berita->hasPages())

<div class="d-flex justify-content-center mt-4">

    {{ $berita->links('pagination::bootstrap-5') }}

</div>

@endif

@endsection
@section('script')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchNews');
    const kategoriSelect = document.getElementById('filterKategori');
    const sentimenSelect = document.getElementById('filterSentimen');

    function filterBerita() {

        const keyword = searchInput.value.toLowerCase().trim();
        const kategori = kategoriSelect.value;
        const sentimen = sentimenSelect.value;

        const berita = document.querySelectorAll('.news-item');

        berita.forEach(function(item){

            const judul = item.querySelector('.news-title').textContent.toLowerCase();

            const kategoriItem = item.dataset.kategori;

            const sentimenItem = item.dataset.sentimen;

            const cocokJudul =
                judul.includes(keyword);

            const cocokKategori =
                kategori === "semua" ||
                kategoriItem === kategori;

            const cocokSentimen =
                sentimen === "semua" ||
                sentimenItem === sentimen;

            if(cocokJudul && cocokKategori && cocokSentimen){

                item.style.display = "block";

            }else{

                item.style.display = "none";

            }

        });

    }

    searchInput.addEventListener('keyup', filterBerita);

    kategoriSelect.addEventListener('change', filterBerita);

    sentimenSelect.addEventListener('change', filterBerita);

});

</script>

@endsection