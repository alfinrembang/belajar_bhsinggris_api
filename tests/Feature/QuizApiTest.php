<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizSoal;
use App\Models\Siswa;
use App\Models\SiswaQuizProgres;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuizApiTest extends TestCase
{
    use DatabaseTransactions;

    protected Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->siswa = Siswa::create([
            'nama_lengkap' => 'Ahmad Siswa Test',
            'email' => 'ahmad_quiz_'.uniqid().'@gmail.com',
            'password' => bcrypt('password123'),
            'kelas' => '10',
        ]);
    }

    /**
     * API dapat mengambil daftar paket kuis aktif.
     */
    public function test_api_can_list_active_quizzes(): void
    {
        $code = 'AP'.rand(1000, 9999);
        Quiz::create([
            'judul' => 'Grammar Challenge 1',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '10',
            'kode_room' => $code,
            'durasi_menit' => 15,
            'xp_reward' => 60,
            'status' => 'aktif',
        ]);

        $response = $this->getJson('/api/quiz?kelas=10');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonFragment(['judul' => 'Grammar Challenge 1'])
            ->assertJsonFragment(['kode_room' => $code]);
    }

    /**
     * Siswa dapat menemukan room kuis menggunakan kode room unik 6 digit.
     */
    public function test_api_can_join_room_by_valid_code(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Live Exam RPL 1',
            'kategori' => 'General',
            'tingkat_kelas' => '10',
            'kode_room' => 'RPL10A',
            'durasi_menit' => 20,
            'xp_reward' => 70,
            'status' => 'aktif',
        ]);

        $response = $this->postJson('/api/quiz/join-room', [
            'kode_room' => 'rpl10a', // Test case-insensitive
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.id', $quiz->id)
            ->assertJsonPath('data.kode_room', 'RPL10A');
    }

    /**
     * API mengembalikan 404 jika kode room salah atau tidak ada.
     */
    public function test_api_join_room_fails_for_invalid_code(): void
    {
        $response = $this->postJson('/api/quiz/join-room', [
            'kode_room' => 'NONEXIST',
        ]);

        $response->assertStatus(404)
            ->assertJsonPath('status', 'error');
    }

    /**
     * Detail kuis dikirimkan ke aplikasi tanpa membocorkan kunci jawaban saat pengerjaan.
     */
    public function test_api_can_get_quiz_detail_without_answer_keys(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Vocabulary Master',
            'kategori' => 'Vocabulary',
            'tingkat_kelas' => '10',
            'kode_room' => 'VOC10B',
            'durasi_menit' => 15,
            'xp_reward' => 60,
            'status' => 'aktif',
        ]);

        QuizSoal::create([
            'quiz_id' => $quiz->id,
            'nomor_soal' => 1,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'What is the opposite of "Ancient"?',
            'pilihan_a' => 'Modern',
            'pilihan_b' => 'Old',
            'pilihan_c' => 'Past',
            'pilihan_d' => 'Antique',
            'kunci_jawaban' => 'A',
            'pembahasan' => 'Modern adalah antonim dari ancient.',
        ]);

        $response = $this->getJson("/api/quiz/{$quiz->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.soals.0.pertanyaan', 'What is the opposite of "Ancient"?')
            ->assertJsonMissing(['kunci_jawaban' => 'A'])
            ->assertJsonMissing(['pembahasan' => 'Modern adalah antonim dari ancient.']);
    }

    /**
     * Anti-Cheat: API menolak submit jika ada butir soal kuis yang belum dijawab.
     */
    public function test_api_rejects_submit_if_not_all_questions_answered(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Strict Completion Quiz',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '10',
            'kode_room' => 'STRIC1',
            'durasi_menit' => 15,
            'xp_reward' => 60,
            'status' => 'aktif',
        ]);

        $soal1 = QuizSoal::create([
            'quiz_id' => $quiz->id,
            'nomor_soal' => 1,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'Question 1',
            'pilihan_a' => 'A',
            'pilihan_b' => 'B',
            'pilihan_c' => 'C',
            'pilihan_d' => 'D',
            'kunci_jawaban' => 'A',
        ]);

        $soal2 = QuizSoal::create([
            'quiz_id' => $quiz->id,
            'nomor_soal' => 2,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'Question 2',
            'pilihan_a' => 'A',
            'pilihan_b' => 'B',
            'pilihan_c' => 'C',
            'pilihan_d' => 'D',
            'kunci_jawaban' => 'B',
        ]);

        // Siswa hanya menjawab soal 1, meninggalkan soal 2 kosong
        $payload = [
            'siswa_id' => $this->siswa->id,
            'jawaban' => [
                $soal1->id => 'A',
            ],
        ];

        $response = $this->postJson("/api/quiz/{$quiz->id}/submit", $payload);

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonFragment([
                'message' => 'Seluruh 2 butir soal kuis wajib dijawab sebelum dikirimkan!',
            ]);
    }

    /**
     * API berhasil memproses jawaban lengkap dan menghitung nilai di sisi server.
     */
    public function test_api_successfully_submits_and_calculates_score_server_side(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Full Submit Quiz',
            'kategori' => 'Reading',
            'tingkat_kelas' => '10',
            'kode_room' => 'FULL01',
            'durasi_menit' => 15,
            'xp_reward' => 100,
            'status' => 'aktif',
        ]);

        $soal1 = QuizSoal::create([
            'quiz_id' => $quiz->id,
            'nomor_soal' => 1,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'Question 1',
            'pilihan_a' => 'Opt A',
            'pilihan_b' => 'Opt B',
            'pilihan_c' => 'Opt C',
            'pilihan_d' => 'Opt D',
            'kunci_jawaban' => 'A',
        ]);

        $soal2 = QuizSoal::create([
            'quiz_id' => $quiz->id,
            'nomor_soal' => 2,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'Question 2',
            'pilihan_a' => 'Opt A',
            'pilihan_b' => 'Opt B',
            'pilihan_c' => 'Opt C',
            'pilihan_d' => 'Opt D',
            'kunci_jawaban' => 'B',
        ]);

        // Siswa menjawab 1 benar (soal 1 = A) dan 1 salah (soal 2 = C) -> Skor 50
        $payload = [
            'siswa_id' => $this->siswa->id,
            'jawaban' => [
                $soal1->id => 'A',
                $soal2->id => 'C',
            ],
            'waktu_pengerjaan_detik' => 180,
        ];

        $response = $this->postJson("/api/quiz/{$quiz->id}/submit", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.skor', 50)
            ->assertJsonPath('data.jumlah_benar', 1)
            ->assertJsonPath('data.jumlah_salah', 1)
            ->assertJsonPath('data.total_soal', 2);

        $this->assertDatabaseHas('siswa_quiz_progres', [
            'siswa_id' => $this->siswa->id,
            'quiz_id' => $quiz->id,
            'skor' => 50,
            'status' => 'selesai',
        ]);
    }

    /**
     * API dapat mengambil riwayat pengerjaan kuis siswa.
     */
    public function test_api_can_get_student_quiz_progres(): void
    {
        $quiz = Quiz::create([
            'judul' => 'History Quiz Track',
            'kategori' => 'General',
            'tingkat_kelas' => '10',
            'kode_room' => 'HIST10',
            'durasi_menit' => 15,
            'xp_reward' => 50,
            'status' => 'aktif',
        ]);

        SiswaQuizProgres::create([
            'siswa_id' => $this->siswa->id,
            'quiz_id' => $quiz->id,
            'skor' => 85,
            'jumlah_benar' => 8,
            'total_soal' => 10,
            'xp_didapat' => 45,
            'status' => 'selesai',
            'completed_at' => now(),
        ]);

        $response = $this->getJson("/api/quiz/progres?siswa_id={$this->siswa->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.0.judul_quiz', 'History Quiz Track')
            ->assertJsonPath('data.0.skor', 85);
    }
}
