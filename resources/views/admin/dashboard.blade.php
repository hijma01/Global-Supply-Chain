@extends('layouts.admin')

@section('title','Dashboard Admin')

@section('content')

<div class="row">

    <div class="col-lg-4 mb-4">

        <div class="card card-dashboard card-blue">

            <div class="card-body">

                <h5>
                    <i class="bi bi-people-fill"></i>
                    Pengguna
                </h5>

                <h1 class="my-3">
                    {{ $jumlahPengguna }}
                </h1>

                <a href="{{ route('admin.pengguna.index') }}"
                   class="btn btn-light">

                    Kelola Data

                </a>

            </div>

        </div>

    </div>

    <div class="col-lg-4 mb-4">

        <div class="card card-dashboard card-green">

            <div class="card-body">

                <h5>
                    <i class="bi bi-geo-alt-fill"></i>
                    Pelabuhan
                </h5>

                <h1 class="my-3">
                    {{ $jumlahPelabuhan }}
                </h1>

                <a href="{{ route('admin.pelabuhan.index') }}"
                   class="btn btn-light">

                    Kelola Data

                </a>

            </div>

        </div>

    </div>

    <div class="col-lg-4 mb-4">

        <div class="card card-dashboard card-orange">

            <div class="card-body">

                <h5>
                    <i class="bi bi-newspaper"></i>
                    Berita
                </h5>

                <h1 class="my-3">
                    {{ $jumlahBerita }}
                </h1>

                <a href="{{ route('admin.berita.index') }}"
                   class="btn btn-light">

                    Kelola Data

                </a>

            </div>

        </div>

    </div>

</div>

<div class="activity">

    <h4 class="mb-4">

        <i class="bi bi-clock-history"></i>

        Aktivitas Terbaru

    </h4>

    <ul class="list-group">

        <li class="list-group-item">
            <i class="bi bi-check-circle-fill text-success"></i>
            Pengguna baru berhasil ditambahkan
        </li>

        <li class="list-group-item">
            <i class="bi bi-check-circle-fill text-success"></i>
            Berita cuaca berhasil dipublikasikan
        </li>

        <li class="list-group-item">
            <i class="bi bi-check-circle-fill text-success"></i>
            Data pelabuhan berhasil diperbarui
        </li>

        <li class="list-group-item">
            <i class="bi bi-check-circle-fill text-success"></i>
            Admin berhasil login
        </li>

    </ul>

</div>

@endsection