<?php

use App\Http\Controllers\Api\AuthSiswaController;
use App\Http\Controllers\Api\KategoriMateriApiController;
use App\Http\Controllers\Api\KosakataController;
use App\Http\Controllers\Api\LoginSiswaController;
use App\Http\Controllers\Api\MateriApiController;
use Illuminate\Support\Facades\Route;

// API Routes - Belajar Bahasa Inggris (Khusus Aplikasi Siswa / Flutter)

// 1. RUTE PUBLIK SISWA
// Autentikasi Siswa
Route::post('/siswa/register', [AuthSiswaController::class, 'register']);
Route::post('/siswa/login', [LoginSiswaController::class, 'login']);
Route::post('/siswa/lupa-sandi', [LoginSiswaController::class, 'lupaSandi']);
Route::post('/siswa/reset-sandi', [LoginSiswaController::class, 'resetSandi']);

// Masuk Siswa & Cek Profil Siswa Sendiri (Kompatibilitas)
Route::post('/siswa/masuk', [LoginSiswaController::class, 'masuk']);
Route::get('/siswa/profil/{nis}', [AuthSiswaController::class, 'profil']);

// 2. MODUL MATERI PEMBELAJARAN (FLUTTER SISWA)
// Mengambil daftar master kategori materi untuk Flutter (nama, warna, icon)
Route::get('/kategori-materi', [KategoriMateriApiController::class, 'index']);
// Mengambil ringkasan progres belajar materi (?siswa_id=...)
Route::get('/materi/progres', [MateriApiController::class, 'progres']);
// Mengambil daftar modul materi (support filter: ?kelas=10&kategori=Reading&siswa_id=...)
Route::get('/materi', [MateriApiController::class, 'index']);
// Mengambil detail isi materi lengkap beserta audio & latihan soal
Route::get('/materi/{id}', [MateriApiController::class, 'show']);
// Menyimpan penyelesaian materi siswa (+ XP reward)
Route::post('/materi/{id}/selesai', [MateriApiController::class, 'selesaikan']);

// 3. RUTE TERPROTEKSI SISWA (Wajib Token Siswa)
Route::middleware('auth.siswa')->group(function () {
    Route::get('/siswa/me', [LoginSiswaController::class, 'me']);
    Route::post('/siswa/logout', [LoginSiswaController::class, 'logout']);
});

// 4. DATA KOSAKATA & MASTER KELAS
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

// 2. RUTE TERPROTEKSI (Wajib Token Guru / Khusus Guru)
Route::middleware('auth.guru')->group(function () {
    // Kelola Data Siswa (Hanya Guru yang boleh melihat rekap semua siswa)
    Route::get('/siswa', [AuthSiswaController::class, 'index']);

    // Kelola Kosakata (Hanya Guru yang boleh Tambah, Ubah, Hapus)
    Route::post('/kosakata', [KosakataController::class, 'store']);
    Route::put('/kosakata/{id}', [KosakataController::class, 'update']);
    Route::delete('/kosakata/{id}', [KosakataController::class, 'destroy']);
});
