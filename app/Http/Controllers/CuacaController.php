<?php

namespace App\Http\Controllers;

use App\Models\CacheCuaca;
use App\Models\Negara;
use App\Services\OpenMeteoService;
use Illuminate\Http\Request;

class CuacaController extends Controller
{
    public function index()
    {
        return response()->json(CacheCuaca::with('negara')->latest('dicatat_pada')->get());
    }

    public function show($id)
    {
        return response()->json(CacheCuaca::with('negara')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'negara_id' => 'required|exists:negara,id',
            'suhu' => 'nullable|numeric',
            'curah_hujan' => 'nullable|numeric',
            'kecepatan_angin' => 'nullable|numeric',
            'tingkat_risiko_badai' => 'nullable|in:rendah,sedang,tinggi',
            'dicatat_pada' => 'required|date',
        ]);
        $cuaca = CacheCuaca::create($data);
        return response()->json($cuaca, 201);
    }

    public function update(Request $request, $id)
    {
        $cuaca = CacheCuaca::findOrFail($id);
        $cuaca->update($request->all());
        return response()->json($cuaca);
    }

    public function destroy($id)
    {
        CacheCuaca::findOrFail($id)->delete();
        return response()->json(['message' => 'Data cuaca berhasil dihapus']);
    }

    public function realtime($id, OpenMeteoService $service)
    {
        $negara = Negara::findOrFail($id);

        if (!$negara->lintang || !$negara->bujur) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat negara tidak tersedia.'
            ], 404);
        }

        $cuaca = $service->ambilCuaca(
            (float) $negara->lintang,
            (float) $negara->bujur
        );

        if (!$cuaca) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data cuaca.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'negara' => $negara->nama,
            'cuaca' => $cuaca
        ]);
    }
}