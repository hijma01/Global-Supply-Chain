<?php

namespace App\Http\Controllers;

use App\Models\SkorRisiko;
use App\Models\BobotSkorRisiko;
use App\Models\CacheCuaca;
use App\Models\DataEkonomiNegara;
use Illuminate\Http\Request;

class RisikoController extends Controller
{
    
    public function bobotIndex()
    {
        return response()->json(BobotSkorRisiko::first());
    }

    public function bobotUpdate(Request $request)
    {
        $bobot = BobotSkorRisiko::first();
        $data = $request->validate([
            'bobot_cuaca' => 'nullable|numeric',
            'bobot_inflasi' => 'nullable|numeric',
            'bobot_berita' => 'nullable|numeric',
            'bobot_kurs' => 'nullable|numeric',
        ]);
        $bobot->update($data);
        return response()->json($bobot);
    }

    public function index()
    {
        return response()->json(SkorRisiko::with('negara')->latest('dihitung_pada')->get());
    }

    public function dashboard()
    {
        $risiko = SkorRisiko::with('negara')
            ->latest('dihitung_pada')
            ->get();

        return view('dashboard.risiko', compact('risiko'));
    }

    public function show($negaraId)
    {
        $skor = SkorRisiko::with('negara')
            ->where('negara_id', $negaraId)
            ->latest('dihitung_pada')
            ->first();
        return response()->json($skor);
    }

    public function hitung(Request $request)
    {
        $data = $request->validate([
            'negara_id' => 'required|exists:negara,id',
        ]);

        $negaraId = $data['negara_id'];
        $bobot = BobotSkorRisiko::first();
        $cuaca = CacheCuaca::where('negara_id', $negaraId)->latest('dicatat_pada')->first();
        $ekonomi = DataEkonomiNegara::where('negara_id', $negaraId)->latest('tahun')->first();

        $skorCuaca = $cuaca ? min($cuaca->curah_hujan ?? 0, 100) : 0;
        $skorInflasi = $ekonomi ? min(($ekonomi->tingkat_inflasi ?? 0) * 2, 100) : 0;
        $skorKurs = 20; 
        $skorBerita = 30; 

        $skorTotal =
            ($skorCuaca * ($bobot->bobot_cuaca / 100)) +
            ($skorInflasi * ($bobot->bobot_inflasi / 100)) +
            ($skorBerita * ($bobot->bobot_berita / 100)) +
            ($skorKurs * ($bobot->bobot_kurs / 100));

        $tingkatRisiko = 'rendah';
        if ($skorTotal >= 60) $tingkatRisiko = 'tinggi';
        elseif ($skorTotal >= 30) $tingkatRisiko = 'sedang';

        $skorRisiko = SkorRisiko::create([
            'negara_id' => $negaraId,
            'skor_cuaca' => $skorCuaca,
            'skor_inflasi' => $skorInflasi,
            'skor_kurs' => $skorKurs,
            'skor_sentimen_berita' => $skorBerita,
            'skor_total' => round($skorTotal, 2),
            'tingkat_risiko' => $tingkatRisiko,
            'dihitung_pada' => now(),
        ]);

        return response()->json($skorRisiko, 201);
    }
}