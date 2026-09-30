<?php

use App\Http\Controllers\Auth\LoginGuruController;
use App\Models\Kosakata;
use App\Models\Siswa;
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
        $totalSiswa = Siswa::count();
        $totalKosakata = Kosakata::count();

        return view('guru.dashboard', compact('totalSiswa', 'totalKosakata'));
    })->name('guru.dashboard');

    // Manajemen Materi Guru
    Route::get('/materi', function () {
        return view('guru.manajemen_materi.index');
    })->name('guru.materi.index');

    // Modul Listening Guru
    Route::get('/listening', function () {
        return view('guru.modul_listening.index');
    })->name('guru.listening.index');

    // Bank Soal & Kuis Guru
    Route::get('/quiz', function () {
        return view('guru.bank_soal_kuis.index');
    })->name('guru.quiz.index');

    // Bank Kosakata Guru
    Route::get('/kosakata', function () {
        $daftarKosakata = Kosakata::latest()->get();

        return view('guru.bank_kosakata.index', compact('daftarKosakata'));
    })->name('guru.kosakata.index');

    // Manajemen Siswa & Kelas Guru
    Route::get('/siswa', function () {
        $daftarSiswa = Siswa::latest()->get();

        return view('guru.manajemen_siswa_kelas.index', compact('daftarSiswa'));
    })->name('guru.siswa.index');

    // Mini Games & Leaderboard Guru
    Route::get('/games', function () {
        $daftarSiswa = Siswa::latest()->get();

        return view('guru.mini_game_leaderboard.index', compact('daftarSiswa'));
    })->name('guru.game.index');

    // Laporan & Analitik Guru
    Route::get('/laporan', function () {
        $daftarSiswa = Siswa::latest()->get();

        return view('guru.laporan_analitik.index', compact('daftarSiswa'));
    })->name('guru.laporan.index');

    // Pengaturan Sistem Guru
    Route::get('/pengaturan', function () {
        return view('guru.pengaturan_sistem.index');
    })->name('guru.pengaturan.index');
});
