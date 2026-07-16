<?php

namespace App\Http\Controllers;

use App\Models\Negara;
use App\Models\DataEkonomiNegara;
use App\Services\WorldBankService;
use Illuminate\Http\Request;

class NegaraController extends Controller
{
    
    public function index()
    {
        return response()->json(Negara::all());
    }

    public function show($id)
    {
        $negara = Negara::with([
            'dataEkonomi' => function ($query) {
                $query->latest('tahun');
            },
            'cacheCuaca' => function ($query) {
                $query->latest('dicatat_pada');
            },
            'skorRisiko' => function ($query) {
                $query->latest('dihitung_pada');
            },
            'pelabuhan'
        ])->findOrFail($id);

        return response()->json($negara);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'kode_negara' => 'required|string|size:2|unique:negara,kode_negara',
            'kode_negara_3' => 'nullable|string|size:3',
            'kode_mata_uang' => 'nullable|string|max:10',
            'nama_mata_uang' => 'nullable|string',
            'wilayah' => 'nullable|string',
            'sub_wilayah' => 'nullable|string',
            'ibu_kota' => 'nullable|string',
            'populasi' => 'nullable|integer',
            'url_bendera' => 'nullable|string',
            'lintang' => 'nullable|numeric',
            'bujur' => 'nullable|numeric',
        ]);
        $negara = Negara::create($data);
        return response()->json($negara, 201);
    }

    public function update(Request $request, $id)
    {
        $negara = Negara::findOrFail($id);
        $negara->update($request->all());
        return response()->json($negara);
    }

    public function destroy($id)
    {
        Negara::findOrFail($id)->delete();
        return response()->json(['message' => 'Negara berhasil dihapus']);
    }

    public function dataEkonomiIndex()
    {
        return response()->json(DataEkonomiNegara::with('negara')->get());
    }

    public function dataEkonomiShow($id)
    {
        return response()->json(DataEkonomiNegara::with('negara')->findOrFail($id));
    }

    public function dataEkonomiStore(Request $request)
    {
        $data = $request->validate([
            'negara_id' => 'required|exists:negara,id',
            'pdb' => 'nullable|numeric',
            'tingkat_inflasi' => 'nullable|numeric',
            'nilai_ekspor' => 'nullable|numeric',
            'nilai_impor' => 'nullable|numeric',
            'tahun' => 'required|integer',
        ]);
        $dataEkonomi = DataEkonomiNegara::create($data);
        return response()->json($dataEkonomi, 201);
    }

    public function dataEkonomiUpdate(Request $request, $id)
    {
        $dataEkonomi = DataEkonomiNegara::findOrFail($id);
        $dataEkonomi->update($request->all());
        return response()->json($dataEkonomi);
    }

    public function dataEkonomiDestroy($id)
    {
        DataEkonomiNegara::findOrFail($id)->delete();
        return response()->json(['message' => 'Data ekonomi berhasil dihapus']);
    }

    public function dataEkonomiRealtime($id, WorldBankService $service)
    {
        $negara = Negara::findOrFail($id);

        $dataEkonomi = $service->ambilDataEkonomi($negara->kode_negara);

        if (!$dataEkonomi) {
            return response()->json([
                'success' => false,
                'message' => 'Data ekonomi tidak tersedia.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'negara' => $negara->nama,
            'data_ekonomi' => $dataEkonomi
        ]);
    }

    public function dataPeta($id)
    {
        $negara = Negara::with(['pelabuhan', 'cacheCuaca' => function ($query) {
            $query->latest('dicatat_pada')->limit(1);
        }])->findOrFail($id);

        return response()->json($negara);
    }
}