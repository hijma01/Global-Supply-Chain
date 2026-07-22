<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Negara;
use App\Models\PerbandinganNegara;

class PerbandinganController extends Controller
{
    /**
     * Menampilkan halaman perbandingan
     */
    public function index()
    {
        $negara = Negara::orderBy('nama')->get();

        return view('dashboard.perbandingan', [
            'negara' => $negara,
            'negaraA' => null,
            'negaraB' => null,
        ]);
    }

    /**
     * Membandingkan dua negara
     */
    public function bandingkan(Request $request)
    {
        $request->validate([
            'negara_a_id' => 'required|exists:negara,id',
            'negara_b_id' => 'required|exists:negara,id|different:negara_a_id',
        ]);

        // Ambil data negara pertama
        $negaraA = Negara::with([
            'dataEkonomi',
            'cacheCuaca',
            'skorRisiko'
        ])->findOrFail($request->negara_a_id);

        // Ambil data negara kedua
        $negaraB = Negara::with([
            'dataEkonomi',
            'cacheCuaca',
            'skorRisiko'
        ])->findOrFail($request->negara_b_id);

        /*
        |--------------------------------------------------------------------------
        | Simpan Riwayat Perbandingan
        |--------------------------------------------------------------------------
        | Jika pasangan negara sudah pernah dibandingkan,
        | maka hanya update tanggal dibandingkan.
        */

        PerbandinganNegara::updateOrCreate(

            [
                'pengguna_id' => session('pengguna_id'),
                'negara_a_id' => $request->negara_a_id,
                'negara_b_id' => $request->negara_b_id,
            ],

            [
                'dibandingkan_pada' => now(),
            ]
        );

        $negara = Negara::orderBy('nama')->get();

        return view('dashboard.perbandingan', [
            'negara' => $negara,
            'negaraA' => $negaraA,
            'negaraB' => $negaraB,
        ]);
    }

    /**
     * Menampilkan riwayat perbandingan
     */
    public function riwayat()
    {
        $riwayat = PerbandinganNegara::with([
            'negaraA',
            'negaraB'
        ])
        ->orderByDesc('dibandingkan_pada')
        ->get();

        return view('dashboard.riwayat_perbandingan', compact('riwayat'));
    }
}