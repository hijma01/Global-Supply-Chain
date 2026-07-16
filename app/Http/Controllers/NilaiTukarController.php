<?php

namespace App\Http\Controllers;

use App\Models\NilaiTukar;
use App\Models\RiwayatNilaiTukar;
use App\Services\FrankfurterService;
use Illuminate\Http\Request;

class NilaiTukarController extends Controller
{
    
    public function index()
    {
        return response()->json(NilaiTukar::latest('dicatat_pada')->get());
    }

    public function show($id)
    {
        return response()->json(NilaiTukar::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mata_uang_dasar' => 'nullable|string|max:10',
            'mata_uang_tujuan' => 'required|string|max:10',
            'nilai_kurs' => 'required|numeric',
            'dicatat_pada' => 'required|date',
        ]);
        $nilaiTukar = NilaiTukar::create($data);
        return response()->json($nilaiTukar, 201);
    }

    public function update(Request $request, $id)
    {
        $nilaiTukar = NilaiTukar::findOrFail($id);
        $nilaiTukar->update($request->all());
        return response()->json($nilaiTukar);
    }

    public function destroy($id)
    {
        NilaiTukar::findOrFail($id)->delete();
        return response()->json(['message' => 'Nilai tukar berhasil dihapus']);
    }

    public function riwayatIndex()
    {
        return response()->json(RiwayatNilaiTukar::orderBy('tanggal')->get());
    }

    public function riwayatStore(Request $request)
    {
        $data = $request->validate([
            'kode_mata_uang' => 'required|string|max:10',
            'nilai_kurs' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);
        $riwayat = RiwayatNilaiTukar::create($data);
        return response()->json($riwayat, 201);
    }

    public function realtime($id, FrankfurterService $service)
    {
        $negara = Negara::findOrFail($id);

        if (!$negara->kode_mata_uang) {

            return response()->json([
                'success' => false,
                'message' => 'Kode mata uang tidak tersedia.'
            ],404);

        }

        $kurs = $service->ambilKurs(
            'USD',
            $negara->kode_mata_uang
        );

        if (!$kurs) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kurs.'
            ],500);

        }

        return response()->json([

            'success' => true,

            'negara' => $negara->nama,

            'kurs' => $kurs

        ]);
    }

    public function riwayatDestroy($id)
    {
        RiwayatNilaiTukar::findOrFail($id)->delete();
        return response()->json(['message' => 'Riwayat nilai tukar berhasil dihapus']);
    }
}