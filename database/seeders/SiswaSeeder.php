<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswas = [
            [
                'nis'          => '202401',
                'nama_lengkap' => 'Budi Santoso',
                'email'        => 'budi@gmail.com',
                'kelas'        => '10 TSM 1',
                'no_absen'     => '05',
            ],
            [
                'nis'          => '202402',
                'nama_lengkap' => 'Siti Rahmawati',
                'email'        => 'siti@gmail.com',
                'kelas'        => '10 RPL 1',
                'no_absen'     => '12',
            ],
            [
                'nis'          => '202403',
                'nama_lengkap' => 'Rizky Pratama',
                'email'        => 'rizky@gmail.com',
                'kelas'        => '10 BD 1',
                'no_absen'     => '08',
            ],
            [
                'nis'          => '202404',
                'nama_lengkap' => 'Dwi Saputra',
                'email'        => 'dwi@gmail.com',
                'kelas'        => '11 TSM 2',
                'no_absen'     => '03',
            ],
            [
                'nis'          => '202405',
                'nama_lengkap' => 'Amanda Putri',
                'email'        => 'amanda@gmail.com',
                'kelas'        => '10 DKV 1',
                'no_absen'     => '15',
            ],
        ];

        foreach ($siswas as $item) {
            Siswa::updateOrCreate(['nis' => $item['nis']], $item);
        }
    }
}
