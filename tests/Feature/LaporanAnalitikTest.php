<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SiswaListeningProgres;
use App\Models\SiswaMateriProgres;
use App\Models\SiswaQuizProgres;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LaporanAnalitikTest extends TestCase
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
     * Tamu / user tanpa login tidak dapat mengakses halaman laporan & analitik.
     */
    public function test_unauthenticated_user_cannot_access_laporan(): void
    {
        $response = $this->get('/laporan');
        $response->assertRedirect('/login');
    }

    /**
     * Guru dapat melihat halaman laporan analitik beserta metrik ringkasannya.
     */
    public function test_guru_can_view_laporan_analitik_dashboard(): void
    {
        $siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'kelas' => '10',
            'jurusan' => 'TSM',
            'no_kelas' => '1',
        ]);

        $response = $this->actingAs($this->guru)->get('/laporan');

        $response->assertStatus(200);
        $response->assertSee('Laporan & Analitik Belajar');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Leger Rekapitulasi Nilai Kolektif');
    }

    /**
     * Halaman laporan menghitung bobot nilai akhir (30% Materi + 30% Listening + 40% Kuis) dan batas KKM 75 secara tepat.
     */
    public function test_laporan_calculates_correct_module_scores_and_kkm(): void
    {
        $siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Siswa Tuntas Cerdas',
            'kelas' => '11',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
        ]);

        $materi = Materi::factory()->create();
        $listening = ModulListening::create([
            'judul' => 'Daily Conversation Practice',
            'deskripsi' => 'Latihan percakapan',
            'tingkat_kesulitan' => 'Beginner',
            'xp_reward' => 50,
            'status' => 'aktif',
        ]);
        $quiz = Quiz::factory()->create();

        // Materi = 80, Listening = 90, Quiz = 70
        // Nilai Akhir = (80 * 0.3) + (90 * 0.3) + (70 * 0.4) = 24 + 27 + 28 = 79.0 (>= 75 -> Tuntas)
        SiswaMateriProgres::create([
            'siswa_id' => $siswa->id,
            'materi_id' => $materi->id,
            'skor' => 80,
            'status' => 'selesai',
            'xp_didapat' => 100,
        ]);

        SiswaListeningProgres::create([
            'siswa_id' => $siswa->id,
            'modul_listening_id' => $listening->id,
            'skor' => 90,
            'status' => 'selesai',
            'xp_didapat' => 100,
        ]);

        SiswaQuizProgres::create([
            'siswa_id' => $siswa->id,
            'quiz_id' => $quiz->id,
            'skor' => 70,
            'status' => 'selesai',
            'xp_didapat' => 100,
        ]);

        $response = $this->actingAs($this->guru)->get('/laporan');

        $response->assertStatus(200);
        $response->assertSee('Siswa Tuntas Cerdas');
        $response->assertSee('79');
        $response->assertSee('Tuntas');
    }

    /**
     * Siswa dengan nilai di bawah KKM 75 otomatis terdeteksi sebagai remedial.
     */
    public function test_laporan_identifies_remedial_student(): void
    {
        $siswa = Siswa::factory()->create([
            'nama_lengkap' => 'Siswa Butuh Remedial',
            'kelas' => '10',
            'jurusan' => 'DKV',
            'no_kelas' => '2',
        ]);

        $quiz = Quiz::factory()->create();
        SiswaQuizProgres::create([
            'siswa_id' => $siswa->id,
            'quiz_id' => $quiz->id,
            'skor' => 50,
            'status' => 'selesai',
            'xp_didapat' => 50,
        ]);

        // Nilai akhir = 50 * 0.4 = 20 (< 75 -> Remedial)
        $response = $this->actingAs($this->guru)->get('/laporan?status=remedial');

        $response->assertStatus(200);
        $response->assertSee('Siswa Butuh Remedial');
        $response->assertSee('Remedial');
    }

    /**
     * Guru dapat mengekspor rekapitulasi nilai kolektif dalam format CSV (Excel-ready).
     */
    public function test_guru_can_export_csv_rekap_nilai(): void
    {
        Siswa::factory()->create([
            'nama_lengkap' => 'Alfin Ekspor Data',
            'nis' => '889900',
        ]);

        $response = $this->actingAs($this->guru)->get('/laporan/export-csv');

        $response->assertStatus(200);
        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename="rekap_nilai_siswa_', $response->headers->get('Content-Disposition') ?? '');
    }

    /**
     * Guru dapat mengekspor rekapitulasi nilai kolektif dalam format Excel (.xlsx) resmi.
     */
    public function test_guru_can_export_excel_rekap_nilai(): void
    {
        Siswa::factory()->create([
            'nama_lengkap' => 'Budi Ekspor Excel',
            'nis' => '112233',
        ]);

        $response = $this->actingAs($this->guru)->get('/laporan/export-excel');

        $response->assertStatus(200);
        $this->assertStringContainsString('spreadsheetml.sheet', $response->headers->get('Content-Type') ?? '');
        $this->assertStringContainsString('attachment; filename=rekap_nilai_siswa_', $response->headers->get('Content-Disposition') ?? '');
        $this->assertStringEndsWith('.xlsx', $response->headers->get('Content-Disposition') ?? '');
    }

    /**
     * Guru dapat mengakses lembar cetak rapor leger resmi siap print.
     */
    public function test_guru_can_view_cetak_rapor_page(): void
    {
        Siswa::factory()->create([
            'nama_lengkap' => 'Dewi Pratiwi',
            'kelas' => '12',
            'jurusan' => 'RPL',
        ]);

        $response = $this->actingAs($this->guru)->get('/laporan/cetak');

        $response->assertStatus(200);
        $response->assertSee('LEGER REKAPITULASI PENILAIAN HASIL BELAJAR SISWA');
        $response->assertSee('Dewi Pratiwi');
        $response->assertSee('Guru Pengampu Bahasa Inggris');
    }
}
