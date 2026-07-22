<?php

namespace App\Http\Controllers;

use App\Models\Pelabuhan;
use App\Models\Negara;
use Illuminate\Http\Request;

class PelabuhanController extends Controller
{
    public function index()
    {
        $data = Pelabuhan::with('negara')->get();

        return response()->json([
            'success' => true,
            'total' => $data->count(),
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = Pelabuhan::with('negara')
            ->findOrFail($id);


        return response()->json([
            'success'=>true,
            'data'=>$data
        ]);
    }

    public function store(Request $request)
    {

        $data = $request->validate([

            'nama_pelabuhan' => 'required|string',

            'negara_id' => 
            'nullable|exists:negara,id',

            'lintang' => 
            'required|numeric',

            'bujur' => 
            'required|numeric',

            'ukuran_pelabuhan' => 
            'nullable|string',

            'tipe_pelabuhan' => 
            'nullable|string',

        ]);

        $pelabuhan = Pelabuhan::create($data);


        return response()->json([

            'success'=>true,

            'data'=>$pelabuhan

        ],201);

    }

    public function update(Request $request,$id)
    {

        $pelabuhan = Pelabuhan::findOrFail($id);


        $pelabuhan->update(
            $request->all()
        );


        return response()->json([

            'success'=>true,

            'data'=>$pelabuhan

        ]);

    }

    public function destroy($id)
    {

        Pelabuhan::findOrFail($id)
            ->delete();


        return response()->json([

            'success'=>true,

            'message'=>'Pelabuhan berhasil dihapus'

        ]);

    }

    public function search(Request $request)
    {

        $keyword = $request->keyword;


        $data = Pelabuhan::with('negara')

            ->where(
                'nama_pelabuhan',
                'LIKE',
                "%$keyword%"
            )

            ->orWhereHas(
                'negara',
                function($query) use($keyword){

                    $query->where(
                        'nama',
                        'LIKE',
                        "%$keyword%"
                    );

                }
            )


            ->get();



        return response()->json([

            'success'=>true,

            'total'=>$data->count(),

            'data'=>$data

        ]);

    }

    public function adminIndex()
    {
        $pelabuhan = Pelabuhan::with('negara')->paginate(10);

        return view('admin.pelabuhan.index', compact('pelabuhan'));
    }
    public function adminCreate()
    {
        $negara = Negara::orderBy('nama')->get();

        return view('admin.pelabuhan.create', compact('negara'));
    }

    public function adminEdit($id)
    {
        $pelabuhan = Pelabuhan::findOrFail($id);

        $negara = Negara::orderBy('nama')->get();

        return view('admin.pelabuhan.edit', compact('pelabuhan', 'negara'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_pelabuhan' => 'required|string|max:255',
            'negara_id' => 'nullable|exists:negara,id',
            'lintang' => 'required|numeric',
            'bujur' => 'required|numeric',
            'ukuran_pelabuhan' => 'nullable|string|max:255',
            'tipe_pelabuhan' => 'nullable|string|max:255',
        ]);

        $pelabuhan = Pelabuhan::findOrFail($id);

        $pelabuhan->update($request->only([
            'nama_pelabuhan',
            'negara_id',
            'lintang',
            'bujur',
            'ukuran_pelabuhan',
            'tipe_pelabuhan',
        ]));

        return redirect()
            ->route('admin.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil diperbarui.');
    }
    public function adminStore(Request $request)
    {
        $request->validate([
            'nama_pelabuhan' => 'required|string|max:255',
            'negara_id' => 'nullable|exists:negara,id',
            'lintang' => 'required|numeric',
            'bujur' => 'required|numeric',
            'ukuran_pelabuhan' => 'nullable|string|max:255',
            'tipe_pelabuhan' => 'nullable|string|max:255',
        ]);

        Pelabuhan::create([
            'nama_pelabuhan' => $request->nama_pelabuhan,
            'negara_id' => $request->negara_id,
            'lintang' => $request->lintang,
            'bujur' => $request->bujur,
            'ukuran_pelabuhan' => $request->ukuran_pelabuhan,
            'tipe_pelabuhan' => $request->tipe_pelabuhan,
        ]);

        return redirect()
            ->route('admin.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil ditambahkan.');
    }
    public function adminDestroy($id)
    {
        $pelabuhan = Pelabuhan::findOrFail($id);

        $pelabuhan->delete();

        return redirect()
            ->route('admin.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil dihapus.');
    }


}