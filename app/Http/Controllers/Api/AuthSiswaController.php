<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthSiswaController extends Controller
{
    /**
     * Registrasi Akun Siswa Baru.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'nama_lengkap' => 'sometimes|required|string|max:100',
            'email' => 'required|email|unique:siswas,email|max:100',
            'nisn' => 'required|string|unique:siswas,nisn|max:30',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'nullable|string|max:30',
            'no_kelas' => 'nullable|string|max:10',
            'no_absen' => 'nullable|integer|between:1,50',
            'sandi' => 'sometimes|required|string|min:6',
            'password' => 'sometimes|required|string|min:6',
        ]);

        $namaLengkap = $validated['nama'] ?? $validated['nama_lengkap'] ?? '';
        $rawPassword = $validated['sandi'] ?? $validated['password'] ?? '';
        $token = Str::random(64);

        $siswa = Siswa::create([
            'nama_lengkap' => $namaLengkap,
            'email' => $validated['email'],
            'nisn' => $validated['nisn'],
            'nis' => $validated['nisn'], // Fallback NIS menggunakan NISN
            'kelas' => $validated['kelas'],
            'jurusan' => $validated['jurusan'] ?? null,
            'no_kelas' => $validated['no_kelas'] ?? null,
            'no_absen' => $validated['no_absen'] ?? null,
            'password' => Hash::make($rawPassword),
            'api_token' => $token,
        ]);

        return response()->json([
            'status' => 'success',
            'role' => 'siswa',
            'message' => 'Registrasi akun siswa berhasil!',
            'token' => $token,
            'data' => $siswa,
        ], 201);
    }

    /**
     * Ambil Data Profil Siswa Berdasarkan NIS.
     */
    public function profil(string $nis): JsonResponse
    {
        $siswa = Siswa::where('nis', $nis)->orWhere('nisn', $nis)->first();

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan!',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'role' => 'siswa',
            'data' => $siswa,
        ], 200);
    }

    /**
     * Ambil Semua Daftar Siswa (Dapat Filter per Kelas ?kelas=10 TSM 1).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Siswa::query();

        if ($request->has('kelas') && ! empty($request->kelas)) {
            $query->where('kelas', $request->kelas);
        }

        $daftarSiswa = $query->orderBy('kelas', 'asc')
            ->orderByRaw('CAST(no_absen AS UNSIGNED) asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'total' => $daftarSiswa->count(),
            'data' => $daftarSiswa,
        ], 200);
    }
}
