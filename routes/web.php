<?php

use App\Http\Controllers\Auth\LoginGuruController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Portal Guru & Admin Belajar Bahasa Inggris
|--------------------------------------------------------------------------
*/

// Redirect root ke halaman login guru
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi Web Guru / Admin
Route::get('/login', [LoginGuruController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginGuruController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginGuruController::class, 'logout'])->name('logout');

// Area Terproteksi Web Guru / Admin
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('guru.dashboard');
    })->name('guru.dashboard');
});
