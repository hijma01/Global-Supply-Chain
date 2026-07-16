<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    public function penggunaIndex()
    {
        return response()->json(Pengguna::all());
    }

    public function penggunaShow($id)
    {
        return response()->json(Pengguna::findOrFail($id));
    }

    public function penggunaStore(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:6',
            'peran' => 'nullable|in:admin,user',
        ]);
        $data['password'] = bcrypt($data['password']);
        $pengguna = Pengguna::create($data);
        return response()->json($pengguna, 201);
    }

    public function penggunaUpdate(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $data = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:pengguna,email,' . $id,
            'peran' => 'nullable|in:admin,user',
        ]);
        $pengguna->update($data);
        return response()->json($pengguna);
    }

    public function penggunaDestroy($id)
    {
        Pengguna::findOrFail($id)->delete();
        return response()->json(['message' => 'Pengguna berhasil dihapus']);
    }

    public function logIndex()
    {
        return response()->json(LogAktivitas::with('pengguna')->latest()->get());
    }

    public function logStore(Request $request)
    {
        $data = $request->validate([
            'pengguna_id' => 'nullable|exists:pengguna,id',
            'aksi' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);
        $log = LogAktivitas::create($data);
        return response()->json($log, 201);
    }
}