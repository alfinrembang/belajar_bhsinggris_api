<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterSiswaRequest;
use App\Http\Requests\Api\UpdateProfilSiswaRequest;
use App\Models\PengaturanAkademik;
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
    public function register(RegisterSiswaRequest $request): JsonResponse
    {
        $validated = $request->validated();

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

    /**
     * Cek status periode kenaikan kelas dan tahun ajaran aktif.
     */
    public function statusAkademik(Request $request): JsonResponse
    {
        $pengaturan = PengaturanAkademik::getActiveSetting();

        $siswa = null;
        if ($request->filled('siswa_id')) {
            $siswa = Siswa::find($request->input('siswa_id'));
        } elseif ($token = $request->bearerToken()) {
            $siswa = Siswa::where('api_token', trim($token))->first();
        }

        $perluKenaikanKelas = false;
        if ($siswa && $pengaturan->is_periode_kenaikan_aktif) {
            if ($siswa->tahun_ajaran_aktif !== $pengaturan->tahun_ajaran) {
                $perluKenaikanKelas = true;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_periode_kenaikan_aktif' => (bool) $pengaturan->is_periode_kenaikan_aktif,
                'tahun_ajaran' => $pengaturan->tahun_ajaran,
                'keterangan' => $pengaturan->keterangan,
                'opened_at' => $pengaturan->opened_at?->toIso8601String(),
                'closed_at' => $pengaturan->closed_at?->toIso8601String(),
                'perlu_kenaikan_kelas' => $perluKenaikanKelas,
                'siswa' => $siswa ? [
                    'id' => $siswa->id,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'kelas' => $siswa->kelas,
                    'jurusan' => $siswa->jurusan,
                    'no_kelas' => $siswa->no_kelas,
                    'no_absen' => $siswa->no_absen,
                    'kelas_lengkap' => $siswa->kelas_lengkap,
                    'tahun_ajaran_aktif' => $siswa->tahun_ajaran_aktif,
                    'status_kelulusan' => $siswa->status_kelulusan,
                ] : null,
            ],
        ], 200);
    }

    /**
     * Memperbarui profil siswa (Nama, Kelas/Rombel, No Absen, Sandi) dari aplikasi Flutter.
     */
    public function updateProfil(UpdateProfilSiswaRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $siswa = null;
        if ($request->filled('siswa_id')) {
            $siswa = Siswa::find($request->input('siswa_id'));
        } elseif ($token = $request->bearerToken()) {
            $siswa = Siswa::where('api_token', trim($token))->first();
        }

        if (! $siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data akun siswa tidak ditemukan.',
            ], 404);
        }

        $pengaturan = PengaturanAkademik::getActiveSetting();

        // 1. Parsing Kelas & Rombel (Bisa format terpisah atau gabungan 'XI TSM 1')
        $targetTingkat = null;
        $targetNoKelas = null;

        if ($request->filled('kelas_lengkap')) {
            $parts = explode(' ', trim((string) $request->input('kelas_lengkap')));
            if (count($parts) >= 1) {
                $rawTingkat = strtoupper($parts[0]);
                $targetTingkat = match ($rawTingkat) {
                    'X', '10' => '10',
                    'XI', '11' => '11',
                    'XII', '12' => '12',
                    default => $rawTingkat,
                };
            }
            if (count($parts) >= 3) {
                $targetNoKelas = $parts[2];
            }
        }

        if ($request->filled('kelas')) {
            $rawTingkat = strtoupper(trim((string) $request->input('kelas')));
            $targetTingkat = match ($rawTingkat) {
                'X', '10' => '10',
                'XI', '11' => '11',
                'XII', '12' => '12',
                default => $rawTingkat,
            };
        }

        if ($request->filled('no_kelas')) {
            $targetNoKelas = (string) $request->input('no_kelas');
        }

        // Cek apakah ada perubahan kelas atau nomor rombel atau nomor absen
        $isChangingRombel = false;
        if ($targetTingkat !== null && $targetTingkat !== $siswa->kelas) {
            $isChangingRombel = true;
        }
        if ($targetNoKelas !== null && $targetNoKelas !== $siswa->no_kelas) {
            $isChangingRombel = true;
        }
        if ($request->filled('no_absen') && (string) $request->input('no_absen') !== (string) $siswa->no_absen) {
            $isChangingRombel = true;
        }

        // Validasi Sakelar Kenaikan Kelas Guru
        if ($isChangingRombel) {
            if (! $pengaturan->is_periode_kenaikan_aktif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Periode kenaikan kelas sedang ditutup oleh guru. Hubungi gurumu jika ingin mengubah rombel atau nomor absen.',
                ], 422);
            }

            // Jika periode dibuka, terapkan perubahan rombel dan perbarui tahun ajaran siswa
            if ($targetTingkat !== null) {
                $siswa->kelas = $targetTingkat;
            }
            if ($targetNoKelas !== null) {
                $siswa->no_kelas = $targetNoKelas;
            }
            if ($request->filled('no_absen')) {
                $siswa->no_absen = (string) $request->input('no_absen');
            }

            $siswa->tahun_ajaran_aktif = $pengaturan->tahun_ajaran;
        }

        // 2. Pembaruan Nama Lengkap
        if ($request->filled('nama_lengkap')) {
            $siswa->nama_lengkap = trim($request->input('nama_lengkap'));
        } elseif ($request->filled('nama')) {
            $siswa->nama_lengkap = trim($request->input('nama'));
        }

        // 3. Pembaruan Kata Sandi
        $sandiBaru = $request->input('sandi_baru') ?? $request->input('password');
        if (! empty($sandiBaru)) {
            $sandiLama = $request->input('sandi_lama') ?? $request->input('password_lama');
            if (empty($sandiLama)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kata sandi lama wajib diisi untuk verifikasi!',
                ], 422);
            }

            if (! Hash::check($sandiLama, $siswa->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kata sandi lama yang Anda masukkan tidak sesuai!',
                ], 422);
            }

            if (strlen($sandiBaru) < 6) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kata sandi baru minimal berjumlah 6 karakter!',
                ], 422);
            }

            $siswa->password = Hash::make($sandiBaru);
        }

        $siswa->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil dan data kelas berhasil diperbarui!',
            'data' => $siswa,
        ], 200);
    }
}
