<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizSoal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QuizManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected User $guru;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guru = User::factory()->create([
            'role' => 'guru',
        ]);
    }

    /**
     * User yang belum login tidak dapat mengakses halaman manajemen kuis.
     */
    public function test_unauthenticated_user_cannot_access_quiz_management(): void
    {
        $response = $this->get('/quiz');
        $response->assertRedirect('/login');
    }

    /**
     * Guru dapat melihat halaman index kuis beserta daftar paket kuis.
     */
    public function test_guru_can_view_quiz_index(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Grammar Quiz Tenses 101',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '10',
            'kode_room' => 'TEST10',
            'durasi_menit' => 15,
            'xp_reward' => 60,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->guru)->get('/quiz');

        $response->assertStatus(200);
        $response->assertSee('Grammar Quiz Tenses 101');
        $response->assertSee('TEST10');
    }

    /**
     * Guru dapat membuat kuis baru lengkap dengan butir soal.
     */
    public function test_guru_can_create_quiz_with_soals(): void
    {
        $uniqueRoom = 'CR'.rand(1000, 9999);
        $payload = [
            'judul' => 'Reading Comprehension Chapter 2',
            'kategori' => 'Reading',
            'tingkat_kelas' => '11',
            'kode_room' => $uniqueRoom,
            'durasi_menit' => 20,
            'xp_reward' => 70,
            'status' => 'aktif',
            'deskripsi' => 'Bacalah teks dengan seksama sebelum menjawab.',
            'soals' => [
                [
                    'tipe_soal' => 'reading',
                    'reading_passage' => 'The Komodo dragon is the largest extant species of lizard.',
                    'pertanyaan' => 'What is the Komodo dragon?',
                    'pilihan_a' => 'A large lizard species',
                    'pilihan_b' => 'A bird',
                    'pilihan_c' => 'A mammal',
                    'pilihan_d' => 'An amphibian',
                    'kunci_jawaban' => 'A',
                    'pembahasan' => 'Teks menyatakan komodo adalah spesies kadal terbesar.',
                ],
                [
                    'tipe_soal' => 'pilgan',
                    'pertanyaan' => 'Which word is a noun?',
                    'pilihan_a' => 'Quickly',
                    'pilihan_b' => 'Dragon',
                    'pilihan_c' => 'Beautiful',
                    'pilihan_d' => 'Run',
                    'kunci_jawaban' => 'B',
                    'pembahasan' => 'Dragon adalah kata benda.',
                ],
            ],
        ];

        $response = $this->actingAs($this->guru)->post('/quiz/store', $payload);

        $response->assertRedirect('/quiz');
        $this->assertDatabaseHas('quizzes', [
            'judul' => 'Reading Comprehension Chapter 2',
            'kode_room' => $uniqueRoom,
        ]);

        $quiz = Quiz::where('kode_room', $uniqueRoom)->first();
        $this->assertNotNull($quiz);
        $this->assertEquals(2, $quiz->soals()->count());
    }

    /**
     * Guru dapat melihat detail paket kuis dan butir soalnya.
     */
    public function test_guru_can_view_quiz_show(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Vocabulary Challenge Live',
            'kategori' => 'Vocabulary',
            'tingkat_kelas' => 'Semua Kelas',
            'kode_room' => 'VOCAB9',
            'durasi_menit' => 10,
            'xp_reward' => 50,
            'status' => 'aktif',
        ]);

        QuizSoal::create([
            'quiz_id' => $quiz->id,
            'nomor_soal' => 1,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'What is the synonym of "Huge"?',
            'pilihan_a' => 'Tiny',
            'pilihan_b' => 'Enormous',
            'pilihan_c' => 'Small',
            'pilihan_d' => 'Narrow',
            'kunci_jawaban' => 'B',
        ]);

        $response = $this->actingAs($this->guru)->get("/quiz/{$quiz->id}");

        $response->assertStatus(200);
        $response->assertSee('Vocabulary Challenge Live');
        $response->assertSee('VOCAB9');
        $response->assertSee('What is the synonym of "Huge"?');
    }

    /**
     * Guru dapat memperbarui kuis dan butir soalnya.
     */
    public function test_guru_can_update_quiz_and_soals(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Old Title Quiz',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '10',
            'kode_room' => 'OLD123',
            'durasi_menit' => 15,
            'xp_reward' => 50,
            'status' => 'draft',
        ]);

        $updatePayload = [
            'judul' => 'Updated Title Quiz',
            'kategori' => 'Speaking',
            'tingkat_kelas' => '12',
            'kode_room' => 'NEW123',
            'durasi_menit' => 25,
            'xp_reward' => 80,
            'status' => 'aktif',
            'soals' => [
                [
                    'tipe_soal' => 'pilgan',
                    'pertanyaan' => 'New question here?',
                    'pilihan_a' => 'Option 1',
                    'pilihan_b' => 'Option 2',
                    'pilihan_c' => 'Option 3',
                    'pilihan_d' => 'Option 4',
                    'kunci_jawaban' => 'C',
                ],
            ],
        ];

        $response = $this->actingAs($this->guru)->post("/quiz/{$quiz->id}/update", $updatePayload);

        $response->assertRedirect('/quiz');
        $this->assertDatabaseHas('quizzes', [
            'id' => $quiz->id,
            'judul' => 'Updated Title Quiz',
            'kode_room' => 'NEW123',
            'status' => 'aktif',
        ]);
    }

    /**
     * Guru dapat menghapus kuis beserta seluruh butir soalnya.
     */
    public function test_guru_can_delete_quiz(): void
    {
        $quiz = Quiz::create([
            'judul' => 'Quiz to Delete',
            'kategori' => 'General',
            'tingkat_kelas' => '10',
            'kode_room' => 'DEL100',
            'durasi_menit' => 10,
            'xp_reward' => 50,
            'status' => 'aktif',
        ]);

        QuizSoal::create([
            'quiz_id' => $quiz->id,
            'nomor_soal' => 1,
            'tipe_soal' => 'pilgan',
            'pertanyaan' => 'Question to delete',
            'pilihan_a' => 'A',
            'pilihan_b' => 'B',
            'pilihan_c' => 'C',
            'pilihan_d' => 'D',
            'kunci_jawaban' => 'A',
        ]);

        $response = $this->actingAs($this->guru)->post("/quiz/{$quiz->id}/destroy");

        $response->assertRedirect('/quiz');
        $this->assertDatabaseMissing('quizzes', ['id' => $quiz->id]);
        $this->assertDatabaseMissing('quiz_soals', ['quiz_id' => $quiz->id]);
    }
}
