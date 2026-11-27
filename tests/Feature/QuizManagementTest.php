<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizSoal;
use App\Models\Siswa;
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
        Siswa::factory()->create([
            'kelas' => '11',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
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
        $response->assertSee('Grammar');
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

    /**
     * Guru dapat menghapus banyak paket kuis sekaligus (bulk delete).
     */
    public function test_guru_can_bulk_delete_quizzes(): void
    {
        $quiz1 = Quiz::create([
            'judul' => 'Quiz Bulk 1',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '10',
            'kode_room' => 'BLK001',
            'durasi_menit' => 10,
            'xp_reward' => 50,
            'status' => 'aktif',
        ]);

        $quiz2 = Quiz::create([
            'judul' => 'Quiz Bulk 2',
            'kategori' => 'Reading',
            'tingkat_kelas' => '11',
            'kode_room' => 'BLK002',
            'durasi_menit' => 10,
            'xp_reward' => 50,
            'status' => 'aktif',
        ]);

        $quiz3 = Quiz::create([
            'judul' => 'Quiz Keep 3',
            'kategori' => 'Vocabulary',
            'tingkat_kelas' => '12',
            'kode_room' => 'BLK003',
            'durasi_menit' => 10,
            'xp_reward' => 50,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->guru)->delete('/quiz/bulk-destroy', [
            'quiz_ids' => [$quiz1->id, $quiz2->id],
        ]);

        $response->assertRedirect(route('guru.quiz.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('quizzes', ['id' => $quiz1->id]);
        $this->assertDatabaseMissing('quizzes', ['id' => $quiz2->id]);
        $this->assertDatabaseHas('quizzes', ['id' => $quiz3->id]);
    }

    /**
     * Guru dapat membuat, melihat pratinjau, dan mengedit soal tipe True/False (1 tabel 3 pernyataan).
     */
    public function test_guru_can_create_view_and_update_true_false_table_quiz(): void
    {
        $uniqueRoom = 'TF'.rand(1000, 9999);

        $response = $this->actingAs($this->guru)->post('/quiz/store', [
            'judul' => 'Kuis Pernyataan True False',
            'kategori' => 'Reading',
            'target_rombel' => 'tingkat:11',
            'kode_room' => $uniqueRoom,
            'durasi_menit' => 15,
            'xp_reward' => 60,
            'status' => 'aktif',
            'soals' => [
                [
                    'tipe_soal' => 'true_false',
                    'pertanyaan' => 'Determine whether each statement is True or False:',
                    'pernyataan_items' => [
                        ['teks' => 'Jakarta is the capital of Indonesia.', 'kunci' => 'true'],
                        ['teks' => 'Penguins can fly in the sky.', 'kunci' => 'false'],
                        ['teks' => 'Water freezes at zero degrees Celsius.', 'kunci' => 'true'],
                    ],
                    'pembahasan' => 'Perhatikan fakta umum pada masing-masing pernyataan.',
                ],
            ],
        ]);

        $response->assertRedirect('/quiz');

        $quiz = Quiz::where('kode_room', $uniqueRoom)->with('soals')->firstOrFail();
        $this->assertCount(1, $quiz->soals);

        $soal = $quiz->soals->first();
        $this->assertEquals('true_false', $soal->tipe_soal);
        $this->assertCount(3, $soal->pernyataan_items);
        $this->assertEquals('TRUE, FALSE, TRUE', $soal->kunci_jawaban);
        $this->assertNull($soal->pilihan_a);

        // Pastikan halaman detail menampilkan tabel 3 pernyataan
        $showRes = $this->actingAs($this->guru)->get(route('guru.quiz.show', $quiz));
        $showRes->assertStatus(200);
        $showRes->assertSee('True / False (Tabel Pernyataan)');
        $showRes->assertSee('Jakarta is the capital of Indonesia.');
        $showRes->assertSee('Penguins can fly in the sky.');

        // Pastikan halaman edit memuat 3 baris pernyataan
        $editRes = $this->actingAs($this->guru)->get(route('guru.quiz.edit', $quiz));
        $editRes->assertStatus(200);
        $editRes->assertSee('Water freezes at zero degrees Celsius.');

        // Update 3 pernyataan True / False
        $updateRes = $this->actingAs($this->guru)->post("/quiz/{$quiz->id}/update", [
            'judul' => 'Kuis Pernyataan True False Updated',
            'kategori' => 'Reading',
            'target_rombel' => 'tingkat:11',
            'kode_room' => $uniqueRoom,
            'durasi_menit' => 20,
            'status' => 'aktif',
            'soals' => [
                [
                    'tipe_soal' => 'true_false',
                    'pertanyaan' => 'Check the updated statements:',
                    'pernyataan_items' => [
                        ['teks' => 'The earth orbits the sun.', 'kunci' => 'true'],
                        ['teks' => 'Monday comes after Wednesday.', 'kunci' => 'false'],
                        ['teks' => 'Dogs are reptiles.', 'kunci' => 'false'],
                    ],
                ],
            ],
        ]);

        $updateRes->assertRedirect('/quiz');
        $updatedSoal = $quiz->fresh('soals')->soals->first();
        $this->assertNotNull($updatedSoal);
        $this->assertEquals('TRUE, FALSE, FALSE', $updatedSoal->kunci_jawaban);
        $this->assertEquals('The earth orbits the sun.', $updatedSoal->pernyataan_items[0]['teks']);
    }

    /**
     * Validasi menolak soal True/False jika judul diisi tetapi tabel pernyataan kosong semua.
     */
    public function test_validation_rejects_true_false_without_any_statement(): void
    {
        $response = $this->actingAs($this->guru)->post('/quiz/store', [
            'judul' => 'Kuis TF Kosong',
            'kategori' => 'Grammar',
            'target_rombel' => 'tingkat:11',
            'kode_room' => 'TFEMPTY',
            'durasi_menit' => 15,
            'status' => 'aktif',
            'soals' => [
                [
                    'tipe_soal' => 'true_false',
                    'pertanyaan' => 'Evaluate these statements:',
                    'pernyataan_items' => [
                        ['teks' => '', 'kunci' => 'true'],
                        ['teks' => '', 'kunci' => 'false'],
                        ['teks' => '', 'kunci' => 'true'],
                    ],
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['soals.0.pernyataan_items']);
    }
}
