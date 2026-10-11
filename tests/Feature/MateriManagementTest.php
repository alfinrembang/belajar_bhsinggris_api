<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\MateriSoal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MateriManagementTest extends TestCase
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
     * User yang belum login diarahkan ke halaman login.
     */
    public function test_unauthenticated_user_cannot_access_materi_management(): void
    {
        $response = $this->get('/materi');
        $response->assertRedirect('/login');
    }

    /**
     * Guru dapat melihat halaman index manajemen materi.
     */
    public function test_guru_can_view_materi_index(): void
    {
        $materi = Materi::factory()->create([
            'judul' => 'Grammar Simple Present Tense Test',
        ]);

        $response = $this->actingAs($this->guru)->get('/materi');

        $response->assertStatus(200);
        $response->assertSee('Grammar Simple Present Tense Test');
    }

    /**
     * Guru dapat membuat materi baru dengan soal pilgan dan listening serta file media.
     */
    public function test_guru_can_create_materi_with_pilgan_and_listening_soal(): void
    {
        Storage::fake('public');

        $payload = [
            'judul' => 'Expressing Congratulations & Compliments',
            'kategori' => 'Speaking & Listening',
            'tingkat_kelas' => '10',
            'deskripsi_singkat' => 'Pelajari cara memberi ucapan selamat.',
            'penjelasan' => 'Materi tentang congratulating others.',
            'contoh_teks' => 'Congratulations on your graduation!',
            'xp_reward' => 75,
            'status' => 'aktif',
            'gambar_materi' => UploadedFile::fake()->image('cover.jpg', 600, 400),
            'audio_materi' => UploadedFile::fake()->create('narasi.mp3', 500, 'audio/mpeg'),
            'soals' => [
                [
                    'tipe_soal' => 'pilgan',
                    'pertanyaan' => 'What do you say when someone wins a competition?',
                    'pilihan_a' => 'Congratulations!',
                    'pilihan_b' => 'I am sorry.',
                    'pilihan_c' => 'Goodbye.',
                    'pilihan_d' => 'Good night.',
                    'kunci_jawaban' => 'A',
                    'pembahasan' => 'Congratulations digunakan untuk memberi selamat.',
                ],
                [
                    'tipe_soal' => 'listening',
                    'pertanyaan' => 'Dengarkan dialog berikut:',
                    'audio_soal' => UploadedFile::fake()->create('listening_soal.mp3', 300, 'audio/mpeg'),
                    'pilihan_a' => 'She bought a car',
                    'pilihan_b' => 'She passed the exam',
                    'pilihan_c' => 'She got sick',
                    'pilihan_d' => 'She went home',
                    'kunci_jawaban' => 'B',
                    'pembahasan' => 'Di audio disebutkan bahwa dia lulus ujian.',
                ],
            ],
        ];

        $response = $this->actingAs($this->guru)->post('/materi/store', $payload);

        $response->assertRedirect(route('guru.materi.index'));
        $this->assertDatabaseHas('materis', [
            'judul' => 'Expressing Congratulations & Compliments',
            'tingkat_kelas' => '10',
            'xp_reward' => 75,
        ]);

        $createdMateri = Materi::where('judul', 'Expressing Congratulations & Compliments')->first();
        $this->assertNotNull($createdMateri);
        $this->assertCount(2, $createdMateri->soals);

        $this->assertDatabaseHas('materi_soals', [
            'materi_id' => $createdMateri->id,
            'tipe_soal' => 'pilgan',
            'kunci_jawaban' => 'A',
        ]);

        $this->assertDatabaseHas('materi_soals', [
            'materi_id' => $createdMateri->id,
            'tipe_soal' => 'listening',
            'kunci_jawaban' => 'B',
        ]);
    }

    /**
     * Validasi gagal jika kolom wajib dikosongkan.
     */
    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->actingAs($this->guru)->post('/materi/store', []);

        $response->assertSessionHasErrors(['judul', 'kategori', 'tingkat_kelas', 'penjelasan', 'status']);
    }

    /**
     * Validasi gagal jika format file audio tidak sesuai.
     */
    public function test_validation_fails_for_invalid_audio_file(): void
    {
        Storage::fake('public');

        $payload = [
            'judul' => 'Testing Invalid Audio Format',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '11',
            'penjelasan' => 'Penjelasan materi',
            'status' => 'aktif',
            'audio_materi' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ];

        $response = $this->actingAs($this->guru)->post('/materi/store', $payload);

        $response->assertSessionHasErrors(['audio_materi']);
    }

    /**
     * Guru dapat memperbarui materi dan mempertahankan audio soal lama.
     */
    public function test_guru_can_update_materi_and_retain_existing_audio(): void
    {
        Storage::fake('public');

        $fakeAudioPath = 'materi/soal_audio/sample_listening.mp3';
        Storage::disk('public')->put($fakeAudioPath, 'dummy-audio-content');

        $materi = Materi::factory()->create([
            'judul' => 'Judul Sebelum Update',
        ]);

        $soal = MateriSoal::factory()->create([
            'materi_id' => $materi->id,
            'tipe_soal' => 'listening',
            'audio_soal' => $fakeAudioPath,
        ]);

        $payload = [
            'judul' => 'Judul Setelah Update',
            'kategori' => $materi->kategori,
            'tingkat_kelas' => $materi->tingkat_kelas,
            'penjelasan' => 'Penjelasan baru yang lebih komprehensif.',
            'status' => 'aktif',
            'soals' => [
                [
                    'tipe_soal' => 'listening',
                    'pertanyaan' => 'Pertanyaan yang diperbarui',
                    'existing_audio_soal' => $fakeAudioPath,
                    'pilihan_a' => 'Pilihan A Baru',
                    'pilihan_b' => 'Pilihan B Baru',
                    'pilihan_c' => 'Pilihan C Baru',
                    'pilihan_d' => 'Pilihan D Baru',
                    'kunci_jawaban' => 'C',
                ],
            ],
        ];

        $response = $this->actingAs($this->guru)->post("/materi/{$materi->id}/update", $payload);

        $response->assertRedirect(route('guru.materi.index'));
        $this->assertDatabaseHas('materis', [
            'id' => $materi->id,
            'judul' => 'Judul Setelah Update',
        ]);

        $this->assertDatabaseHas('materi_soals', [
            'materi_id' => $materi->id,
            'tipe_soal' => 'listening',
            'audio_soal' => $fakeAudioPath,
            'kunci_jawaban' => 'C',
        ]);

        Storage::disk('public')->assertExists($fakeAudioPath);
    }

    /**
     * Guru dapat menghapus materi beserta relasi butir soalnya.
     */
    public function test_guru_can_delete_materi(): void
    {
        $materi = Materi::factory()->create();
        $soal = MateriSoal::factory()->create([
            'materi_id' => $materi->id,
        ]);

        $response = $this->actingAs($this->guru)->post("/materi/{$materi->id}/destroy");

        $response->assertRedirect(route('guru.materi.index'));
        $this->assertDatabaseMissing('materis', ['id' => $materi->id]);
        $this->assertDatabaseMissing('materi_soals', ['id' => $soal->id]);
    }
}
