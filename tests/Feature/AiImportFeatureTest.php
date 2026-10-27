<?php

namespace Tests\Feature;

use App\Jobs\ProcessAiDocumentJob;
use App\Models\AiImport;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AiImportFeatureTest extends TestCase
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

    public function test_unauthenticated_user_cannot_access_ai_import(): void
    {
        $response = $this->postJson(route('guru.ai-import.upload'));
        $response->assertStatus(401);
    }

    public function test_guru_can_upload_document_and_job_is_dispatched(): void
    {
        Queue::fake();
        Storage::fake('local');

        $file = UploadedFile::fake()->create('modul_grammar.docx', 1024, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($this->guru)->post(route('guru.ai-import.upload'), [
            'file' => $file,
            'tipe_target' => 'materi',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('ai_imports', [
            'user_id' => $this->guru->id,
            'nama_file_asli' => 'modul_grammar.docx',
            'tipe_target' => 'materi',
            'status' => 'pending',
        ]);

        Queue::assertPushed(ProcessAiDocumentJob::class);
    }

    public function test_guru_can_check_status_of_their_import(): void
    {
        $aiImport = AiImport::factory()->create([
            'user_id' => $this->guru->id,
            'status' => 'processing',
        ]);

        $response = $this->actingAs($this->guru)->getJson(route('guru.ai-import.status', $aiImport->id));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $aiImport->id,
            'status' => 'processing',
        ]);
    }

    public function test_guru_cannot_check_other_gurus_import(): void
    {
        $otherGuru = User::factory()->create(['role' => 'guru']);
        $aiImport = AiImport::factory()->create([
            'user_id' => $otherGuru->id,
        ]);

        $response = $this->actingAs($this->guru)->getJson(route('guru.ai-import.status', $aiImport->id));
        $response->assertStatus(403);
    }

    public function test_guru_can_view_review_page_when_completed(): void
    {
        $aiImport = AiImport::factory()->completed()->create([
            'user_id' => $this->guru->id,
            'tipe_target' => 'materi',
        ]);

        $response = $this->actingAs($this->guru)->get(route('guru.ai-import.review', $aiImport->id));
        $response->assertStatus(200);
        $response->assertSee('Kurasi Hasil Ekstraksi AI');
        $response->assertSee('Simple Present Tense');
    }

    public function test_guru_can_confirm_materi_from_ai_result(): void
    {
        $aiImport = AiImport::factory()->completed()->create([
            'user_id' => $this->guru->id,
            'tipe_target' => 'materi',
        ]);

        $payload = [
            'judul' => 'Simple Present Tense AI',
            'kategori' => 'Grammar',
            'tingkat_kelas' => 'Kelas 10',
            'target_rombel' => 'X-A',
            'deskripsi_singkat' => 'Materi tenses hasil ekstraksi AI.',
            'penjelasan' => 'Penjelasan rumus dasar.',
            'contoh_teks' => 'They work every day.',
            'xp_reward' => 50,
            'status' => 'aktif',
            'soals' => [
                [
                    'pertanyaan' => 'They ... football on Sunday.',
                    'pilihan_a' => 'plays',
                    'pilihan_b' => 'play',
                    'pilihan_c' => 'playing',
                    'pilihan_d' => 'played',
                    'kunci_jawaban' => 'B',
                    'pembahasan' => 'Subjek They menggunakan play.',
                ],
            ],
        ];

        $response = $this->actingAs($this->guru)->post(route('guru.ai-import.confirm-materi', $aiImport->id), $payload);

        $response->assertRedirect(route('guru.materi.index'));
        $this->assertDatabaseHas('materis', [
            'user_id' => $this->guru->id,
            'judul' => 'Simple Present Tense AI',
            'kategori' => 'Grammar',
        ]);

        $this->assertDatabaseHas('materi_soals', [
            'pertanyaan' => 'They ... football on Sunday.',
            'kunci_jawaban' => 'B',
        ]);
    }

    public function test_guru_can_confirm_quiz_from_ai_result(): void
    {
        $aiImport = AiImport::factory()->completed()->forQuiz()->create([
            'user_id' => $this->guru->id,
            'tipe_target' => 'quiz',
        ]);

        $payload = [
            'judul' => 'Kuis Grammar AI',
            'kategori' => 'Grammar',
            'tingkat_kelas' => 'Kelas 10',
            'durasi_menit' => 25,
            'deskripsi' => 'Kuis hasil ekstraksi AI.',
            'xp_reward' => 60,
            'status' => 'aktif',
            'soals' => [
                [
                    'tipe_soal' => 'multiple_choice',
                    'pertanyaan' => 'What is the capital of England?',
                    'pilihan_a' => 'Paris',
                    'pilihan_b' => 'London',
                    'pilihan_c' => 'Berlin',
                    'pilihan_d' => 'Rome',
                    'kunci_jawaban' => 'B',
                    'pembahasan' => 'London is the capital.',
                ],
            ],
        ];

        $response = $this->actingAs($this->guru)->post(route('guru.ai-import.confirm-quiz', $aiImport->id), $payload);

        $response->assertRedirect(route('guru.quiz.index'));
        $this->assertDatabaseHas('quizzes', [
            'user_id' => $this->guru->id,
            'judul' => 'Kuis Grammar AI',
        ]);

        $this->assertDatabaseHas('quiz_soals', [
            'pertanyaan' => 'What is the capital of England?',
            'kunci_jawaban' => 'B',
        ]);
    }
}
