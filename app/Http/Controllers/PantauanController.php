<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarPantauan;
use App\Models\Negara;

class PantauanController extends Controller
{
    /**
     * Menampilkan halaman Daftar Pantauan
     */
    public function index(Request $request)
    {
        // ID pengguna yang sedang login
        $penggunaId = 1;
        // Semua negara untuk dropdown
        $negara = Negara::orderBy('nama')->get();
        
        // Daftar pantauan pengguna
        $pantauan = DaftarPantauan::with([
            'negara.ekonomiTerbaru',
            'negara.cuacaTerbaru',
            'negara.risikoTerbaru'
        ])
        ->where('pengguna_id', $penggunaId)
        ->latest()
        ->get();

        // Statistik
        $totalFavorit = $pantauan->count();

        $totalAman = 0;
        $totalWaspada = 0;
        $totalRisiko = 0;
        foreach ($pantauan as $item) {

            if (!$item->negara || !$item->negara->risikoTerbaru) {
                continue;
            }

            $risiko = strtolower($item->negara->risikoTerbaru->tingkat_risiko);

            if ($risiko == 'rendah') {
                $totalAman++;
            } elseif ($risiko == 'sedang') {
                $totalWaspada++;
            } elseif ($risiko == 'tinggi') {
                $totalRisiko++;
            }
        }

        return view('dashboard.pantauan', compact(
            'pantauan',
            'negara',
            'totalFavorit',
            'totalAman',
            'totalWaspada',
            'totalRisiko'
        ));
    }

    /**
     * Menambahkan negara ke daftar pantauan
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengguna_id' => 'required|exists:pengguna,id',
            'negara_id'   => 'required|exists:negara,id',
        ]);

        DaftarPantauan::firstOrCreate([
            'pengguna_id' => $request->pengguna_id,
            'negara_id'   => $request->negara_id,
        ]);

        return redirect()
            ->route('pantauan.index')
            ->with('success', 'Negara berhasil ditambahkan ke daftar pantauan.');
    }

    /**
     * Menghapus negara dari daftar pantauan
     */
    public function destroy($id)
    {
        $pantauan = DaftarPantauan::findOrFail($id);

        $pantauan->delete();

        return redirect()->route('pantauan.index')
            ->with('success', 'Negara berhasil dihapus dari daftar pantauan.');
    }
}