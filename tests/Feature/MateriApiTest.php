<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\MateriSoal;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MateriApiTest extends TestCase
{
    use DatabaseTransactions;

    protected Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->siswa = Siswa::create([
            'nama_lengkap' => 'Ahmad Siswa Test',
            'email' => 'ahmad_test_'.uniqid().'@gmail.com',
            'password' => bcrypt('password123'),
            'kelas' => '10',
        ]);
    }

    /**
     * API dapat memuat daftar materi yang aktif dan menyaring yang berstatus draft.
     */
    public function test_can_get_active_materi_list(): void
    {
        $materiAktif = Materi::factory()->create([
            'judul' => 'Materi Aktif Terbuka',
            'status' => 'aktif',
            'tingkat_kelas' => '10',
        ]);

        $materiDraft = Materi::factory()->draft()->create([
            'judul' => 'Materi Rahasia Draft',
        ]);

        $response = $this->getJson('/api/materi?kelas=10');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Daftar materi berhasil dimuat',
            ])
            ->assertJsonFragment([
                'judul' => 'Materi Aktif Terbuka',
            ])
            ->assertJsonMissing([
                'judul' => 'Materi Rahasia Draft',
            ]);
    }

    /**
     * API dapat menampilkan detail modul materi beserta daftar butir latihannya.
     */
    public function test_can_get_materi_detail_with_soals(): void
    {
        $materi = Materi::factory()->create([
            'judul' => 'Materi Detail Belajar',
            'status' => 'aktif',
        ]);

        $soal = MateriSoal::factory()->create([
            'materi_id' => $materi->id,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'Pertanyaan uji coba?',
        ]);

        $response = $this->getJson("/api/materi/{$materi->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $materi->id,
                    'judul' => 'Materi Detail Belajar',
                ],
            ])
            ->assertJsonFragment([
                'pertanyaan' => 'Pertanyaan uji coba?',
            ]);
    }

    /**
     * Mengakses materi draft atau id tidak dikenal mengembalikan response 404.
     */
    public function test_returns_404_for_draft_or_missing_materi(): void
    {
        $materiDraft = Materi::factory()->draft()->create();

        $response = $this->getJson("/api/materi/{$materiDraft->id}");
        $response->assertStatus(404);

        $response404 = $this->getJson('/api/materi/99999999');
        $response404->assertStatus(404);
    }

    /**
     * Siswa menyelesaikan modul materi dan skor dihitung di server secara anti-cheat.
     */
    public function test_server_calculates_score_accurately_from_submitted_answers(): void
    {
        $materi = Materi::factory()->create([
            'status' => 'aktif',
            'xp_reward' => 100,
        ]);

        $soal1 = MateriSoal::factory()->create([
            'materi_id' => $materi->id,
            'kunci_jawaban' => 'A',
        ]);

        $soal2 = MateriSoal::factory()->create([
            'materi_id' => $materi->id,
            'kunci_jawaban' => 'B',
        ]);

        // Siswa menjawab 1 benar (soal1: A) dan 1 salah (soal2: C) -> skor harus 50%
        $payload = [
            'siswa_id' => $this->siswa->id,
            'jawaban' => [
                $soal1->id => 'A',
                $soal2->id => 'C',
            ],
        ];

        $response = $this->postJson("/api/materi/{$materi->id}/selesai", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'materi_id' => $materi->id,
                    'skor' => 50,
                    'xp_didapat' => 50, // 50% dari 100 XP
                ],
            ]);

        $this->assertDatabaseHas('siswa_materi_progres', [
            'siswa_id' => $this->siswa->id,
            'materi_id' => $materi->id,
            'status' => 'selesai',
            'skor' => 50,
            'xp_didapat' => 50,
        ]);
    }

    /**
     * Anti-cheat mencegah client memalsukan perolehan XP.
     */
    public function test_anti_cheat_prevents_client_xp_spoofing(): void
    {
        $materi = Materi::factory()->create([
            'status' => 'aktif',
            'xp_reward' => 50,
        ]);

        // Client mencoba mengirim payload palsu dengan XP 999999
        $payload = [
            'siswa_id' => $this->siswa->id,
            'skor' => 100,
            'xp_reward' => 999999,
        ];

        $response = $this->postJson("/api/materi/{$materi->id}/selesai", $payload);

        $response->assertStatus(200);

        // Nilai XP yang didapat harus tetap sesuai database (50 XP), bukan 999999
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'xp_didapat' => 50,
            ],
        ]);

        $this->assertDatabaseHas('siswa_materi_progres', [
            'siswa_id' => $this->siswa->id,
            'materi_id' => $materi->id,
            'xp_didapat' => 50,
        ]);
    }

    /**
     * API dapat memuat ringkasan progres penyelesaian modul materi oleh siswa.
     */
    public function test_can_get_materi_progress_summary(): void
    {
        $materi = Materi::factory()->create([
            'status' => 'aktif',
        ]);

        $this->postJson("/api/materi/{$materi->id}/selesai", [
            'siswa_id' => $this->siswa->id,
            'skor' => 100,
        ]);

        $response = $this->getJson("/api/materi/progres?siswa_id={$this->siswa->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonStructure([
                'data' => [
                    'total_materi',
                    'materi_selesai',
                    'persentase',
                    'completed_materi_ids',
                ],
            ]);
    }

    /**
     * API menolak penyelesaian materi jika latihan soal belum dijawab seluruhnya.
     */
    public function test_completion_rejected_if_questions_not_fully_answered(): void
    {
        $materi = Materi::factory()->create([
            'status' => 'aktif',
        ]);

        $soal1 = MateriSoal::factory()->create(['materi_id' => $materi->id]);
        $soal2 = MateriSoal::factory()->create(['materi_id' => $materi->id]);

        // Siswa hanya menjawab 1 dari 2 butir soal
        $response = $this->postJson("/api/materi/{$materi->id}/selesai", [
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
}
