<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\LoginGuruController;
use App\Http\Controllers\Guru\AiImportController;
use App\Http\Controllers\Guru\DashboardController;
use App\Http\Controllers\Guru\GameController;
use App\Http\Controllers\Guru\KategoriMateriController;
use App\Http\Controllers\Guru\LaporanAnalitikController;
use App\Http\Controllers\Guru\MateriController;
use App\Http\Controllers\Guru\ModulListeningController;
use App\Http\Controllers\Guru\PengaturanSistemController;
use App\Http\Controllers\Guru\QuizController;
use App\Http\Controllers\Guru\SiswaController;
use App\Models\Materi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Route;

// Web Routes - Portal Guru & Admin Belajar Bahasa Inggris

// Redirect root ke halaman login guru
Route::get('/', function () {
    return redirect()->route('login');
});

// AUTENTIKASI WEB GURU & ADMINISTRATOR
Route::get('/login', [LoginGuruController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginGuruController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
Route::post('/logout', [LoginGuruController::class, 'logout'])->name('logout');

// AREA TERPROTEKSI WEB GURU / ADMIN (WAJIB LOGIN)
Route::middleware(['auth'])->group(function () {
    // 0. Dashboard Utama Pendidik
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');

    // MODUL 1: MANAJEMEN MATERI PEMBELAJARAN (EKSPLISIT GET & POST/PUT/DELETE)
    Route::get('/materi', [MateriController::class, 'index'])->name('guru.materi.index');
    Route::get('/materi/create', [MateriController::class, 'create'])->name('guru.materi.create');
    Route::post('/materi/store', [MateriController::class, 'store'])->name('guru.materi.store');
    Route::match(['post', 'delete'], '/materi/bulk-destroy', [MateriController::class, 'bulkDestroy'])->name('guru.materi.bulk_destroy');
    Route::get('/materi/{materi}', [MateriController::class, 'show'])->name('guru.materi.show');
    Route::get('/materi/{materi}/edit', [MateriController::class, 'edit'])->name('guru.materi.edit');
    Route::match(['post', 'put'], '/materi/{materi}/update', [MateriController::class, 'update'])->name('guru.materi.update');
    Route::match(['post', 'delete'], '/materi/{materi}/destroy', [MateriController::class, 'destroy'])->name('guru.materi.destroy');

    // MODUL 1B: MANAJEMEN KATEGORI MATERI (EKSPLISIT GET & POST)
    Route::get('/kategori', [KategoriMateriController::class, 'index'])->name('guru.kategori.index');
    Route::get('/kategori/create', [KategoriMateriController::class, 'create'])->name('guru.kategori.create');
    Route::post('/kategori/store', [KategoriMateriController::class, 'store'])->name('guru.kategori.store');
    Route::match(['post', 'delete'], '/kategori/bulk-destroy', [KategoriMateriController::class, 'bulkDestroy'])->name('guru.kategori.bulk_destroy');
    Route::get('/kategori/{kategori}', [KategoriMateriController::class, 'show'])->name('guru.kategori.show');
    Route::get('/kategori/{kategori}/edit', [KategoriMateriController::class, 'edit'])->name('guru.kategori.edit');
    Route::match(['post', 'put'], '/kategori/{kategori}/update', [KategoriMateriController::class, 'update'])->name('guru.kategori.update');
    Route::match(['post', 'delete'], '/kategori/{kategori}/destroy', [KategoriMateriController::class, 'destroy'])->name('guru.kategori.destroy');

    // MODUL 2: MODUL LISTENING GURU (EKSPLISIT GET & POST/PUT)
    Route::get('/listening', [ModulListeningController::class, 'index'])->name('guru.listening.index');
    Route::get('/listening/create', [ModulListeningController::class, 'create'])->name('guru.listening.create');
    Route::post('/listening/store', [ModulListeningController::class, 'store'])->name('guru.listening.store');
    Route::match(['post', 'delete'], '/listening/bulk-destroy', [ModulListeningController::class, 'bulkDestroy'])->name('guru.listening.bulk_destroy');
    Route::get('/listening/{listening}', [ModulListeningController::class, 'show'])->name('guru.listening.show');
    Route::get('/listening/{listening}/edit', [ModulListeningController::class, 'edit'])->name('guru.listening.edit');
    Route::match(['post', 'put'], '/listening/{listening}/update', [ModulListeningController::class, 'update'])->name('guru.listening.update');
    Route::match(['post', 'delete'], '/listening/{listening}/destroy', [ModulListeningController::class, 'destroy'])->name('guru.listening.destroy');

    // MODUL 3: BANK SOAL & KUIS GURU (EKSPLISIT GET & POST/PUT/DELETE)
    Route::get('/quiz', [QuizController::class, 'index'])->name('guru.quiz.index');
    Route::get('/quiz/create', [QuizController::class, 'create'])->name('guru.quiz.create');
    Route::post('/quiz/store', [QuizController::class, 'store'])->name('guru.quiz.store');
    Route::match(['post', 'delete'], '/quiz/bulk-destroy', [QuizController::class, 'bulkDestroy'])->name('guru.quiz.bulk_destroy');
    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('guru.quiz.show');
    Route::get('/quiz/{quiz}/edit', [QuizController::class, 'edit'])->name('guru.quiz.edit');
    Route::match(['post', 'put'], '/quiz/{quiz}/update', [QuizController::class, 'update'])->name('guru.quiz.update');
    Route::match(['post', 'delete'], '/quiz/{quiz}/destroy', [QuizController::class, 'destroy'])->name('guru.quiz.destroy');

    // MODUL 4: MANAJEMEN SISWA & KELAS GURU
    Route::get('/siswa', [SiswaController::class, 'index'])->name('guru.siswa.index');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('guru.siswa.create');
    Route::post('/siswa/store', [SiswaController::class, 'store'])->name('guru.siswa.store');
    Route::get('/siswa/template-excel', [SiswaController::class, 'downloadTemplateExcel'])->name('guru.siswa.template_excel');
    Route::post('/siswa/import-excel', [SiswaController::class, 'importExcel'])->name('guru.siswa.import_excel');
    Route::match(['post', 'delete'], '/siswa/bulk-destroy', [SiswaController::class, 'bulkDestroy'])->name('guru.siswa.bulk_destroy');
    Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('guru.siswa.show');
    Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('guru.siswa.edit');
    Route::post('/siswa/{siswa}/update', [SiswaController::class, 'update'])->name('guru.siswa.update');
    Route::post('/siswa/{siswa}/reset-password', [SiswaController::class, 'resetPassword'])->name('guru.siswa.reset_password');
    Route::match(['post', 'delete'], '/siswa/{siswa}/destroy', [SiswaController::class, 'destroy'])->name('guru.siswa.destroy');

    // Mini Games & Leaderboard Guru (Dinamis Sesuai Game Mobile)
    Route::get('/games', [GameController::class, 'index'])->name('guru.game.index');

    // Game 1: Tangkap Ikan (Word Fishing)
    Route::post('/games/fishing/soal', [GameController::class, 'storeFishingSoal'])->name('guru.game.fishing.store');
    Route::match(['post', 'delete'], '/games/fishing/soal/{id}/delete', [GameController::class, 'destroyFishingSoal'])->name('guru.game.fishing.destroy');
    Route::post('/games/fishing/import', [GameController::class, 'importFishingExcel'])->name('guru.game.fishing.import');
    Route::get('/games/fishing/template', [GameController::class, 'downloadFishingTemplate'])->name('guru.game.fishing.template');

    // Game 2: Tebak Serigala (Wolf vs Sheep)
    Route::post('/games/werewolf/soal', [GameController::class, 'storeWerewolfSoal'])->name('guru.game.werewolf.store');
    Route::match(['post', 'delete'], '/games/werewolf/soal/{id}/delete', [GameController::class, 'destroyWerewolfSoal'])->name('guru.game.werewolf.destroy');
    Route::post('/games/werewolf/import', [GameController::class, 'importWerewolfExcel'])->name('guru.game.werewolf.import');
    Route::get('/games/werewolf/template', [GameController::class, 'downloadWerewolfTemplate'])->name('guru.game.werewolf.template');

    // Game 3: Adu Lafal (Pronounce Hero)
    Route::post('/games/pronounce/word', [GameController::class, 'storePronounceWord'])->name('guru.game.pronounce.store');
    Route::post('/games/pronounce/word/{id}/update', [GameController::class, 'updatePronounceWord'])->name('guru.game.pronounce.update');
    Route::match(['post', 'delete'], '/games/pronounce/word/{id}/delete', [GameController::class, 'destroyPronounceWord'])->name('guru.game.pronounce.destroy');
    Route::post('/games/pronounce/import', [GameController::class, 'importPronounceExcel'])->name('guru.game.pronounce.import');
    Route::get('/games/pronounce/template', [GameController::class, 'downloadPronounceTemplate'])->name('guru.game.pronounce.template');
    Route::post('/games/sentence/soal', [GameController::class, 'storeSentenceSoal'])->name('guru.game.sentence.store');
    Route::post('/games/sentence/soal/{id}/update', [GameController::class, 'updateSentenceSoal'])->name('guru.game.sentence.update');
    Route::match(['post', 'delete'], '/games/sentence/soal/{id}/delete', [GameController::class, 'destroySentenceSoal'])->name('guru.game.sentence.destroy');
    Route::post('/games/sentence/import', [GameController::class, 'importSentenceExcel'])->name('guru.game.sentence.import');
    Route::get('/games/sentence/template', [GameController::class, 'downloadSentenceTemplate'])->name('guru.game.sentence.template');

    // MODUL 5: LAPORAN & ANALITIK GURU
    Route::get('/laporan', [LaporanAnalitikController::class, 'index'])->name('guru.laporan.index');
    Route::get('/laporan/materi', [LaporanAnalitikController::class, 'materi'])->name('guru.laporan.materi');
    Route::get('/laporan/materi/cetak', [LaporanAnalitikController::class, 'cetakMateri'])->name('guru.laporan.materi.cetak');
    Route::get('/laporan/materi/export-excel', [LaporanAnalitikController::class, 'exportMateriExcel'])->name('guru.laporan.materi.export_excel');
    Route::get('/laporan/quiz', [LaporanAnalitikController::class, 'quiz'])->name('guru.laporan.quiz');
    Route::get('/laporan/quiz/cetak', [LaporanAnalitikController::class, 'cetakQuiz'])->name('guru.laporan.quiz.cetak');
    Route::get('/laporan/quiz/export-excel', [LaporanAnalitikController::class, 'exportQuizExcel'])->name('guru.laporan.quiz.export_excel');
    Route::get('/laporan/listening', [LaporanAnalitikController::class, 'listening'])->name('guru.laporan.listening');
    Route::get('/laporan/listening/cetak', [LaporanAnalitikController::class, 'cetakListening'])->name('guru.laporan.listening.cetak');
    Route::get('/laporan/listening/export-excel', [LaporanAnalitikController::class, 'exportListeningExcel'])->name('guru.laporan.listening.export_excel');
    Route::get('/laporan/export-excel', [LaporanAnalitikController::class, 'exportExcel'])->name('guru.laporan.export_excel');
    Route::get('/laporan/export-csv', [LaporanAnalitikController::class, 'exportCsv'])->name('guru.laporan.export_csv');
    Route::get('/laporan/cetak', [LaporanAnalitikController::class, 'cetak'])->name('guru.laporan.cetak');

    // MODUL 6: PENGATURAN SISTEM GURU
    Route::get('/pengaturan', [PengaturanSistemController::class, 'index'])->name('guru.pengaturan.index');
    Route::post('/pengaturan/profil', [PengaturanSistemController::class, 'updateProfil'])->name('guru.pengaturan.update_profil');
    Route::post('/pengaturan/password', [PengaturanSistemController::class, 'updatePassword'])->name('guru.pengaturan.update_password');
    Route::post('/pengaturan/akademik', [PengaturanSistemController::class, 'updateAkademik'])->name('guru.pengaturan.update_akademik');
    // MODUL 7: AI DOCUMENT IMPORTER (BACKGROUND PROCESSING VIA REDIS & GEMINI)
    Route::post('/ai-import/upload', [AiImportController::class, 'upload'])->middleware('throttle:15,1')->name('guru.ai-import.upload');
    Route::get('/ai-import/{aiImport}/status', [AiImportController::class, 'status'])->name('guru.ai-import.status');
    Route::get('/ai-import/{aiImport}/review', [AiImportController::class, 'review'])->name('guru.ai-import.review');
    Route::post('/ai-import/{aiImport}/confirm-materi', [AiImportController::class, 'confirmMateri'])->name('guru.ai-import.confirm-materi');
    Route::post('/ai-import/{aiImport}/confirm-quiz', [AiImportController::class, 'confirmQuiz'])->name('guru.ai-import.confirm-quiz');
});

// AREA KHUSUS ADMINISTRATOR UTAMA (HANYA ROLE ADMIN)
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/guru/store', [AdminDashboardController::class, 'storeGuru'])->name('guru.store');
    Route::post('/guru/{guru}/update', [AdminDashboardController::class, 'updateGuru'])->name('guru.update');
    Route::post('/guru/{guru}/reset-password', [AdminDashboardController::class, 'resetPasswordGuru'])->name('guru.reset_password');
    Route::match(['post', 'delete'], '/guru/{guru}/destroy', [AdminDashboardController::class, 'destroyGuru'])->name('guru.destroy');
    Route::post('/kenaikan-kelas/toggle', [AdminDashboardController::class, 'toggleKenaikanKelas'])->name('kenaikan_kelas.toggle');
});
