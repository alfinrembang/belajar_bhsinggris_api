<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\PengaturanAkademik;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SiswaListeningProgres;
use App\Models\SiswaMateriProgres;
use App\Models\SiswaQuizProgres;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SiswaManagementTest extends TestCase
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
     * User yang belum login tidak dapat mengakses halaman manajemen siswa.
     */
    public function test_unauthenticated_user_cannot_access_siswa_management(): void
    {
        $response = $this->get('/siswa');
        $response->assertRedirect('/login');
    }

    /**
     * Guru dapat melihat halaman index siswa dan filter rombel.
     */
    public function test_guru_can_view_siswa_index_and_filter(): void
    {
        $siswaA = Siswa::factory()->create([
            'nama_lengkap' => 'Alfin Ramadhan',
            'kelas' => '12',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
        ]);

        $siswaB = Siswa::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'kelas' => '10',
            'jurusan' => 'DKV',
            'no_kelas' => '2',
        ]);

        $response = $this->actingAs($this->guru)->get('/siswa?kelas=12&jurusan=RPL');

        $response->assertStatus(200);
        $response->assertSee('Alfin Ramadhan');
        $response->assertDontSee('Budi Santoso');
    }

    /**
     * Guru dapat menambahkan akun siswa baru.
     */
    public function test_guru_can_create_new_siswa(): void
    {
        $payload = [
            'nama_lengkap' => 'Citra Lestari',
            'email' => 'citra.lestari@sekolah.test',
            'nis' => '192837',
            'nisn' => '0081928374',
            'kelas' => '11',
            'jurusan' => 'BD',
            'no_kelas' => '1',
            'no_absen' => '7',
            'password' => '', // kosong, otomatis default 12345678
        ];

        $response = $this->actingAs($this->guru)->post('/siswa/store', $payload);

        $response->assertRedirect(route('guru.siswa.index'));
        $this->assertDatabaseHas('siswas', [
            'email' => 'citra.lestari@sekolah.test',
            'nama_lengkap' => 'Citra Lestari',
            'kelas' => '11',
            'jurusan' => 'BD',
        ]);

        $created = Siswa::where('email', 'citra.lestari@sekolah.test')->first();
        $this->assertTrue(Hash::check('12345678', $created->password));
    }

    /**
     * Guru dapat melihat buku rapor & transkrip nilai detail siswa per modul.
     */
    public function test_guru_can_view_siswa_grade_transcript(): void
    {
        $siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Alfin Pratama',
            'kelas' => '12',
            'jurusan' => 'RPL',
        ]);

        // Buat materi, listening, kuis
        $materi = Materi::factory()->create([
            'judul' => 'Descriptive Text Landmark',
            'tingkat_kelas' => '12',
            'kategori' => 'Reading',
            'status' => 'aktif',
        ]);

        $listening = ModulListening::create([
            'judul' => 'Airport Announcement Listening',
            'deskripsi' => 'Deskripsi listening',
            'tingkat_kesulitan' => 'Intermediate',
            'status' => 'aktif',
        ]);

        $quiz = Quiz::create([
            'judul' => 'English Mid Exam 2026',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '12',
            'kode_room' => 'MID123',
            'durasi_menit' => 30,
            'xp_reward' => 100,
            'status' => 'aktif',
        ]);

        // Rekam progres belajar siswa
        SiswaMateriProgres::create([
            'siswa_id' => $siswa->id,
            'materi_id' => $materi->id,
            'status' => 'selesai',
            'skor' => 90,
            'xp_didapat' => 50,
            'completed_at' => now(),
        ]);

        SiswaListeningProgres::create([
            'siswa_id' => $siswa->id,
            'modul_listening_id' => $listening->id,
            'status' => 'selesai',
            'skor' => 85,
            'xp_didapat' => 60,
            'completed_at' => now(),
        ]);

        SiswaQuizProgres::create([
            'siswa_id' => $siswa->id,
            'quiz_id' => $quiz->id,
            'status' => 'selesai',
            'skor' => 95,
            'jumlah_benar' => 19,
            'total_soal' => 20,
            'xp_didapat' => 100,
            'waktu_pengerjaan_detik' => 1200,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->guru)->get("/siswa/{$siswa->id}");

        $response->assertStatus(200);
        $response->assertSee('Alfin Pratama');
        // Pastikan nilai spesifik per modul muncul
        $response->assertSee('Descriptive Text Landmark');
        $response->assertSee('90 / 100');
        $response->assertSee('Airport Announcement Listening');
        $response->assertSee('85 / 100');
        $response->assertSee('English Mid Exam 2026');
        $response->assertSee('95 / 100');
        $response->assertSee('ROOM: MID123');
    }

    /**
     * Guru dapat memperbarui data akun siswa.
     */
    public function test_guru_can_update_siswa(): void
    {
        $siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Nama Lama',
            'kelas' => '10',
            'jurusan' => 'TSM',
        ]);

        $payload = [
            'nama_lengkap' => 'Nama Baru Siswa',
            'email' => $siswa->email,
            'nis' => $siswa->nis,
            'nisn' => $siswa->nisn,
            'kelas' => '11',
            'jurusan' => 'TSM',
            'no_kelas' => '2',
            'no_absen' => '15',
        ];

        $response = $this->actingAs($this->guru)->post("/siswa/{$siswa->id}/update", $payload);

        $response->assertRedirect(route('guru.siswa.index'));
        $this->assertDatabaseHas('siswas', [
            'id' => $siswa->id,
            'nama_lengkap' => 'Nama Baru Siswa',
            'kelas' => '11',
            'no_kelas' => '2',
        ]);
    }

    /**
     * Guru dapat mereset kata sandi siswa ke default 12345678 secara instan.
     */
    public function test_guru_can_reset_siswa_password_instantly(): void
    {
        $siswa = Siswa::factory()->create([
            'password' => Hash::make('password_rahasia_lama'),
        ]);

        $response = $this->actingAs($this->guru)->post("/siswa/{$siswa->id}/reset-password");

        $response->assertStatus(302);
        $siswa->refresh();
        $this->assertTrue(Hash::check('12345678', $siswa->password));
    }

    /**
     * Guru dapat menghapus siswa beserta cascading progres belajarnya.
     */
    public function test_guru_can_delete_siswa(): void
    {
        $siswa = Siswa::factory()->create();

        $response = $this->actingAs($this->guru)->post("/siswa/{$siswa->id}/destroy");

        $response->assertRedirect(route('guru.siswa.index'));
        $this->assertDatabaseMissing('siswas', [
            'id' => $siswa->id,
        ]);
    }

    /**
     * Guru dapat membuka dan menutup sakelar periode kenaikan kelas.
     */
    public function test_guru_can_toggle_kenaikan_kelas_periode(): void
    {
        $setting = PengaturanAkademik::getActiveSetting();
        $this->assertFalse($setting->is_periode_kenaikan_aktif);

        // Buka periode
        $response = $this->actingAs($this->guru)->post('/siswa/toggle-kenaikan-kelas');
        $response->assertStatus(302);

        $setting->refresh();
        $this->assertTrue($setting->is_periode_kenaikan_aktif);
        $this->assertNotNull($setting->opened_at);

        // Tutup periode
        $response = $this->actingAs($this->guru)->post('/siswa/toggle-kenaikan-kelas');
        $response->assertStatus(302);

        $setting->refresh();
        $this->assertFalse($setting->is_periode_kenaikan_aktif);
        $this->assertNotNull($setting->closed_at);
    }

    /**
     * Siswa dapat memeriksa status akademik melalui endpoint API.
     */
    public function test_student_can_check_status_akademik_api(): void
    {
        $siswa = Siswa::factory()->create([
            'kelas' => '10',
            'jurusan' => 'TSM',
            'no_kelas' => '1',
            'tahun_ajaran_aktif' => '2025/2026',
        ]);

        $setting = PengaturanAkademik::getActiveSetting();
        $setting->update([
            'tahun_ajaran' => '2026/2027',
            'is_periode_kenaikan_aktif' => true,
        ]);

        $response = $this->getJson("/api/siswa/status-akademik?siswa_id={$siswa->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'is_periode_kenaikan_aktif' => true,
                    'tahun_ajaran' => '2026/2027',
                    'perlu_kenaikan_kelas' => true,
                ],
            ]);
    }

    /**
     * Siswa ditolak jika mencoba mengubah kelas saat periode kenaikan ditutup oleh guru.
     */
    public function test_student_cannot_change_rombel_when_promotion_period_is_closed(): void
    {
        $siswa = Siswa::factory()->create([
            'kelas' => '10',
            'jurusan' => 'TSM',
            'no_kelas' => '1',
            'no_absen' => '10',
        ]);

        $setting = PengaturanAkademik::getActiveSetting();
        $setting->update(['is_periode_kenaikan_aktif' => false]);

        $response = $this->postJson('/api/siswa/update-profil', [
            'siswa_id' => $siswa->id,
            'kelas' => '11',
            'no_kelas' => '2',
            'no_absen' => '12',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
            ]);

        $siswa->refresh();
        $this->assertEquals('10', $siswa->kelas);
        $this->assertEquals('1', $siswa->no_kelas);
    }

    /**
     * Siswa berhasil menaikkan kelas & memilih rombel acak saat periode kenaikan dibuka.
     */
    public function test_student_can_change_rombel_when_promotion_period_is_open(): void
    {
        $siswa = Siswa::factory()->create([
            'kelas' => '10',
            'jurusan' => 'TSM',
            'no_kelas' => '1',
            'no_absen' => '10',
            'tahun_ajaran_aktif' => '2025/2026',
        ]);

        $setting = PengaturanAkademik::getActiveSetting();
        $setting->update([
            'is_periode_kenaikan_aktif' => true,
            'tahun_ajaran' => '2026/2027',
        ]);

        $response = $this->postJson('/api/siswa/update-profil', [
            'siswa_id' => $siswa->id,
            'kelas' => '11',
            'no_kelas' => '3',
            'no_absen' => '25',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        $siswa->refresh();
        $this->assertEquals('11', $siswa->kelas);
        $this->assertEquals('3', $siswa->no_kelas);
        $this->assertEquals('25', $siswa->no_absen);
        $this->assertEquals('2026/2027', $siswa->tahun_ajaran_aktif);
    }

    /**
     * Riwayat belajar, skor, dan perolehan XP siswa tetap utuh setelah naik kelas.
     */
    public function test_student_learning_history_and_scores_preserved_after_promotion(): void
    {
        $siswa = Siswa::factory()->create([
            'kelas' => '10',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
            'tahun_ajaran_aktif' => '2025/2026',
        ]);

        $materi = Materi::factory()->create();
        SiswaMateriProgres::create([
            'siswa_id' => $siswa->id,
            'materi_id' => $materi->id,
            'skor' => 95,
            'status' => 'selesai',
            'xp_didapat' => 150,
        ]);

        $setting = PengaturanAkademik::getActiveSetting();
        $setting->update([
            'is_periode_kenaikan_aktif' => true,
            'tahun_ajaran' => '2026/2027',
        ]);

        // Siswa naik kelas dari 10 RPL 1 ke 11 RPL 2
        $this->postJson('/api/siswa/update-profil', [
            'siswa_id' => $siswa->id,
            'kelas' => '11',
            'no_kelas' => '2',
            'no_absen' => '5',
        ])->assertStatus(200);

        // Verifikasi relasi & skor tidak hilang sama sekali
        $siswa->refresh();
        $this->assertEquals('11', $siswa->kelas);
        $this->assertEquals(1, $siswa->materiProgres()->count());
        $this->assertEquals(95, $siswa->materiProgres()->first()->skor);
        $this->assertEquals(150, $siswa->materiProgres()->first()->xp_didapat);
    }
}
