<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GameFeatureTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guru_can_access_game_leaderboard_page(): void
    {
        $guru = User::factory()->create([
            'role' => 'guru',
        ]);

        $response = $this->actingAs($guru)->get(route('guru.game.index'));

        $response->assertStatus(200);
        $response->assertSee('Mini Games & Leaderboard');
        $response->assertSee('Mode Permainan Edukasi Siswa');
        $response->assertSee('Papan Peringkat Game');
        $response->assertSee('Tebak Serigala');
        $response->assertSee('modalTambahSoalIkan');
    }
}
