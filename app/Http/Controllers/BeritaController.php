<?php

namespace App\Http\Controllers;

use App\Models\CacheBerita;
use App\Models\KataPositif;
use App\Models\KataNegatif;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    
    public function index()
    {
        return response()->json(CacheBerita::latest('diterbitkan_pada')->get());
    }

    public function show($id)
    {
        return response()->json(CacheBerita::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'url' => 'required|string|unique:cache_berita,url',
            'sumber' => 'nullable|string',
            'kategori' => 'nullable|in:ekonomi,logistik,geopolitik,lainnya',
            'diterbitkan_pada' => 'nullable|date',
        ]);

        // Analisis sentimen sederhana berbasis lexicon (dictionary matching)
        $teks = strtolower($data['judul'] . ' ' . ($data['deskripsi'] ?? ''));
        $kataPositif = KataPositif::pluck('kata')->toArray();
        $kataNegatif = KataNegatif::pluck('kata')->toArray();
        $kataDalamTeks = str_word_count($teks, 1);

        $skorPositif = 0;
        $skorNegatif = 0;
        foreach ($kataDalamTeks as $kata) {
            if (in_array($kata, $kataPositif)) $skorPositif++;
            if (in_array($kata, $kataNegatif)) $skorNegatif++;
        }

        $label = 'netral';
        if ($skorPositif > $skorNegatif) $label = 'positif';
        if ($skorNegatif > $skorPositif) $label = 'negatif';

        $data['positive_score'] = $skorPositif;
        $data['negative_score'] = $skorNegatif;
        $data['sentiment_label'] = $label;

        $berita = CacheBerita::create($data);
        return response()->json($berita, 201);
    }

    public function destroy($id)
    {
        CacheBerita::findOrFail($id)->delete();
        return response()->json(['message' => 'Berita berhasil dihapus']);
    }

    public function kataPositifIndex()
    {
        return response()->json(KataPositif::all());
    }

    public function kataPositifStore(Request $request)
    {
        $data = $request->validate([
            'kata' => 'required|string|unique:kata_positif,kata',
        ]);
        $kata = KataPositif::create($data);
        return response()->json($kata, 201);
    }

    public function kataPositifDestroy($id)
    {
        KataPositif::findOrFail($id)->delete();
        return response()->json(['message' => 'Kata positif berhasil dihapus']);
    }

    public function kataNegatifIndex()
    {
        return response()->json(KataNegatif::all());
    }

    public function kataNegatifStore(Request $request)
    {
        $data = $request->validate([
            'kata' => 'required|string|unique:kata_negatif,kata',
        ]);
        $kata = KataNegatif::create($data);
        return response()->json($kata, 201);
    }

    public function kataNegatifDestroy($id)
    {
        KataNegatif::findOrFail($id)->delete();
        return response()->json(['message' => 'Kata negatif berhasil dihapus']);
    }
}