<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/negara', [DashboardController::class,'negara'])->name('dashboard.negara');

Route::get('/cuaca', [DashboardController::class,'cuaca'])->name('dashboard.cuaca');

Route::get('/nilai-tukar', [DashboardController::class,'nilaiTukar'])->name('dashboard.nilai-tukar');

Route::get('/berita', [DashboardController::class,'berita'])->name('berita.index');

Route::get('/pelabuhan', [DashboardController::class,'pelabuhan'])->name('pelabuhan.index');

Route::get('/risiko', [DashboardController::class,'risiko'])->name('risiko.index');

Route::get('/pantauan', [DashboardController::class,'pantauan'])->name('pantauan.index');

Route::get('/perbandingan', [DashboardController::class,'perbandingan'])->name('perbandingan.index');
