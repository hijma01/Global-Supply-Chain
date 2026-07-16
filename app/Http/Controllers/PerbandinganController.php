<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\PerbandinganNegara;
use Illuminate\Http\Request;

class PerbandinganController extends Controller
{
    public function bandingkan(Request $request)
    {
        $data = $request->validate([
            'negara_a_id' => 'required|exists:negara,id',
            'negara_b_id' => 'required|exists:negara,id',
            'pengguna_id' => 'nullable|exists:pengguna,id',
        ]);

        $negaraA = Negara::with(['dataEkonomi', 'cacheCuaca', 'skorRisiko'])->findOrFail($data['negara_a_id']);
        $negaraB = Negara::with(['dataEkonomi', 'cacheCuaca', 'skorRisiko'])->findOrFail($data['negara_b_id']);

        PerbandinganNegara::create([
            'pengguna_id' => $data['pengguna_id'] ?? null,
            'negara_a_id' => $data['negara_a_id'],
            'negara_b_id' => $data['negara_b_id'],
            'dibandingkan_pada' => now(),
        ]);

        return response()->json([
            'negara_a' => $negaraA,
            'negara_b' => $negaraB,
        ]);
    }

    public function riwayat()
    {
        return response()->json(
            PerbandinganNegara::with(['negaraA', 'negaraB'])->latest()->get()
        );
    }
}