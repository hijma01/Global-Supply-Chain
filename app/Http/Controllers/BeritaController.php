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
        return response()->json(
            CacheBerita::latest('diterbitkan_pada')->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            CacheBerita::findOrFail($id)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'url' => 'required|string|unique:cache_berita,url',
            'sumber' => 'nullable|string',
            'kategori' => 'nullable|in:ekonomi,logistik,perdagangan,pelayaran,geopolitik,lainnya',
            'diterbitkan_pada' => 'nullable|date',
        ]);

        // Gabungkan judul dan deskripsi
        $teks = strtolower(
            $data['judul'] . ' ' . ($data['deskripsi'] ?? '')
        );

        // Ambil daftar kata dari database
        $kataPositif = KataPositif::pluck('kata')->toArray();
        $kataNegatif = KataNegatif::pluck('kata')->toArray();

        $kataDalamTeks = str_word_count($teks, 1);

        $skorPositif = 0;
        $skorNegatif = 0;

        foreach ($kataDalamTeks as $kata) {

            if (in_array($kata, $kataPositif)) {
                $skorPositif++;
            }

            if (in_array($kata, $kataNegatif)) {
                $skorNegatif++;
            }
        }

        $label = 'netral';

        if ($skorPositif > $skorNegatif) {
            $label = 'positif';
        } elseif ($skorNegatif > $skorPositif) {
            $label = 'negatif';
        }

        // Simpan hasil analisis sentimen
        $data['skor_positif'] = $skorPositif;
        $data['skor_negatif'] = $skorNegatif;
        $data['label_sentimen'] = $label;

        $berita = CacheBerita::create($data);

        return response()->json($berita, 201);
    }

    public function destroy($id)
    {
        CacheBerita::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Berita berhasil dihapus'
        ]);
    }

    public function kataPositifIndex()
    {
        return response()->json(
            KataPositif::all()
        );
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

        return response()->json([
            'message' => 'Kata positif berhasil dihapus'
        ]);
    }

    public function kataNegatifIndex()
    {
        return response()->json(
            KataNegatif::all()
        );
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

        return response()->json([
            'message' => 'Kata negatif berhasil dihapus'
        ]);
    }

    public function adminIndex()
    {
        $berita = CacheBerita::latest('diterbitkan_pada')->get();

        return view('admin.berita.index', compact('berita'));
    }
    public function adminCreate()
    {
        return view('admin.berita.create');
    }

    public function adminEdit($id)
    {
        $berita = CacheBerita::findOrFail($id);

        return view('admin.berita.edit', compact('berita'));
    }
    public function adminUpdate(Request $request, $id)
    {
        $berita = CacheBerita::findOrFail($id);


        $data = $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'url' => 'required|string',
            'sumber' => 'nullable|string',
            'kategori' => 'nullable|in:ekonomi,logistik,perdagangan,pelayaran,geopolitik,lainnya',
            'diterbitkan_pada' => 'nullable|date',
        ]);


        // Analisis ulang sentimen
        $teks = strtolower(
            $data['judul'].' '.($data['deskripsi'] ?? '')
        );


        $kataPositif = KataPositif::pluck('kata')->toArray();
        $kataNegatif = KataNegatif::pluck('kata')->toArray();


        $kataDalamTeks = str_word_count($teks,1);


        $skorPositif = 0;
        $skorNegatif = 0;


        foreach($kataDalamTeks as $kata){

            if(in_array($kata,$kataPositif)){
                $skorPositif++;
            }


            if(in_array($kata,$kataNegatif)){
                $skorNegatif++;
            }

        }


        $label = 'netral';


        if($skorPositif > $skorNegatif){

            $label='positif';

        }elseif($skorNegatif > $skorPositif){

            $label='negatif';

        }


        $data['skor_positif']=$skorPositif;
        $data['skor_negatif']=$skorNegatif;
        $data['label_sentimen']=$label;


        $berita->update($data);


        return redirect()
            ->route('admin.berita.index')
            ->with('success','Berita berhasil diperbarui');

    }
    public function adminDestroy($id)
    {
        $berita = CacheBerita::findOrFail($id);

        $berita->delete();

        return redirect()
            ->route('admin.berita.index')
            ->with('success','Berita berhasil dihapus');
    }
}