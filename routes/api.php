<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NegaraController;
use App\Http\Controllers\CuacaController;
use App\Http\Controllers\NilaiTukarController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PelabuhanController;
use App\Http\Controllers\RisikoController;
use App\Http\Controllers\PantauanController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\PerbandinganController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController; 

Route::post('/register', [AuthController::class, 'register']);        
Route::post('/login', [AuthController::class, 'login']);                
                                                                         
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profil', [AuthController::class, 'profil']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/negara', [NegaraController::class, 'index']);
Route::get('/negara/{id}', [NegaraController::class, 'show']);
Route::post('/negara', [NegaraController::class, 'store']);
Route::put('/negara/{id}', [NegaraController::class, 'update']);
Route::delete('/negara/{id}', [NegaraController::class, 'destroy']);

Route::get('/data-ekonomi', [NegaraController::class, 'dataEkonomiIndex']);
Route::get('/data-ekonomi/realtime/{id}', [NegaraController::class, 'dataEkonomiRealtime']);
Route::get('/data-ekonomi/{id}', [NegaraController::class, 'dataEkonomiShow']);
Route::post('/data-ekonomi', [NegaraController::class, 'dataEkonomiStore']);
Route::put('/data-ekonomi/{id}', [NegaraController::class, 'dataEkonomiUpdate']);
Route::delete('/data-ekonomi/{id}', [NegaraController::class, 'dataEkonomiDestroy']);


Route::get('/cuaca', [CuacaController::class, 'index']);
Route::get('/cuaca/realtime/{id}', [CuacaController::class, 'realtime']);
Route::get('/cuaca/{id}', [CuacaController::class, 'show']);
Route::post('/cuaca', [CuacaController::class, 'store']);
Route::put('/cuaca/{id}', [CuacaController::class, 'update']);
Route::delete('/cuaca/{id}', [CuacaController::class, 'destroy']);



Route::get('/kurs', [NilaiTukarController::class, 'index']);
Route::get('/nilai-tukar/realtime/{id}', [NilaiTukarController::class, 'realtime']);
Route::get('/kurs/{id}', [NilaiTukarController::class, 'show']);
Route::post('/kurs', [NilaiTukarController::class, 'store']);
Route::put('/kurs/{id}', [NilaiTukarController::class, 'update']);
Route::delete('/kurs/{id}', [NilaiTukarController::class, 'destroy']);

Route::get('/kurs-riwayat', [NilaiTukarController::class, 'riwayatIndex']);
Route::post('/kurs-riwayat', [NilaiTukarController::class, 'riwayatStore']);
Route::delete('/kurs-riwayat/{id}', [NilaiTukarController::class, 'riwayatDestroy']);


Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{id}', [BeritaController::class, 'show']);
Route::post('/berita', [BeritaController::class, 'store']);
Route::delete('/berita/{id}', [BeritaController::class, 'destroy']);

Route::get('/kata-positif', [BeritaController::class, 'kataPositifIndex']);
Route::post('/kata-positif', [BeritaController::class, 'kataPositifStore']);
Route::delete('/kata-positif/{id}', [BeritaController::class, 'kataPositifDestroy']);

Route::get('/kata-negatif', [BeritaController::class, 'kataNegatifIndex']);
Route::post('/kata-negatif', [BeritaController::class, 'kataNegatifStore']);
Route::delete('/kata-negatif/{id}', [BeritaController::class, 'kataNegatifDestroy']);


Route::get('/pelabuhan', [PelabuhanController::class, 'index']);
Route::get('/pelabuhan/{id}', [PelabuhanController::class, 'show']);
Route::post('/pelabuhan', [PelabuhanController::class, 'store']);
Route::put('/pelabuhan/{id}', [PelabuhanController::class, 'update']);
Route::delete('/pelabuhan/{id}', [PelabuhanController::class, 'destroy']);


Route::get('/risiko', [RisikoController::class, 'index']);
Route::get('/risiko/{negaraId}', [RisikoController::class, 'show']);
Route::post('/risiko/hitung', [RisikoController::class, 'hitung']);

Route::get('/bobot-risiko', [RisikoController::class, 'bobotIndex']);
Route::put('/bobot-risiko', [RisikoController::class, 'bobotUpdate']);


Route::get('/pantauan', [PantauanController::class, 'index']);
Route::post('/pantauan', [PantauanController::class, 'store']);
Route::delete('/pantauan/{id}', [PantauanController::class, 'destroy']);


Route::get('/artikel', [ArtikelController::class, 'index']);
Route::get('/artikel/{id}', [ArtikelController::class, 'show']);
Route::post('/artikel', [ArtikelController::class, 'store']);
Route::put('/artikel/{id}', [ArtikelController::class, 'update']);
Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy']);


Route::post('/bandingkan', [PerbandinganController::class, 'bandingkan']);
Route::get('/bandingkan/riwayat', [PerbandinganController::class, 'riwayat']);


Route::get('/admin/pengguna', [AdminController::class, 'penggunaIndex']);
Route::get('/admin/pengguna/{id}', [AdminController::class, 'penggunaShow']);
Route::post('/admin/pengguna', [AdminController::class, 'penggunaStore']);
Route::put('/admin/pengguna/{id}', [AdminController::class, 'penggunaUpdate']);
Route::delete('/admin/pengguna/{id}', [AdminController::class, 'penggunaDestroy']);

Route::get('/admin/log-aktivitas', [AdminController::class, 'logIndex']);
Route::post('/admin/log-aktivitas', [AdminController::class, 'logStore']);