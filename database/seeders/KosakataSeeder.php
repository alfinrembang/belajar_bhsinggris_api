<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kosakata;

class KosakataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kosakata::truncate(); // Kosongkan dulu agar tidak duplikat

        $data = [
            [
                'english'   => 'Book',
                'indonesia' => 'Buku',
                'contoh'    => 'I am reading an interesting book.',
            ],
            [
                'english'   => 'Apple',
                'indonesia' => 'Apel',
                'contoh'    => 'She eats a sweet red apple.',
            ],
            [
                'english'   => 'Learn',
                'indonesia' => 'Belajar',
                'contoh'    => 'We learn English vocabulary together every day.',
            ],
            [
                'english'   => 'School',
                'indonesia' => 'Sekolah',
                'contoh'    => 'They go to school together by bus.',
            ],
            [
                'english'   => 'Teacher',
                'indonesia' => 'Guru',
                'contoh'    => 'The teacher explains the lesson clearly.',
            ],
            [
                'english'   => 'Student',
                'indonesia' => 'Murid',
                'contoh'    => 'The student is doing English homework.',
            ],
            [
                'english'   => 'Friend',
                'indonesia' => 'Teman',
                'contoh'    => 'A good friend always supports you.',
            ],
            [
                'english'   => 'Water',
                'indonesia' => 'Air',
                'contoh'    => 'Make sure to drink enough water every day.',
            ],
        ];

        foreach ($data as $item) {
            Kosakata::create($item);
        }
    }
}
