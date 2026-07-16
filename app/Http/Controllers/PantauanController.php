<?php

namespace App\Http\Controllers;

use App\Models\DaftarPantauan;
use Illuminate\Http\Request;

class PantauanController extends Controller
{
    public function index(Request $request)
    {
        $penggunaId = $request->query('pengguna_id');
        return response()->json(
            DaftarPantauan::with('negara')->where('pengguna_id', $penggunaId)->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'negara_id' => 'required|exists:negara,id',
        ]);
        $pantauan = DaftarPantauan::create($data);
        return response()->json($pantauan, 201);
    }

    public function destroy($id)
    {
        DaftarPantauan::findOrFail($id)->delete();
        return response()->json(['message' => 'Berhasil dihapus dari daftar pantauan']);
    }
}