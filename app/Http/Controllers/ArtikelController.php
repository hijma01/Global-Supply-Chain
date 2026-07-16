<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        return response()->json(Artikel::with('pengguna')->latest('diterbitkan_pada')->get());
    }

    public function show($id)
    {
        return response()->json(Artikel::with('pengguna')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'judul' => 'required|string',
            'konten' => 'required|string',
            'kategori' => 'nullable|string',
            'diterbitkan_pada' => 'nullable|date',
        ]);
        $artikel = Artikel::create($data);
        return response()->json($artikel, 201);
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->update($request->all());
        return response()->json($artikel);
    }

    public function destroy($id)
    {
        Artikel::findOrFail($id)->delete();
        return response()->json(['message' => 'Artikel berhasil dihapus']);
    }
}