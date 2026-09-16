<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * Login Siswa Menggunakan Email atau NISN beserta Kata Sandi.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'nullable|string',
            'nisn' => 'nullable|string',
            'sandi' => 'sometimes|required|string',
            'password' => 'sometimes|required|string',
        ]);

        $rawPassword = $request->sandi ?? $request->password;

        if (empty($rawPassword)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kata sandi wajib diisi!',
            ], 422);
        }

        $query = Siswa::query();

        if ($request->filled('email') && $request->filled('nisn')) {
            $query->where('email', $request->email)->where(function ($q) use ($request): void {
                $q->where('nisn', $request->nisn)->orWhere('nis', $request->nisn);
            });
        } elseif ($request->filled('email')) {
            $query->where('email', $request->email);
        } elseif ($request->filled('nisn')) {
            $query->where('nisn', $request->nisn)->orWhere('nis', $request->nisn);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau NISN wajib diisi untuk masuk!',
            ], 422);
        }

        $siswa = $query->first();

        if (! $siswa || ! Hash::check($rawPassword, $siswa->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email/NISN atau kata sandi tidak sesuai!',
            ], 401);
        }

        // Buat token akses sesi baru
        $token = Str::random(64);
        $siswa->api_token = $token;
        $siswa->save();

        return response()->json([
            'status' => 'success',
            'role' => 'siswa',
            'message' => 'Selamat datang kembali, '.$siswa->nama_lengkap.'!',
            'token' => $token,
            'data' => $siswa,
        ], 200);
    }

    /**
     * Permintaan Lupa Sandi: Memverifikasi Email dan Menghasilkan Token Reset.
     */
    public function lupaSandi(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $siswa = Siswa::where('email', $request->email)->first();

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alamat email tidak ditemukan dalam data siswa!',
            ], 404);
        }

        // Buat kode / token reset 6 digit
        $resetToken = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $siswa->email],
            [
                'token' => Hash::make($resetToken),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Kode verifikasi reset kata sandi telah dikirim ke email kamu.',
            'data' => [
                'email' => $siswa->email,
                'reset_token' => $resetToken, // Disediakan untuk pengujian & alur mobile
            ],
        ], 200);
    }

    /**
     * Eksekusi Perubahan Kata Sandi Baru Menggunakan Token Reset.
     */
    public function resetSandi(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'sandi_baru' => 'sometimes|required|string|min:6',
            'password' => 'sometimes|required|string|min:6',
        ]);

        $newPassword = $request->sandi_baru ?? $request->password;

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode token reset tidak valid atau telah kedaluwarsa!',
            ], 422);
        }

        $siswa = Siswa::where('email', $request->email)->first();

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan!',
            ], 404);
        }

        $siswa->password = Hash::make($newPassword);
        $siswa->api_token = Str::random(64);
        $siswa->save();

        // Hapus token reset setelah berhasil digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kata sandi berhasil diperbarui! Silakan masuk dengan kata sandi baru.',
            'token' => $siswa->api_token,
        ], 200);
    }

    /**
     * Ambil Data Profil Siswa yang Sedang Login (Sesi Aktif).
     */
    public function me(Request $request): JsonResponse
    {
        $siswa = $request->get('authenticated_siswa') ?? auth('siswa')->user();

        if (! $siswa) {
            $token = $request->bearerToken() ?? $request->header('Authorization');
            if ($token) {
                $token = trim(str_replace('Bearer ', '', $token));
                $siswa = Siswa::where('api_token', $token)->first();
            }
        }

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi siswa tidak ditemukan atau tidak sah!',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'role' => 'siswa',
            'data' => $siswa,
        ], 200);
    }

    /**
     * Logout Sesi Siswa (Mencabut Token Akses).
     */
    public function logout(Request $request): JsonResponse
    {
        $siswa = $request->get('authenticated_siswa') ?? auth('siswa')->user();

        if (! $siswa) {
            $token = $request->bearerToken() ?? $request->header('Authorization');
            if ($token) {
                $token = trim(str_replace('Bearer ', '', $token));
                $siswa = Siswa::where('api_token', $token)->first();
            }
        }

        if ($siswa) {
            $siswa->api_token = null;
            $siswa->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil!',
        ], 200);
    }

    /**
     * Masuk untuk Siswa (Metode Kompatibilitas Tanpa Password Berbasis NIS).
     */
    public function masuk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50',
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'kelas' => 'required|string|max:50',
            'no_absen' => 'required|string|max:10',
        ]);

        $siswa = Siswa::updateOrCreate(
            ['nis' => $validated['nis']],
            [
                'nama_lengkap' => $validated['nama_lengkap'],
                'email' => $validated['email'] ?? null,
                'kelas' => $validated['kelas'],
                'no_absen' => $validated['no_absen'],
            ]
        );

        return response()->json([
            'status' => 'success',
            'role' => 'siswa',
            'message' => 'Selamat datang, '.$siswa->nama_lengkap.'!',
            'data' => $siswa,
        ], 200);
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
