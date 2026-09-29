<?php

use App\Http\Controllers\Api\AuthGuruController;
use App\Http\Controllers\Api\AuthSiswaController;
use App\Http\Controllers\Api\KosakataController;
use App\Http\Controllers\Api\LoginSiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Belajar Bahasa Inggris
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. RUTE PUBLIK (Bisa Diakses Siapa Saja / Siswa)
// =========================================================================

// Autentikasi Guru (Login)
Route::post('/guru/login', [AuthGuruController::class, 'login']);

// Autentikasi Siswa
Route::post('/siswa/register', [AuthSiswaController::class, 'register']);
Route::post('/siswa/login', [LoginSiswaController::class, 'login']);
Route::post('/siswa/lupa-sandi', [LoginSiswaController::class, 'lupaSandi']);
Route::post('/siswa/reset-sandi', [LoginSiswaController::class, 'resetSandi']);

// Masuk Siswa & Cek Profil Siswa Sendiri (Kompatibilitas)
Route::post('/siswa/masuk', [LoginSiswaController::class, 'masuk']);
Route::get('/siswa/profil/{nis}', [AuthSiswaController::class, 'profil']);

// =========================================================================
// 2. RUTE TERPROTEKSI SISWA (Wajib Token Siswa)
// =========================================================================
Route::middleware('auth.siswa')->group(function () {
    Route::get('/siswa/me', [LoginSiswaController::class, 'me']);
    Route::post('/siswa/logout', [LoginSiswaController::class, 'logout']);
});

// Data Kosakata (Hanya Baca: Siswa & Guru bisa melihat)
Route::get('/kosakata', [KosakataController::class, 'index']);
Route::get('/kosakata/{id}', [KosakataController::class, 'show']);

// Helper: Data Master Dropdown Kelas untuk Flutter (Tingkat, Jurusan, Nomor)
Route::get('/kelas', function () {
    return response()->json([
        'status' => 'success',
        'tingkat' => ['10', '11', '12'],
        'jurusan' => ['TSM', 'RPL', 'BD', 'DKV', 'SA', 'MPLB', 'TKKR'],
        'nomor' => ['1', '2', '3'],
    ]);
});

// =========================================================================
// 2. RUTE TERPROTEKSI (Wajib Token Guru / Khusus Guru)
// =========================================================================
Route::middleware('auth.guru')->group(function () {
    // Sesi Guru
    Route::post('/guru/logout', [AuthGuruController::class, 'logout']);
    Route::get('/guru/me', [AuthGuruController::class, 'me']);

    // Kelola Data Siswa (Hanya Guru yang boleh melihat rekap semua siswa)
    Route::get('/siswa', [AuthSiswaController::class, 'index']);

    // Kelola Kosakata (Hanya Guru yang boleh Tambah, Ubah, Hapus)
    Route::post('/kosakata', [KosakataController::class, 'store']);
    Route::put('/kosakata/{id}', [KosakataController::class, 'update']);
    Route::delete('/kosakata/{id}', [KosakataController::class, 'destroy']);
});
