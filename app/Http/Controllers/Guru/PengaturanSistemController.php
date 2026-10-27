<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\UpdateAkademikRequest;
use App\Http\Requests\Guru\UpdatePasswordGuruRequest;
use App\Http\Requests\Guru\UpdateProfilGuruRequest;
use App\Models\PengaturanAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PengaturanSistemController extends Controller
{
    /**
     * Menampilkan dashboard pengaturan sistem, profil pengajar, dan konfigurasi akademik.
     */
    public function index(Request $request): View
    {
        $guru = $request->user();
        $pengaturanAkademik = PengaturanAkademik::getActiveSetting();

        return view('guru.pengaturan_sistem.index', compact('guru', 'pengaturanAkademik'));
    }

    /**
     * Memperbarui informasi profil pengajar (nama, email, dan NIP).
     */
    public function updateProfil(UpdateProfilGuruRequest $request): RedirectResponse
    {
        $guru = $request->user();
        $validated = $request->validated();

        $guru->update($validated);

        return back()->with('success', 'Informasi profil pengajar berhasil diperbarui!');
    }

    /**
     * Mengubah kata sandi akun login guru dengan verifikasi kata sandi saat ini.
     */
    public function updatePassword(UpdatePasswordGuruRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi akun pengajar berhasil diperbarui!');
    }

    /**
     * Memperbarui konfigurasi akademik, periode kenaikan kelas siswa, dan standar penilaian.
     */
    public function updateAkademik(UpdateAkademikRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $setting = PengaturanAkademik::getActiveSetting();

        $setting->update([
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'is_periode_kenaikan_aktif' => $request->boolean('is_periode_kenaikan_aktif'),
            'kkm_default' => $validated['kkm_default'],
            'durasi_kuis_default' => $validated['durasi_kuis_default'],
            'is_leaderboard_aktif' => $request->boolean('is_leaderboard_aktif'),
            'keterangan' => $validated['keterangan'] ?? null,
            'opened_at' => $request->boolean('is_periode_kenaikan_aktif') ? ($setting->opened_at ?? now()) : null,
            'closed_at' => ! $request->boolean('is_periode_kenaikan_aktif') ? now() : null,
        ]);

        return back()->with('success', 'Pengaturan akademik & evaluasi berhasil diperbarui!');
    }
}
