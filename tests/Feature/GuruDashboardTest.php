<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SiswaMateriProgres;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GuruDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected User $guru;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guru = User::factory()->create([
            'role' => 'guru',
            'name' => 'Bambang Pamungkas, S.Pd.',
        ]);
    }

    /**
     * Guru dapat mengakses dashboard utama yang memuat ringkasan modul lengkap.
     */
    public function test_guru_can_view_comprehensive_dashboard_summary(): void
    {
        // Siapkan data siswa di berbagai jenjang kelas
        Siswa::factory()->create(['kelas' => '10', 'nama_lengkap' => 'Andi Kelas Sepuluh']);
        Siswa::factory()->create(['kelas' => '11', 'nama_lengkap' => 'Budi Kelas Sebelas']);
        $siswaTop = Siswa::factory()->create(['kelas' => '12', 'nama_lengkap' => 'Citra Siswa Unggul']);

        // Siapkan modul pembelajaran
        $materi = Materi::factory()->create(['judul' => 'Simple Present Tense Master']);
        ModulListening::create([
            'judul' => 'Daily Conversation Listening',
            'deskripsi' => 'Latihan percakapan',
            'tingkat_kesulitan' => 'Beginner',
            'status' => 'aktif',
        ]);
        Quiz::create([
            'judul' => 'Grammar Pop Quiz 1',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '12',
            'kode_room' => 'POP01',
            'durasi_menit' => 15,
            'xp_reward' => 100,
            'status' => 'aktif',
        ]);

        // Rekam aktivitas siswa top
        SiswaMateriProgres::create([
            'siswa_id' => $siswaTop->id,
            'materi_id' => $materi->id,
            'status' => 'selesai',
            'skor' => 95,
            'xp_didapat' => 50,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->guru)->get(route('guru.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Bambang Pamungkas, S.Pd.');
        $response->assertSee('Total Siswa Terdaftar');
        $response->assertSee('Modul Bahan Ajar');
        $response->assertSee('Modul Audio Listening');
        $response->assertSee('Paket Kuis');
        $response->assertSee('Rerata Nilai Siswa');
        $response->assertSee('Modul Pembelajaran Terkini');
        $response->assertSee('Siswa Paling Unggul');
        $response->assertSee('Citra Siswa Unggul');
        $response->assertSee('Pintasan Akses Menu');
    }

    /**
     * User yang belum login dialihkan ke form login.
     */
    public function test_guest_is_redirected_to_login_page(): void
    {
        $response = $this->get(route('guru.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
