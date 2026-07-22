<?php

namespace App\Http\Controllers;
use App\Models\Pengguna;
use App\Models\Pelabuhan;
use App\Models\CacheBerita;

class DashboardController extends Controller
{

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
        $berita = CacheBerita::latest('diterbitkan_pada')
            ->paginate(12);

        return view('dashboard.berita', compact('berita'));
    }

    public function pelabuhan()
    {
        return view('dashboard.pelabuhan');
    }

    public function risiko()
    {
        return view('dashboard.risiko');
    }

    public function perbandingan()
    {
        return view('dashboard.perbandingan');
    }

    public function admin()
    {
        return view('admin.dashboard', [
            'jumlahPengguna'  => Pengguna::count(),
            'jumlahPelabuhan' => Pelabuhan::count(),
            'jumlahBerita'    => CacheBerita::count(),
        ]);
    }
}