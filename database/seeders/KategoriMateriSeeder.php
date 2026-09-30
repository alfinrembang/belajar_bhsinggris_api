<?php

namespace Database\Seeders;

use App\Models\KategoriMateri;
use Illuminate\Database\Seeder;

class KategoriMateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Reading',
                'slug' => 'reading',
                'deskripsi' => 'Membaca teks deskriptif, naratif, dan analisis konteks bacaan bahasa Inggris.',
                'warna_hex' => '#1E6BFF', // Royal Blue
                'icon_name' => 'auto_stories',
                'urutan' => 1,
                'is_aktif' => true,
            ],
            [
                'nama' => 'Grammar',
                'slug' => 'grammar',
                'deskripsi' => 'Aturan tata bahasa, pola tenses, struktur kalimat, dan rumus grammar terstandar.',
                'warna_hex' => '#059669', // Emerald Green
                'icon_name' => 'spellcheck',
                'urutan' => 2,
                'is_aktif' => true,
            ],
            [
                'nama' => 'Conversation',
                'slug' => 'conversation',
                'deskripsi' => 'Keterampilan percakapan interaktif, ungkapan opini, diskusi, dan respon santun.',
                'warna_hex' => '#7C3AED', // Royal Violet / Ungu
                'icon_name' => 'forum',
                'urutan' => 3,
                'is_aktif' => true,
            ],
            [
                'nama' => 'Vocabulary',
                'slug' => 'vocabulary',
                'deskripsi' => 'Penguasaan kosakata tematik, idiom populer, padanan kata, dan istilah profesional.',
                'warna_hex' => '#D97706', // Warm Amber / Oranye
                'icon_name' => 'translate',
                'urutan' => 4,
                'is_aktif' => true,
            ],
        ];

        foreach ($kategoris as $item) {
            KategoriMateri::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
