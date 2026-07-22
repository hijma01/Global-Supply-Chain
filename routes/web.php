<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerbandinganController;
use App\Http\Controllers\PantauanController;
use App\Http\Controllers\RisikoController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PelabuhanController;
use App\Http\Controllers\BeritaController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');



Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->name('admin.dashboard');

Route::get('/dashboard', [DashboardController::class, 'negara'])->name('dashboard');

Route::get('/negara', [DashboardController::class,'negara'])->name('dashboard.negara');

Route::get('/cuaca', [DashboardController::class,'cuaca'])->name('dashboard.cuaca');

Route::get('/nilai-tukar', [DashboardController::class,'nilaiTukar'])->name('dashboard.nilai-tukar');

Route::get('/berita', [DashboardController::class,'berita'])->name('berita.index');

Route::get('/pelabuhan', [DashboardController::class,'pelabuhan'])->name('pelabuhan.index');

Route::get('/risiko', [RisikoController::class, 'dashboard'])
    ->name('dashboard.risiko');

Route::get('/pantauan', [PantauanController::class, 'index'])
    ->name('pantauan.index');

Route::post('/pantauan', [PantauanController::class, 'store'])
    ->name('pantauan.store');

Route::delete('/pantauan/{id}', [PantauanController::class, 'destroy'])
    ->name('pantauan.destroy');

Route::get('/perbandingan', [PerbandinganController::class, 'index'])
    ->name('perbandingan.index');

Route::post('/perbandingan', [PerbandinganController::class, 'bandingkan'])
    ->name('perbandingan.bandingkan');

Route::get('/perbandingan/riwayat', [PerbandinganController::class, 'riwayat'])
    ->name('perbandingan.riwayat');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'admin'])
        ->name('dashboard');

    Route::resource('pengguna', PenggunaController::class);
    Route::get('/pelabuhan', [PelabuhanController::class, 'adminIndex'])
        ->name('pelabuhan.index');
    Route::get('/pelabuhan/create', [PelabuhanController::class, 'adminCreate'])
        ->name('pelabuhan.create');
     Route::post('/pelabuhan', [PelabuhanController::class, 'adminStore'])
        ->name('pelabuhan.store');

    Route::get('/pelabuhan/{id}/edit', [PelabuhanController::class, 'adminEdit'])
        ->name('pelabuhan.edit');

    Route::put('/pelabuhan/{id}', [PelabuhanController::class, 'adminUpdate'])
        ->name('pelabuhan.update');

    Route::delete('/pelabuhan/{id}', [PelabuhanController::class, 'adminDestroy'])
        ->name('pelabuhan.destroy');
    Route::get('/berita',[BeritaController::class,'adminIndex'])->name('berita.index');
    Route::get('/berita/create',[BeritaController::class,'adminCreate'])->name('berita.create');
    Route::post('/berita',[BeritaController::class,'store'])->name('berita.store');
    Route::get('/berita/{id}/edit',[BeritaController::class,'adminEdit'])->name('berita.edit');
    Route::put('/berita/{id}',[BeritaController::class,'adminUpdate'])->name('berita.update');
    Route::delete('/berita/{id}',[BeritaController::class, 'adminDestroy'])->name('berita.destroy');
    
});
