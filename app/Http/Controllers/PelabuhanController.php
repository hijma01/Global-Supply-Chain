<?php

namespace App\Http\Controllers;

use App\Models\Pelabuhan;
use Illuminate\Http\Request;

class PelabuhanController extends Controller
{
    public function index()
    {
        return response()->json(Pelabuhan::with('negara')->get());
    }

    public function show($id)
    {
        return response()->json(Pelabuhan::with('negara')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pelabuhan' => 'required|string',
            'negara_id' => 'nullable|exists:negara,id',
            'lintang' => 'required|numeric',
            'bujur' => 'required|numeric',
            'ukuran_pelabuhan' => 'nullable|string',
            'tipe_pelabuhan' => 'nullable|string',
        ]);
        $pelabuhan = Pelabuhan::create($data);
        return response()->json($pelabuhan, 201);
    }

    public function update(Request $request, $id)
    {
        $pelabuhan = Pelabuhan::findOrFail($id);
        $pelabuhan->update($request->all());
        return response()->json($pelabuhan);
    }

    public function destroy($id)
    {
        Pelabuhan::findOrFail($id)->delete();
        return response()->json(['message' => 'Pelabuhan berhasil dihapus']);
    }
}