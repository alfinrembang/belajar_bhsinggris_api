<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KosakataController;
use App\Http\Controllers\Api\AuthGuruController;
use App\Http\Controllers\Api\AuthSiswaController;

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

// Masuk Siswa & Cek Profil Siswa Sendiri
Route::post('/siswa/masuk', [AuthSiswaController::class, 'masuk']);
Route::get('/siswa/profil/{nis}', [AuthSiswaController::class, 'profil']);

// Data Kosakata (Hanya Baca: Siswa & Guru bisa melihat)
Route::get('/kosakata', [KosakataController::class, 'index']);
Route::get('/kosakata/{id}', [KosakataController::class, 'show']);

// Helper: Data Master Dropdown Kelas untuk Flutter (Tingkat, Jurusan, Nomor)
Route::get('/kelas', function () {
    return response()->json([
        'status'  => 'success',
        'tingkat' => ['10', '11', '12'],
        'jurusan' => ['TSM', 'RPL', 'BD', 'DKV', 'SA', 'MPLB', 'TKKR'],
        'nomor'   => ['1', '2', '3'],
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
