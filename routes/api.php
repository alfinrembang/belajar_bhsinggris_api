<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KosakataController;
use App\Http\Controllers\Api\AuthGuruController;
use App\Http\Controllers\Api\AuthSiswaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- 1. Jalur Autentikasi Guru ---
Route::post('/guru/login', [AuthGuruController::class, 'login']);
Route::post('/guru/logout', [AuthGuruController::class, 'logout']);
Route::get('/guru/me', [AuthGuruController::class, 'me']);

// --- 2. Jalur Masuk & Data Siswa ---
Route::post('/siswa/masuk', [AuthSiswaController::class, 'masuk']);
Route::get('/siswa/profil/{nis}', [AuthSiswaController::class, 'profil']);
Route::get('/siswa', [AuthSiswaController::class, 'index']);

// --- 3. Jalur Kosakata ---
Route::apiResource('kosakata', KosakataController::class);
