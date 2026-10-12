<?php

namespace Tests\Feature;

use App\Models\PengaturanAkademik;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PengaturanSistemTest extends TestCase
{
    use DatabaseTransactions;

    protected User $guru;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guru = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru Pengajar Test',
            'email' => 'guru_test_'.uniqid().'@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * Tamu / user tanpa login tidak dapat mengakses halaman pengaturan sistem.
     */
    public function test_unauthenticated_user_cannot_access_pengaturan(): void
    {
        $response = $this->get('/pengaturan');
        $response->assertRedirect('/login');
    }

    /**
     * Guru yang terautentikasi dapat melihat halaman pengaturan sistem beserta data profilnya.
     */
    public function test_guru_can_view_pengaturan_page_with_active_settings(): void
    {
        PengaturanAkademik::getActiveSetting()->update([
            'tahun_ajaran' => '2026/2027',
            'kkm_default' => 75,
        ]);

        $response = $this->actingAs($this->guru)->get('/pengaturan');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem');
        $response->assertSee('Guru Pengajar Test');
        $response->assertSee('2026/2027');
    }

    /**
     * Guru dapat memperbarui nama, email, dan NIP pada profil akunnya.
     */
    public function test_guru_can_update_profile_info(): void
    {
        $newEmail = 'guru_baru_'.uniqid().'@example.com';

        $response = $this->actingAs($this->guru)->post('/pengaturan/profil', [
            'name' => 'Alfin Rembang, S.Pd.',
            'email' => $newEmail,
            'nip' => '199501012020121001',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->guru->id,
            'name' => 'Alfin Rembang, S.Pd.',
            'email' => $newEmail,
            'nip' => '199501012020121001',
        ]);
    }

    /**
     * Guru tidak dapat menggunakan email yang sudah terdaftar oleh pengguna lain.
     */
    public function test_guru_cannot_update_profile_with_duplicate_email(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'duplicate_'.uniqid().'@example.com',
        ]);

        $response = $this->actingAs($this->guru)->post('/pengaturan/profil', [
            'name' => 'Guru Coba Ganti Email',
            'email' => $existingUser->email,
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Guru dapat memperbarui kata sandi dengan verifikasi kata sandi lama yang valid.
     */
    public function test_guru_can_update_password_with_valid_current_password(): void
    {
        $response = $this->actingAs($this->guru)->post('/pengaturan/password', [
            'current_password' => 'password123',
            'password' => 'newSecretPassword2026',
            'password_confirmation' => 'newSecretPassword2026',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('newSecretPassword2026', $this->guru->fresh()->password));
    }

    /**
     * Guru gagal mengubah kata sandi jika kata sandi saat ini salah.
     */
    public function test_guru_cannot_update_password_with_wrong_current_password(): void
    {
        $response = $this->actingAs($this->guru)->post('/pengaturan/password', [
            'current_password' => 'wrong_password',
            'password' => 'newSecretPassword2026',
            'password_confirmation' => 'newSecretPassword2026',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    /**
     * Guru dapat memperbarui konfigurasi akademik, KKM, dan sakelar kenaikan kelas.
     */
    public function test_guru_can_update_academic_settings(): void
    {
        $response = $this->actingAs($this->guru)->post('/pengaturan/akademik', [
            'tahun_ajaran' => '2027/2028',
            'kkm_default' => 80,
            'durasi_kuis_default' => 25,
            'is_periode_kenaikan_aktif' => '1',
            'is_leaderboard_aktif' => '1',
            'keterangan' => 'Tahun Pelajaran Baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengaturan_akademiks', [
            'tahun_ajaran' => '2027/2028',
            'kkm_default' => 80,
            'durasi_kuis_default' => 25,
            'is_periode_kenaikan_aktif' => true,
            'is_leaderboard_aktif' => true,
            'keterangan' => 'Tahun Pelajaran Baru',
        ]);
    }
}
