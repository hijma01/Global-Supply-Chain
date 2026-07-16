<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function negara()
    {
        return view('dashboard.negara');
    }

    public function cuaca()
    {
        return view('dashboard.cuaca');
    }

    public function nilaiTukar()
    {
        return view('dashboard.nilai-tukar');
    }

    public function berita()
    {
        return view('dashboard.berita');
    }

    public function pelabuhan()
    {
        return view('dashboard.pelabuhan');
    }

    public function risiko()
    {
        return view('dashboard.risiko');
    }

    public function pantauan()
    {
        return view('dashboard.pantauan');
    }

    public function perbandingan()
    {
        return view('dashboard.perbandingan');
    }
}