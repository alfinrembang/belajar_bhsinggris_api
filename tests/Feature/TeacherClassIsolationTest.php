<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TeacherClassIsolationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected User $guruA;

    protected User $guruB;

    protected Siswa $siswaDKV;

    protected Siswa $siswaRPL;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Utama',
            'email' => 'admin_iso_'.uniqid().'@mail.com',
            'password' => Hash::make('password123'),
        ]);

        $this->guruA = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru A - DKV',
            'email' => 'gurua_'.uniqid().'@mail.com',
            'password' => Hash::make('password123'),
        ]);

        $this->guruB = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru B - RPL',
            'email' => 'gurub_'.uniqid().'@mail.com',
            'password' => Hash::make('password123'),
        ]);

        $this->siswaDKV = Siswa::factory()->create([
            'kelas' => '10',
            'jurusan' => 'DKV',
            'no_kelas' => '1',
            'nama_lengkap' => 'Siswa 10 DKV 1',
            'email' => 'siswadkv_'.uniqid().'@mail.com',
        ]);

        $this->siswaRPL = Siswa::factory()->create([
            'kelas' => '10',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
            'nama_lengkap' => 'Siswa 10 RPL 1',
            'email' => 'siswarpl_'.uniqid().'@mail.com',
        ]);
    }

    /**
     * Admin mendaftarkan dan mengedit akun guru tanpa pilihan kelas_diampu dan tanpa rombel dummy.
     */
    public function test_admin_manages_guru_without_kelas_diampu(): void
    {
        $newEmail = 'guru_ajar_'.uniqid().'@mail.com';

        $response = $this->actingAs($this->admin)->post(route('admin.guru.store'), [
            'name' => 'Guru Baru Bahasa Inggris',
            'email' => $newEmail,
            'nip' => '19876543210',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => $newEmail,
            'name' => 'Guru Baru Bahasa Inggris',
            'role' => 'guru',
        ]);

        $dashboardResponse = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertDontSee('Kelas Diampu');
        $dashboardResponse->assertDontSee('kelas_diampu[]');
    }

    /**
     * Guru A di web portal hanya dapat melihat materi buatannya sendiri, bukan materi Guru B.
     */
    public function test_guru_only_sees_own_materi_on_web_index(): void
    {
        $materiA = Materi::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Materi Unik Guru A',
            'tingkat_kelas' => '10',
        ]);

        Materi::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Materi Rahasia Guru B',
            'tingkat_kelas' => '10',
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.materi.index'));

        $response->assertStatus(200);
        $response->assertSee($materiA->judul);
        $response->assertDontSee('Materi Rahasia Guru B');
    }

    /**
     * Guru A ditolak dengan kode 403 saat mencoba melihat, mengedit, atau menghapus materi milik Guru B.
     */
    public function test_guru_cannot_edit_or_delete_other_guru_materi(): void
    {
        $materiB = Materi::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Materi Spesifik Guru B',
            'tingkat_kelas' => '10',
        ]);

        $this->actingAs($this->guruA)
            ->get(route('guru.materi.show', $materiB))
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->get(route('guru.materi.edit', $materiB))
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->put(route('guru.materi.update', $materiB), [
                'judul' => 'Materi Diubah Ilegal',
                'kategori' => 'Grammar',
                'tingkat_kelas' => '10',
                'penjelasan' => 'Penjelasan baru',
                'status' => 'aktif',
            ])
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->delete(route('guru.materi.destroy', $materiB))
            ->assertStatus(403);

        $this->assertDatabaseHas('materis', ['id' => $materiB->id]);
    }

    /**
     * Guru A ditolak dengan kode 403 saat mencoba mengedit atau menghapus kuis dan listening milik Guru B.
     */
    public function test_guru_cannot_edit_or_delete_other_guru_quiz_and_listening(): void
    {
        $quizB = Quiz::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Kuis Spesifik Guru B',
            'tingkat_kelas' => '10',
        ]);

        $listeningB = ModulListening::create([
            'user_id' => $this->guruB->id,
            'judul' => 'Listening Eksklusif Guru B',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        $this->actingAs($this->guruA)
            ->get(route('guru.quiz.edit', $quizB))
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->delete(route('guru.quiz.destroy', $quizB))
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->get(route('guru.listening.edit', $listeningB))
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->delete(route('guru.listening.destroy', $listeningB))
            ->assertStatus(403);
    }

    /**
     * Saat Guru membuat materi baru dengan multi-rombel, target_rombel dan user_id tersimpan akurat.
     */
    public function test_guru_stores_materi_with_user_id_and_multi_target_rombel(): void
    {
        $response = $this->actingAs($this->guruA)->post(route('guru.materi.store'), [
            'judul' => 'Materi Multi Rombel Guru A',
            'kategori' => 'Grammar',
            'target_rombel' => '__multi__',
            'target_rombels' => ['10 DKV 1', '10 DKV 2'],
            'penjelasan' => 'Materi khusus 10 DKV 1 dan 10 DKV 2',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('guru.materi.index'));
        $this->assertDatabaseHas('materis', [
            'judul' => 'Materi Multi Rombel Guru A',
            'user_id' => $this->guruA->id,
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1, 10 DKV 2',
        ]);
    }

    /**
     * API Siswa: Konten target rombel terisolasi ketat di HP siswa dan mendukung multi-rombel serta rombel tanpa nomor kelas.
     */
    public function test_student_api_isolates_materi_by_target_rombel_strictly(): void
    {
        $siswaRPLNoNum = Siswa::factory()->create([
            'kelas' => '12',
            'jurusan' => 'RPL',
            'no_kelas' => null,
            'nama_lengkap' => 'Siswa 12 RPL Tanpa Nomor',
        ]);

        $siswaRPL1 = Siswa::factory()->create([
            'kelas' => '12',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
            'nama_lengkap' => 'Siswa 12 RPL 1',
        ]);

        $materiMultiDKV = Materi::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Materi Multi DKV 1 & 2',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1, 10 DKV 2',
            'status' => 'aktif',
        ]);

        $materiRPL = Materi::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Materi Khusus 10 RPL 1',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 RPL 1',
            'status' => 'aktif',
        ]);

        $materi12Rpl = Materi::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Materi Khusus 12 RPL Tanpa Nomor',
            'tingkat_kelas' => '12',
            'target_rombel' => '12 RPL',
            'status' => 'aktif',
        ]);

        // Siswa 10 DKV 1 melihat materi multi DKV, tidak melihat materi 10 RPL 1
        $resDKV = $this->getJson("/api/materi?siswa_id={$this->siswaDKV->id}");
        $idsDKV = collect($resDKV->json('data'))->pluck('id')->all();
        $this->assertContains($materiMultiDKV->id, $idsDKV);
        $this->assertNotContains($materiRPL->id, $idsDKV);

        // Siswa 10 RPL 1 melihat materi 10 RPL 1, tidak melihat materi multi DKV
        $resRPL = $this->getJson("/api/materi?siswa_id={$this->siswaRPL->id}");
        $idsRPL = collect($resRPL->json('data'))->pluck('id')->all();
        $this->assertContains($materiRPL->id, $idsRPL);
        $this->assertNotContains($materiMultiDKV->id, $idsRPL);

        // Siswa 12 RPL melihat materi '12 RPL', tetapi Siswa 12 RPL 1 TIDAK bocor melihatnya
        $res12Rpl = $this->getJson("/api/materi?siswa_id={$siswaRPLNoNum->id}");
        $this->assertContains($materi12Rpl->id, collect($res12Rpl->json('data'))->pluck('id')->all());

        $res12Rpl1 = $this->getJson("/api/materi?siswa_id={$siswaRPL1->id}");
        $this->assertNotContains($materi12Rpl->id, collect($res12Rpl1->json('data'))->pluck('id')->all());
    }

    /**
     * API Siswa: Listening dan Quiz juga terisolasi ketat berdasarkan target_rombel di HP siswa.
     */
    public function test_student_api_isolates_listening_and_quiz_by_target_rombel(): void
    {
        $listeningDKV = ModulListening::create([
            'user_id' => $this->guruA->id,
            'judul' => 'Listening DKV',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1',
            'status' => 'aktif',
        ]);

        $listeningRPL = ModulListening::create([
            'user_id' => $this->guruB->id,
            'judul' => 'Listening RPL',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 RPL 1',
            'status' => 'aktif',
        ]);

        $quizDKV = Quiz::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Quiz DKV',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1',
            'status' => 'aktif',
        ]);

        $quizRPL = Quiz::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Quiz RPL',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 RPL 1',
            'status' => 'aktif',
        ]);

        $resListening = $this->getJson("/api/listening?siswa_id={$this->siswaDKV->id}");
        $listeningIds = collect($resListening->json('data'))->pluck('id')->all();
        $this->assertContains($listeningDKV->id, $listeningIds);
        $this->assertNotContains($listeningRPL->id, $listeningIds);

        $resQuiz = $this->getJson("/api/quiz?siswa_id={$this->siswaDKV->id}");
        $quizIds = collect($resQuiz->json('data'))->pluck('id')->all();
        $this->assertContains($quizDKV->id, $quizIds);
        $this->assertNotContains($quizRPL->id, $quizIds);
    }

    /**
     * Jika belum ada siswa sama sekali, dropdown target rombel kosong dan pembuatan konten ditolak validasi.
     */
    public function test_dropdown_is_empty_and_creation_is_blocked_when_no_students_exist(): void
    {
        Schema::disableForeignKeyConstraints();
        Siswa::query()->delete();
        Schema::enableForeignKeyConstraints();

        $createMateriView = $this->actingAs($this->guruA)->get(route('guru.materi.create'));
        $createMateriView->assertStatus(200);
        $createMateriView->assertSee('Belum ada siswa terdaftar di sistem');
        $createMateriView->assertDontSee('Semua Kelas (Umum');
        $createMateriView->assertDontSee('Khusus Jurusan');

        $postResponse = $this->actingAs($this->guruA)->post(route('guru.materi.store'), [
            'judul' => 'Materi Tanpa Siswa',
            'kategori' => 'Grammar',
            'target_rombel' => 'Semua Kelas',
            'penjelasan' => 'Isi penjelasan.',
            'status' => 'aktif',
        ]);

        $postResponse->assertSessionHasErrors(['target_rombel']);
    }

    /**
     * Materi umum sekolah ('Semua Kelas') tetap dapat diakses oleh seluruh siswa.
     */
    public function test_common_school_material_is_available_to_all_students(): void
    {
        $materiUmum = Materi::factory()->create([
            'user_id' => null,
            'judul' => 'Materi Umum Sekolah untuk Semua',
            'tingkat_kelas' => 'Semua Kelas',
            'target_rombel' => null,
            'status' => 'aktif',
        ]);

        $materiGuruUmum = Materi::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Materi Bersama Semua Kelas',
            'tingkat_kelas' => 'Semua Kelas',
            'target_rombel' => null,
            'status' => 'aktif',
        ]);

        $responseDKV = $this->getJson("/api/materi?siswa_id={$this->siswaDKV->id}");
        $idsDKV = collect($responseDKV->json('data'))->pluck('id')->all();
        $this->assertContains($materiUmum->id, $idsDKV);
        $this->assertContains($materiGuruUmum->id, $idsDKV);

        $responseRPL = $this->getJson("/api/materi?siswa_id={$this->siswaRPL->id}");
        $idsRPL = collect($responseRPL->json('data'))->pluck('id')->all();
        $this->assertContains($materiUmum->id, $idsRPL);
        $this->assertContains($materiGuruUmum->id, $idsRPL);
    }
}
