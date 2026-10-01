<?php

use App\Http\Controllers\Auth\LoginGuruController;
use App\Http\Controllers\Guru\KategoriMateriController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\ModulListeningController;
use App\Models\Kosakata;
use App\Models\Siswa;
use Illuminate\Support\Facades\Route;

// Web Routes - Portal Guru & Admin Belajar Bahasa Inggris

// Redirect root ke halaman login guru
Route::get('/', function () {
    return redirect()->route('login');
});

// AUTENTIKASI WEB GURU & ADMINISTRATOR
Route::get('/login', [LoginGuruController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginGuruController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginGuruController::class, 'logout'])->name('logout');

// AREA TERPROTEKSI WEB GURU / ADMIN (WAJIB LOGIN)
Route::middleware(['auth'])->group(function () {
    // 0. Dashboard Utama
    Route::get('/dashboard', function () {
        $totalSiswa = Siswa::count();
        $totalKosakata = Kosakata::count();

        return view('guru.dashboard', compact('totalSiswa', 'totalKosakata'));
    })->name('guru.dashboard');

    // MODUL 1: MANAJEMEN MATERI PEMBELAJARAN (EKSPLISIT GET & POST)
    Route::get('/materi', [MateriController::class, 'index'])->name('guru.materi.index');
    Route::get('/materi/create', [MateriController::class, 'create'])->name('guru.materi.create');
    Route::post('/materi/store', [MateriController::class, 'store'])->name('guru.materi.store');
    Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('guru.materi.show');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('guru.materi.edit');
    Route::post('/materi/{materi}/update', [MateriController::class, 'update'])->name('guru.materi.update');
    Route::post('/materi/{materi}/destroy', [MateriController::class, 'destroy'])->name('guru.materi.destroy');

    // MODUL 1B: MANAJEMEN KATEGORI MATERI (EKSPLISIT GET & POST)
    Route::get('/kategori', [KategoriMateriController::class, 'index'])->name('guru.kategori.index');
    Route::get('/kategori/create', [KategoriMateriController::class, 'create'])->name('guru.kategori.create');
    Route::post('/kategori/store', [KategoriMateriController::class, 'store'])->name('guru.kategori.store');
    Route::get('/kategori/{kategori}', [KategoriMateriController::class, 'show'])->name('guru.kategori.show');
    Route::get('/kategori/{kategori}/edit', [KategoriMateriController::class, 'edit'])->name('guru.kategori.edit');
    Route::post('/kategori/{kategori}/update', [KategoriMateriController::class, 'update'])->name('guru.kategori.update');
    Route::post('/kategori/{kategori}/destroy', [KategoriMateriController::class, 'destroy'])->name('guru.kategori.destroy');

    // MODUL 2: MODUL LISTENING GURU (EKSPLISIT GET & POST)
    Route::get('/listening', [ModulListeningController::class, 'index'])->name('guru.listening.index');
    Route::get('/listening/create', [ModulListeningController::class, 'create'])->name('guru.listening.create');
    Route::post('/listening/store', [ModulListeningController::class, 'store'])->name('guru.listening.store');
    Route::get('/listening/{listening}', [ModulListeningController::class, 'show'])->name('guru.listening.show');
    Route::get('/listening/{listening}/edit', [ModulListeningController::class, 'edit'])->name('guru.listening.edit');
    Route::post('/listening/{listening}/update', [ModulListeningController::class, 'update'])->name('guru.listening.update');
    Route::post('/listening/{listening}/destroy', [ModulListeningController::class, 'destroy'])->name('guru.listening.destroy');

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
