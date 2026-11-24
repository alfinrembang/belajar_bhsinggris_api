<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UnifiedRombelFilterTest extends TestCase
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
     * Filter rombel spesifik (misal '10 DKV 1') pada Manajemen Materi hanya menampilkan
     * materi relevan untuk 10 DKV 1 dan mengecualikan Kelas 11, Kelas 12, dan rombel lain.
     */
    public function test_materi_filter_by_specific_rombel_excludes_other_classes(): void
    {
        $materiDKV = Materi::factory()->create([
            'judul' => 'Materi Khusus 10 DKV 1 Test',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1',
            'user_id' => $this->guru->id,
        ]);

        $materiTingkat10 = Materi::factory()->create([
            'judul' => 'Materi Umum Seluruh Kelas 10 Test',
            'tingkat_kelas' => '10',
            'target_rombel' => null,
            'user_id' => $this->guru->id,
        ]);

        $materiRPL = Materi::factory()->create([
            'judul' => 'Materi Khusus 10 RPL 1 Test',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 RPL 1',
            'user_id' => $this->guru->id,
        ]);

        $materiKelas11 = Materi::factory()->create([
            'judul' => 'Materi Tingkat 11 Test',
            'tingkat_kelas' => '11',
            'target_rombel' => null,
            'user_id' => $this->guru->id,
        ]);

        $materiSemua = Materi::factory()->create([
            'judul' => 'Materi Lintas Semua Kelas Test',
            'tingkat_kelas' => 'Semua Kelas',
            'target_rombel' => null,
            'user_id' => $this->guru->id,
        ]);

        $response = $this->actingAs($this->guru)->get('/materi?rombel=10+DKV+1');

        $response->assertStatus(200);
        $response->assertSee('Materi Khusus 10 DKV 1 Test');
        $response->assertSee('Materi Umum Seluruh Kelas 10 Test');
        $response->assertDontSee('Materi Khusus 10 RPL 1 Test');
        $response->assertDontSee('Materi Tingkat 11 Test');
        $response->assertDontSee('Materi Lintas Semua Kelas Test');
    }

    /**
     * Filter tingkat kelas ('tingkat:10') menampilkan seluruh materi kelas 10 dan mengecualikan kelas 11.
     */
    public function test_materi_filter_by_tingkat_kelas_shows_all_grade_10_materials(): void
    {
        $materiDKV = Materi::factory()->create([
            'judul' => 'Materi Tingkat 10 DKV Test',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1',
            'user_id' => $this->guru->id,
        ]);

        $materiKelas11 = Materi::factory()->create([
            'judul' => 'Materi Khusus Tingkat 11 Exclude Test',
            'tingkat_kelas' => '11',
            'target_rombel' => null,
            'user_id' => $this->guru->id,
        ]);

        $response = $this->actingAs($this->guru)->get('/materi?rombel=tingkat:10');

        $response->assertStatus(200);
        $response->assertSee('Materi Tingkat 10 DKV Test');
        $response->assertDontSee('Materi Khusus Tingkat 11 Exclude Test');
    }

    /**
     * Filter rombel spesifik pada Bank Soal & Kuis mengisolasi paket kuis sesuai rombel target.
     */
    public function test_quiz_filter_by_specific_rombel_isolates_correctly(): void
    {
        $quizDKV = Quiz::factory()->create([
            'judul' => 'Kuis Spesifik 10 DKV 1 Test',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1',
            'user_id' => $this->guru->id,
        ]);

        $quizKelas11 = Quiz::factory()->create([
            'judul' => 'Kuis Paket Tingkat 11 Test',
            'tingkat_kelas' => '11',
            'target_rombel' => null,
            'user_id' => $this->guru->id,
        ]);

        $response = $this->actingAs($this->guru)->get('/quiz?rombel=10+DKV+1');

        $response->assertStatus(200);
        $response->assertSee('Kuis Spesifik 10 DKV 1 Test');
        $response->assertDontSee('Kuis Paket Tingkat 11 Test');
    }

    /**
     * Filter rombel spesifik pada Modul Listening mengisolasi track audio listening sesuai rombel target.
     */
    public function test_listening_filter_by_specific_rombel_isolates_correctly(): void
    {
        $audioDKV = ModulListening::create([
            'judul' => 'Audio Percakapan 10 DKV 1 Test',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1',
            'status' => 'aktif',
            'user_id' => $this->guru->id,
        ]);

        $audioKelas11 = ModulListening::create([
            'judul' => 'Audio Percakapan Kelas 11 Test',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '11',
            'target_rombel' => null,
            'status' => 'aktif',
            'user_id' => $this->guru->id,
        ]);

        $response = $this->actingAs($this->guru)->get('/listening?rombel=10+DKV+1');

        $response->assertStatus(200);
        $response->assertSee('Audio Percakapan 10 DKV 1 Test');
        $response->assertDontSee('Audio Percakapan Kelas 11 Test');
    }
}
