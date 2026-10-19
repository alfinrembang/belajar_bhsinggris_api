<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected User $guru;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'adminprima',
            'email' => 'admin_test_'.uniqid().'@mail.com',
            'password' => Hash::make('admin1234'),
        ]);

        $this->guru = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru Pengajar Test',
            'email' => 'guru_test_'.uniqid().'@mail.com',
            'password' => Hash::make('guru1234'),
        ]);
    }

    /**
     * Tamu tanpa login tidak dapat mengakses admin dashboard.
     */
    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Akun role guru ditolak (403 Forbidden) saat mencoba mengakses admin dashboard.
     */
    public function test_guru_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->guru)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Administrator dapat mengakses admin dashboard dan melihat daftar guru.
     */
    public function test_admin_can_access_dashboard_and_see_guru_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Administrator');
        $response->assertSee('Daftar Akun Guru Pengajar');
        $response->assertSee($this->guru->name);
        $response->assertSee('ADMIN PORTAL');
        $response->assertDontSee('MODUL PEMBELAJARAN');
    }

    /**
     * Admin yang mengakses /dashboard otomatis dialihkan ke /admin/dashboard.
     */
    public function test_admin_accessing_general_dashboard_is_redirected_to_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Guru yang mengakses dashboard melihat menu pembelajaran secara lengkap.
     */
    public function test_guru_can_access_own_dashboard_and_see_learning_modules(): void
    {
        $response = $this->actingAs($this->guru)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('GURU PORTAL');
        $response->assertSee('Manajemen Materi');
        $response->assertSee('Modul Listening');
        $response->assertSeeText('Bank Soal & Kuis');
    }

    /**
     * Administrator dapat mendaftarkan akun guru baru.
     */
    public function test_admin_can_create_new_guru(): void
    {
        $newEmail = 'guru_baru_'.uniqid().'@sekolah.sch.id';

        $response = $this->actingAs($this->admin)->post(route('admin.guru.store'), [
            'name' => 'Guru Baru Bahasa Inggris',
            'email' => $newEmail,
            'nip' => '1990123456789',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => $newEmail,
            'name' => 'Guru Baru Bahasa Inggris',
            'role' => 'guru',
            'nip' => '1990123456789',
        ]);
    }

    /**
     * Validasi gagal jika mendaftarkan guru dengan email duplikat.
     */
    public function test_admin_cannot_create_guru_with_duplicate_email(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.guru.store'), [
            'name' => 'Guru Duplikat',
            'email' => $this->guru->email,
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Administrator dapat mengedit profil dan NIP akun guru.
     */
    public function test_admin_can_update_guru_profile(): void
    {
        $updatedEmail = 'updated_guru_'.uniqid().'@sekolah.sch.id';

        $response = $this->actingAs($this->admin)->post(route('admin.guru.update', $this->guru), [
            'name' => 'Guru Updated Name',
            'email' => $updatedEmail,
            'nip' => '9876543210',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->guru->id,
            'name' => 'Guru Updated Name',
            'email' => $updatedEmail,
            'nip' => '9876543210',
        ]);
    }

    /**
     * Administrator dapat mereset kata sandi akun guru.
     */
    public function test_admin_can_reset_guru_password(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.guru.reset_password', $this->guru), [
            'new_password' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->guru->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->guru->password));
    }

    /**
     * Administrator dapat menghapus akun guru.
     */
    public function test_admin_can_delete_guru(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.guru.destroy', $this->guru));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $this->guru->id,
        ]);
    }

    /**
     * Administrator tidak dapat menghapus akun administrator lain atau dirinya sendiri.
     */
    public function test_admin_cannot_delete_admin_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.guru.destroy', $this->admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }
}
