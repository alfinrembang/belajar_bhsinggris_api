<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
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
            'kelas_diampu' => ['10 DKV 1'],
        ]);

        $this->guruB = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru B - RPL',
            'email' => 'gurub_'.uniqid().'@mail.com',
            'password' => Hash::make('password123'),
            'kelas_diampu' => ['10 RPL 1'],
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
     * Admin dapat menentukan kelas yang diampu guru saat membuat dan mengedit guru.
     */
    public function test_admin_can_assign_and_update_kelas_diampu_to_guru(): void
    {
        $newEmail = 'guru_ajar_'.uniqid().'@mail.com';

        // 1. Simpan Guru Baru beserta kelas_diampu
        $response = $this->actingAs($this->admin)->post(route('admin.guru.store'), [
            'name' => 'Guru Baru Penugasan',
            'email' => $newEmail,
            'nip' => '19876543210',
            'password' => 'secret123',
            'kelas_diampu' => ['10 DKV 1', '11 RPL 2'],
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => $newEmail,
            'name' => 'Guru Baru Penugasan',
        ]);

        $createdGuru = User::where('email', $newEmail)->firstOrFail();
        $this->assertEquals(['10 DKV 1', '11 RPL 2'], $createdGuru->kelas_diampu);

        // 2. Update kelas_diampu Guru
        $updateResponse = $this->actingAs($this->admin)->post(route('admin.guru.update', $createdGuru), [
            'name' => 'Guru Baru Penugasan Updated',
            'email' => $newEmail,
            'kelas_diampu' => ['12 RPL 1'],
        ]);

        $updateResponse->assertRedirect(route('admin.dashboard'));
        $createdGuru->refresh();
        $this->assertEquals(['12 RPL 1'], $createdGuru->kelas_diampu);
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

        $materiB = Materi::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Materi Rahasia Guru B',
            'tingkat_kelas' => '10',
        ]);

        $response = $this->actingAs($this->guruA)->get(route('guru.materi.index'));

        $response->assertStatus(200);
        $response->assertSee('Materi Unik Guru A');
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

        // 1. Coba show
        $this->actingAs($this->guruA)
            ->get(route('guru.materi.show', $materiB))
            ->assertStatus(403);

        // 2. Coba edit
        $this->actingAs($this->guruA)
            ->get(route('guru.materi.edit', $materiB))
            ->assertStatus(403);

        // 3. Coba update
        $this->actingAs($this->guruA)
            ->put(route('guru.materi.update', $materiB), [
                'judul' => 'Materi Diubah Ilegal',
                'kategori' => 'Grammar',
                'tingkat_kelas' => '10',
                'penjelasan' => 'Penjelasan baru',
                'status' => 'aktif',
            ])
            ->assertStatus(403);

        // 4. Coba delete
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

        // Cek Quiz
        $this->actingAs($this->guruA)
            ->get(route('guru.quiz.edit', $quizB))
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->delete(route('guru.quiz.destroy', $quizB))
            ->assertStatus(403);

        // Cek Listening
        $this->actingAs($this->guruA)
            ->get(route('guru.listening.edit', $listeningB))
            ->assertStatus(403);

        $this->actingAs($this->guruA)
            ->delete(route('guru.listening.destroy', $listeningB))
            ->assertStatus(403);
    }

    /**
     * Saat Guru membuat materi baru, user_id dan target_rombel tercatat otomatis di database.
     */
    public function test_guru_stores_materi_with_user_id_and_target_rombel(): void
    {
        $response = $this->actingAs($this->guruA)->post(route('guru.materi.store'), [
            'judul' => 'Materi Baru Guru A dengan Rombel',
            'kategori' => 'Grammar',
            'tingkat_kelas' => '10',
            'target_rombel' => '10 DKV 1',
            'penjelasan' => 'Materi khusus siswa 10 DKV 1',
            'status' => 'aktif',
        ]);

        $response->assertRedirect(route('guru.materi.index'));
        $this->assertDatabaseHas('materis', [
            'judul' => 'Materi Baru Guru A dengan Rombel',
            'user_id' => $this->guruA->id,
            'target_rombel' => '10 DKV 1',
        ]);
    }

    /**
     * API Siswa: Siswa 10 DKV 1 hanya menerima materi dari Guru A (pengampu kelasnya) dan tidak menerima materi Guru B.
     */
    public function test_student_dkv_only_receives_materi_from_assigned_teacher(): void
    {
        $materiA = Materi::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Materi Grammar 10 DKV 1',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        $materiB = Materi::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Materi Grammar 10 RPL 1',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        // Siswa DKV merequest materi dengan parameter siswa_id
        $response = $this->getJson("/api/materi?siswa_id={$this->siswaDKV->id}");

        $response->assertStatus(200);
        $materiIds = collect($response->json('data'))->pluck('id')->all();

        $this->assertContains($materiA->id, $materiIds);
        $this->assertNotContains($materiB->id, $materiIds);
    }

    /**
     * API Siswa: Siswa 10 RPL 1 hanya menerima materi dari Guru B dan tidak menerima materi Guru A.
     */
    public function test_student_rpl_only_receives_materi_from_assigned_teacher(): void
    {
        $materiA = Materi::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Materi DKV Khusus',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        $materiB = Materi::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Materi RPL Khusus',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        // Siswa RPL merequest materi
        $response = $this->getJson("/api/materi?siswa_id={$this->siswaRPL->id}");

        $response->assertStatus(200);
        $materiIds = collect($response->json('data'))->pluck('id')->all();

        $this->assertContains($materiB->id, $materiIds);
        $this->assertNotContains($materiA->id, $materiIds);
    }

    /**
     * API Siswa: Isolasi listening audio dan kuis kuesioner berjalan sesuai guru pengampu kelas.
     */
    public function test_student_only_receives_listening_and_quiz_from_assigned_teacher(): void
    {
        // Listening
        $listeningA = ModulListening::create([
            'user_id' => $this->guruA->id,
            'judul' => 'Listening Track Guru A',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        $listeningB = ModulListening::create([
            'user_id' => $this->guruB->id,
            'judul' => 'Listening Track Guru B',
            'tingkat_kesulitan' => 'Beginner',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        // Quiz
        $quizA = Quiz::factory()->create([
            'user_id' => $this->guruA->id,
            'judul' => 'Quiz Guru A',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        $quizB = Quiz::factory()->create([
            'user_id' => $this->guruB->id,
            'judul' => 'Quiz Guru B',
            'tingkat_kelas' => '10',
            'status' => 'aktif',
        ]);

        // 1. Uji endpoint listening untuk Siswa DKV
        $responseListening = $this->getJson("/api/listening?siswa_id={$this->siswaDKV->id}");
        $listeningIds = collect($responseListening->json('data'))->pluck('id')->all();
        $this->assertContains($listeningA->id, $listeningIds);
        $this->assertNotContains($listeningB->id, $listeningIds);

        // 2. Uji endpoint quiz untuk Siswa DKV
        $responseQuiz = $this->getJson("/api/quiz?siswa_id={$this->siswaDKV->id}");
        $quizIds = collect($responseQuiz->json('data'))->pluck('id')->all();
        $this->assertContains($quizA->id, $quizIds);
        $this->assertNotContains($quizB->id, $quizIds);
    }

    /**
     * Materi umum sekolah (tanpa user_id atau dibuat oleh Super Admin) tetap dapat diakses oleh seluruh siswa.
     */
    public function test_common_school_material_is_available_to_all_students(): void
    {
        $materiUmum = Materi::factory()->create([
            'user_id' => null,
            'judul' => 'Materi Umum Sekolah untuk Semua',
            'tingkat_kelas' => 'Semua Kelas',
            'status' => 'aktif',
        ]);

        $materiAdmin = Materi::factory()->create([
            'user_id' => $this->admin->id,
            'judul' => 'Materi Resmi Administrator Sekolah',
            'tingkat_kelas' => 'Semua Kelas',
            'status' => 'aktif',
        ]);

        $responseDKV = $this->getJson("/api/materi?siswa_id={$this->siswaDKV->id}");
        $idsDKV = collect($responseDKV->json('data'))->pluck('id')->all();
        $this->assertContains($materiUmum->id, $idsDKV);
        $this->assertContains($materiAdmin->id, $idsDKV);

        $responseRPL = $this->getJson("/api/materi?siswa_id={$this->siswaRPL->id}");
        $idsRPL = collect($responseRPL->json('data'))->pluck('id')->all();
        $this->assertContains($materiUmum->id, $idsRPL);
        $this->assertContains($materiAdmin->id, $idsRPL);
    }
}
