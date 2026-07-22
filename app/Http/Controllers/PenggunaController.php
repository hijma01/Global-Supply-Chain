<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     */
    public function index()
    {
        $pengguna = Pengguna::latest()->paginate(10);

        return view('admin.pengguna.index', compact('pengguna'));
    }

    /**
     * Menampilkan form tambah pengguna.
     */
    public function create()
    {
        return view('admin.pengguna.create');
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:pengguna,email',
            'password' => 'required|min:6',
            'peran'    => 'required|in:admin,user',
        ]);

        Pengguna::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'peran'    => $request->peran,
        ]);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        return view('admin.pengguna.edit', compact('pengguna'));
    }

    /**
     * Mengupdate data pengguna.
     */
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id,
            'peran' => 'required|in:admin,user',
            'password' => 'nullable|min:6',
        ]);

        $pengguna->nama = $request->nama;
        $pengguna->email = $request->email;
        $pengguna->peran = $request->peran;

        if ($request->filled('password')) {
            $pengguna->password = Hash::make($request->password);
        }

        $pengguna->save();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }
    /**
     * Menghapus pengguna.
     */
    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        $pengguna->delete();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}