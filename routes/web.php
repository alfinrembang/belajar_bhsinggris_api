<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\LoginGuruController;
use App\Http\Controllers\Guru\KategoriMateriController;
use App\Http\Controllers\Guru\LaporanAnalitikController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\ModulListeningController;
use App\Http\Controllers\Guru\PengaturanSistemController;
use App\Http\Controllers\Guru\QuizController;
use App\Http\Controllers\Guru\SiswaController;
use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
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
    // 0. Dashboard Utama (Jika login adalah admin, alihkan ke admin.dashboard)
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $totalSiswa = Siswa::count();
        $totalMateri = Materi::count();
        $totalListening = ModulListening::count();
        $totalQuiz = Quiz::count();

        return view('guru.dashboard', compact('totalSiswa', 'totalMateri', 'totalListening', 'totalQuiz'));
    })->name('guru.dashboard');

    // MODUL 1: MANAJEMEN MATERI PEMBELAJARAN (EKSPLISIT GET & POST/PUT/DELETE)
    Route::get('/materi', [MateriController::class, 'index'])->name('guru.materi.index');
    Route::get('/materi/create', [MateriController::class, 'create'])->name('guru.materi.create');
    Route::post('/materi/store', [MateriController::class, 'store'])->name('guru.materi.store');
    Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('guru.materi.show');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('guru.materi.edit');
    Route::match(['post', 'put'], '/materi/{materi}/update', [MateriController::class, 'update'])->name('guru.materi.update');
    Route::match(['post', 'delete'], '/materi/{materi}/destroy', [MateriController::class, 'destroy'])->name('guru.materi.destroy');

    // MODUL 1B: MANAJEMEN KATEGORI MATERI (EKSPLISIT GET & POST)
    Route::get('/kategori', [KategoriMateriController::class, 'index'])->name('guru.kategori.index');
    Route::get('/kategori/create', [KategoriMateriController::class, 'create'])->name('guru.kategori.create');
    Route::post('/kategori/store', [KategoriMateriController::class, 'store'])->name('guru.kategori.store');
    Route::get('/kategori/{kategori}', [KategoriMateriController::class, 'show'])->name('guru.kategori.show');
    Route::get('/kategori/{kategori}/edit', [KategoriMateriController::class, 'edit'])->name('guru.kategori.edit');
    Route::match(['post', 'put'], '/kategori/{kategori}/update', [KategoriMateriController::class, 'update'])->name('guru.kategori.update');
    Route::match(['post', 'delete'], '/kategori/{kategori}/destroy', [KategoriMateriController::class, 'destroy'])->name('guru.kategori.destroy');

    // MODUL 2: MODUL LISTENING GURU (EKSPLISIT GET & POST/PUT)
    Route::get('/listening', [ModulListeningController::class, 'index'])->name('guru.listening.index');
    Route::get('/listening/create', [ModulListeningController::class, 'create'])->name('guru.listening.create');
    Route::post('/listening/store', [ModulListeningController::class, 'store'])->name('guru.listening.store');
    Route::get('/listening/{listening}', [ModulListeningController::class, 'show'])->name('guru.listening.show');
    Route::get('/listening/{listening}/edit', [ModulListeningController::class, 'edit'])->name('guru.listening.edit');
    Route::match(['post', 'put'], '/listening/{listening}/update', [ModulListeningController::class, 'update'])->name('guru.listening.update');
    Route::match(['post', 'delete'], '/listening/{listening}/destroy', [ModulListeningController::class, 'destroy'])->name('guru.listening.destroy');

    // MODUL 3: BANK SOAL & KUIS GURU (EKSPLISIT GET & POST/PUT/DELETE)
    Route::get('/quiz', [QuizController::class, 'index'])->name('guru.quiz.index');
    Route::get('/quiz/create', [QuizController::class, 'create'])->name('guru.quiz.create');
    Route::post('/quiz/store', [QuizController::class, 'store'])->name('guru.quiz.store');
    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('guru.quiz.show');
    Route::get('/quiz/{quiz}/edit', [QuizController::class, 'edit'])->name('guru.quiz.edit');
    Route::match(['post', 'put'], '/quiz/{quiz}/update', [QuizController::class, 'update'])->name('guru.quiz.update');
    Route::match(['post', 'delete'], '/quiz/{quiz}/destroy', [QuizController::class, 'destroy'])->name('guru.quiz.destroy');

    // MODUL 4: MANAJEMEN SISWA & KELAS GURU
    Route::get('/siswa', [SiswaController::class, 'index'])->name('guru.siswa.index');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('guru.siswa.create');
    Route::post('/siswa/store', [SiswaController::class, 'store'])->name('guru.siswa.store');
    Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('guru.siswa.show');
    Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('guru.siswa.edit');
    Route::post('/siswa/{siswa}/update', [SiswaController::class, 'update'])->name('guru.siswa.update');
    Route::post('/siswa/toggle-kenaikan-kelas', [SiswaController::class, 'toggleKenaikanKelas'])->name('guru.siswa.toggle_kenaikan');
    Route::post('/siswa/{siswa}/reset-password', [SiswaController::class, 'resetPassword'])->name('guru.siswa.reset_password');
    Route::post('/siswa/{siswa}/destroy', [SiswaController::class, 'destroy'])->name('guru.siswa.destroy');

    // Mini Games & Leaderboard Guru
    Route::get('/games', function () {
        $daftarSiswa = Siswa::latest()->get();

        return view('guru.mini_game_leaderboard.index', compact('daftarSiswa'));
    })->name('guru.game.index');

    // MODUL 5: LAPORAN & ANALITIK GURU
    Route::get('/laporan', [LaporanAnalitikController::class, 'index'])->name('guru.laporan.index');
    Route::get('/laporan/export-excel', [LaporanAnalitikController::class, 'exportExcel'])->name('guru.laporan.export_excel');
    Route::get('/laporan/export-csv', [LaporanAnalitikController::class, 'exportCsv'])->name('guru.laporan.export_csv');
    Route::get('/laporan/cetak', [LaporanAnalitikController::class, 'cetak'])->name('guru.laporan.cetak');

    // MODUL 6: PENGATURAN SISTEM GURU
    Route::get('/pengaturan', [PengaturanSistemController::class, 'index'])->name('guru.pengaturan.index');
    Route::post('/pengaturan/profil', [PengaturanSistemController::class, 'updateProfil'])->name('guru.pengaturan.update_profil');
    Route::post('/pengaturan/password', [PengaturanSistemController::class, 'updatePassword'])->name('guru.pengaturan.update_password');
    Route::post('/pengaturan/akademik', [PengaturanSistemController::class, 'updateAkademik'])->name('guru.pengaturan.update_akademik');
});

// AREA KHUSUS ADMINISTRATOR UTAMA (HANYA ROLE ADMIN)
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/guru/store', [AdminDashboardController::class, 'storeGuru'])->name('guru.store');
    Route::post('/guru/{guru}/update', [AdminDashboardController::class, 'updateGuru'])->name('guru.update');
    Route::post('/guru/{guru}/reset-password', [AdminDashboardController::class, 'resetPasswordGuru'])->name('guru.reset_password');
    Route::post('/guru/{guru}/destroy', [AdminDashboardController::class, 'destroyGuru'])->name('guru.destroy');
});
