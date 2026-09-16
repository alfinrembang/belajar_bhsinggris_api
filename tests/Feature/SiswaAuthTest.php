<?php

namespace Tests\Feature;

use App\Models\Siswa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SiswaAuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test pendaftaran akun siswa baru.
     */
    public function test_siswa_can_register(): void
    {
        $payload = [
            'nama' => 'Budi Pratama Test',
            'email' => 'buditest@gmail.com',
            'nisn' => '9988776655',
            'kelas' => '12',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
            'sandi' => 'secret123',
        ];

        $response = $this->postJson('/api/siswa/register', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'role' => 'siswa',
            ])
            ->assertJsonStructure([
                'token',
                'data' => [
                    'id',
                    'nama_lengkap',
                    'email',
                    'nisn',
                    'kelas',
                    'jurusan',
                    'no_kelas',
                    'kelas_lengkap',
                ],
            ]);

        $this->assertDatabaseHas('siswas', [
            'email' => 'buditest@gmail.com',
            'nisn' => '9988776655',
        ]);
    }

    /**
     * Test login siswa menggunakan email & kata sandi.
     */
    public function test_siswa_can_login_with_email_and_password(): void
    {
        $siswa = Siswa::create([
            'nama_lengkap' => 'Siswa Login Test',
            'email' => 'logintest@gmail.com',
            'nisn' => '1122334455',
            'nis' => '1122334455',
            'kelas' => '10',
            'jurusan' => 'TSM',
            'no_kelas' => '2',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/siswa/login', [
            'email' => 'logintest@gmail.com',
            'sandi' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'role' => 'siswa',
            ])
            ->assertJsonStructure(['token', 'data']);
    }

    /**
     * Test login siswa menggunakan NISN & kata sandi.
     */
    public function test_siswa_can_login_with_nisn_and_password(): void
    {
        $siswa = Siswa::create([
            'nama_lengkap' => 'Siswa NISN Test',
            'email' => 'nisntest@gmail.com',
            'nisn' => '5544332211',
            'nis' => '5544332211',
            'kelas' => '11',
            'jurusan' => 'DKV',
            'no_kelas' => '1',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/siswa/login', [
            'nisn' => '5544332211',
            'sandi' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'role' => 'siswa',
            ]);
    }

    /**
     * Test permintaan lupa sandi dan reset kata sandi.
     */
    public function test_siswa_can_request_lupa_sandi_and_reset_password(): void
    {
        $siswa = Siswa::create([
            'nama_lengkap' => 'Siswa Lupa Sandi Test',
            'email' => 'lupasanditest@gmail.com',
            'nisn' => '7788990011',
            'nis' => '7788990011',
            'kelas' => '10',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
            'password' => bcrypt('oldpassword'),
        ]);

        // 1. Request Lupa Sandi
        $lupaRes = $this->postJson('/api/siswa/lupa-sandi', [
            'email' => 'lupasanditest@gmail.com',
        ]);

        $lupaRes->assertStatus(200)
            ->assertJsonStructure(['data' => ['reset_token']]);

        $resetToken = $lupaRes->json('data.reset_token');

        // 2. Eksekusi Reset Sandi
        $resetRes = $this->postJson('/api/siswa/reset-sandi', [
            'email' => 'lupasanditest@gmail.com',
            'token' => $resetToken,
            'sandi_baru' => 'newpassword123',
        ]);

        $resetRes->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        // 3. Login dengan kata sandi baru
        $loginRes = $this->postJson('/api/siswa/login', [
            'email' => 'lupasanditest@gmail.com',
            'sandi' => 'newpassword123',
        ]);

        $loginRes->assertStatus(200);
    }

    /**
     * Test akses profil siswa menggunakan Bearer token.
     */
    public function test_siswa_can_access_me_profile_with_token(): void
    {
        $token = 'test_sample_token_1234567890abcdefghijklmnopqrstuvwxyz';
        $siswa = Siswa::create([
            'nama_lengkap' => 'Siswa Profile Test',
            'email' => 'profiletest@gmail.com',
            'nisn' => '8899001122',
            'nis' => '8899001122',
            'kelas' => '12',
            'jurusan' => 'RPL',
            'no_kelas' => '1',
            'password' => bcrypt('secret'),
            'api_token' => $token,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/siswa/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'role' => 'siswa',
                'data' => [
                    'email' => 'profiletest@gmail.com',
                    'kelas_lengkap' => '12 RPL 1',
                ],
            ]);
    }
}
