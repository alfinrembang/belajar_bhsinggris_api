<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriMateri;
use Illuminate\Http\JsonResponse;

class KategoriMateriApiController extends Controller
{
    /**
     * Mengambil daftar seluruh kategori materi aktif untuk aplikasi Flutter siswa.
     */
    public function index(): JsonResponse
    {
        $kategoris = KategoriMateri::where('is_aktif', true)
            ->withCount('materis')
            ->orderBy('urutan', 'asc')
            ->get()
            ->map(function ($kat) {
                return [
                    'id' => $kat->id,
                    'nama' => $kat->nama,
                    'slug' => $kat->slug,
                    'deskripsi' => $kat->deskripsi,
                    'warna_hex' => $kat->warna_hex,
                    'icon_name' => $kat->icon_name,
                    'jumlah_materi' => $kat->materis_count,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar kategori materi berhasil dimuat',
            'data' => $kategoris,
        ]);
    }
}
