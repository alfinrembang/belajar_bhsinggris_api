<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengaturanAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
    public function updateProfil(Request $request): RedirectResponse
    {
        $guru = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($guru->id)],
            'nip' => ['nullable', 'string', 'max:50'],
        ], [
            'name.required' => 'Nama lengkap guru wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar oleh akun lain.',
        ]);

        $guru->update($validated);

        return back()->with('success', 'Informasi profil pengajar berhasil diperbarui!');
    }

    /**
     * Mengubah kata sandi akun login guru dengan verifikasi kata sandi saat ini.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan catatan sistem.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi akun pengajar berhasil diperbarui!');
    }

    /**
     * Memperbarui konfigurasi akademik, periode kenaikan kelas siswa, dan standar penilaian.
     */
    public function updateAkademik(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
            'is_periode_kenaikan_aktif' => ['nullable', 'boolean'],
            'kkm_default' => ['required', 'integer', 'min:0', 'max:100'],
            'durasi_kuis_default' => ['required', 'integer', 'min:5', 'max:180'],
            'is_leaderboard_aktif' => ['nullable', 'boolean'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus berupa format Tahun/Tahun (contoh: 2026/2027).',
            'kkm_default.required' => 'Standar KKM kuis wajib ditentukan.',
            'kkm_default.min' => 'Nilai KKM minimal 0.',
            'kkm_default.max' => 'Nilai KKM maksimal 100.',
            'durasi_kuis_default.required' => 'Durasi kuis default wajib diisi.',
        ]);

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
