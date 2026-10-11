<?php

namespace Tests\Feature;

use App\Models\ListeningSoal;
use App\Models\ModulListening;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ListeningApiTest extends TestCase
{
    use DatabaseTransactions;

    protected Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->siswa = Siswa::create([
            'nama_lengkap' => 'Budi Siswa Listening',
            'email' => 'budi_listening_'.uniqid().'@gmail.com',
            'password' => bcrypt('password123'),
            'kelas' => '10',
        ]);
    }

    /**
     * API menolak penyelesaian modul listening jika ada butir soal yang belum dijawab.
     */
    public function test_completion_rejected_if_listening_questions_not_fully_answered(): void
    {
        $listening = ModulListening::create([
            'judul' => 'Daily Conversation Listening',
            'deskripsi' => 'Latihan listening percakapan sehari-hari',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
            'xp_reward' => 80,
        ]);

        $soal1 = ListeningSoal::create([
            'modul_listening_id' => $listening->id,
            'nomor_soal' => 1,
            'pertanyaan' => 'Where are they going?',
            'pilihan_a' => 'To school',
            'pilihan_b' => 'To market',
            'pilihan_c' => 'To library',
            'pilihan_d' => 'To hospital',
            'jawaban_benar' => 'A',
        ]);

        $soal2 = ListeningSoal::create([
            'modul_listening_id' => $listening->id,
            'nomor_soal' => 2,
            'pertanyaan' => 'What time does the event start?',
            'pilihan_a' => 'At 7 AM',
            'pilihan_b' => 'At 8 AM',
            'pilihan_c' => 'At 9 AM',
            'pilihan_d' => 'At 10 AM',
            'jawaban_benar' => 'B',
        ]);

        // Siswa hanya menjawab 1 dari 2 butir soal
        $response = $this->postJson("/api/listening/{$listening->id}/selesai", [
            'siswa_id' => $this->siswa->id,
            'jawaban' => [
                $soal1->id => 'A',
            ],
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
            ]);
    }

    /**
     * API menghitung skor listening secara akurat dan anti-cheat jika seluruh butir soal dijawab.
     */
    public function test_server_calculates_listening_score_accurately(): void
    {
        $listening = ModulListening::create([
            'judul' => 'Airport Announcement Test',
            'deskripsi' => 'Pengumuman di bandara',
            'tingkat_kesulitan' => 'Intermediate',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
            'xp_reward' => 100,
        ]);

        $soal1 = ListeningSoal::create([
            'modul_listening_id' => $listening->id,
            'nomor_soal' => 1,
            'pertanyaan' => 'Which flight is delayed?',
            'pilihan_a' => 'GA 101',
            'pilihan_b' => 'GA 202',
            'pilihan_c' => 'GA 303',
            'pilihan_d' => 'GA 404',
            'jawaban_benar' => 'A',
        ]);

        $soal2 = ListeningSoal::create([
            'modul_listening_id' => $listening->id,
            'nomor_soal' => 2,
            'pertanyaan' => 'Where should passengers go?',
            'pilihan_a' => 'Gate 1',
            'pilihan_b' => 'Gate 2',
            'pilihan_c' => 'Gate 3',
            'pilihan_d' => 'Gate 4',
            'jawaban_benar' => 'B',
        ]);

        // 1 Benar (soal 1: A) dan 1 Salah (soal 2: C) -> skor harus 50
        $response = $this->postJson("/api/listening/{$listening->id}/selesai", [
            'siswa_id' => $this->siswa->id,
            'jawaban' => [
                $soal1->id => 'A',
                $soal2->id => 'C',
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'modul_listening_id' => $listening->id,
                    'skor' => 50,
                    'xp_didapat' => 50,
                ],
            ]);

        $this->assertDatabaseHas('siswa_listening_progres', [
            'siswa_id' => $this->siswa->id,
            'modul_listening_id' => $listening->id,
            'status' => 'selesai',
            'skor' => 50,
            'xp_didapat' => 50,
        ]);
    }
}
