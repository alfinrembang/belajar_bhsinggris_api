<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AuthSiswaController extends Controller
{
    /**
     * Masuk untuk Siswa (Tanpa password, berbasis NIS)
     */
    public function masuk(Request $request)
    {
        $validated = $request->validate([
            'nis'          => 'required|string|max:50',
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'nullable|email|max:100',
            'kelas'        => 'required|string|max:50',
            'no_absen'     => 'required|string|max:10',
        ]);

        // Cari berdasarkan NIS (kunci utama abadi), data lainnya otomatis ter-update jika berubah
        $siswa = Siswa::updateOrCreate(
            ['nis' => $validated['nis']],
            [
                'nama_lengkap' => $validated['nama_lengkap'],
                'email'        => $validated['email'] ?? null,
                'kelas'        => $validated['kelas'],
                'no_absen'     => $validated['no_absen'],
            ]
        );

        return response()->json([
            'status'  => 'success',
            'role'    => 'siswa',
            'message' => 'Selamat datang, ' . $siswa->nama_lengkap . '!',
            'data'    => $siswa,
        ], 200);
    }

    /**
     * Ambil data profil siswa berdasarkan NIS
     */
    public function profil($nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();

        if (!$siswa) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data siswa tidak ditemukan!',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'role'   => 'siswa',
            'data'   => $siswa,
        ], 200);
    }

    /**
     * Ambil semua daftar siswa (bisa filter per kelas ?kelas=10 TSM 1)
     */
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->has('kelas') && !empty($request->kelas)) {
            $query->where('kelas', $request->kelas);
        }

        $daftarSiswa = $query->orderBy('kelas', 'asc')
                             ->orderByRaw('CAST(no_absen AS UNSIGNED) asc')
                             ->get();

        return response()->json([
            'status' => 'success',
            'total'  => $daftarSiswa->count(),
            'data'   => $daftarSiswa,
        ], 200);
    }
}
