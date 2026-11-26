<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\AktivitasApiController;
use App\Models\Siswa;
use App\Models\SiswaListeningProgres;
use App\Models\SiswaMateriProgres;
use App\Models\SiswaQuizProgres;
use App\Models\SiswaFishingProgres;
use App\Models\Materi;
use App\Models\ModulListening;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;

echo "--- CHECKING PROGRESS RECORDS IN DB ---" . PHP_EOL;
echo "Materi progres count: " . SiswaMateriProgres::count() . PHP_EOL;
echo "Listening progres count: " . SiswaListeningProgres::count() . PHP_EOL;
echo "Quiz progres count: " . SiswaQuizProgres::count() . PHP_EOL;
echo "Fishing progres count: " . SiswaFishingProgres::count() . PHP_EOL;

// Let's create dummy/test progress records for Siswa ID 1 if none exist, so we can verify the response format
$siswa = Siswa::first();
echo "Siswa ID 1: " . $siswa->nama_lengkap . PHP_EOL;

$materi = Materi::first();
$listening = ModulListening::first();
$quiz = Quiz::first();

if ($materi) {
    SiswaMateriProgres::updateOrCreate(
        ['siswa_id' => $siswa->id, 'materi_id' => $materi->id],
        [
            'status' => 'selesai',
            'skor' => 95,
            'xp_didapat' => 50,
            'completed_at' => Carbon::now()->subHours(2),
        ]
    );
}

if ($listening) {
    SiswaListeningProgres::updateOrCreate(
        ['siswa_id' => $siswa->id, 'modul_listening_id' => $listening->id],
        [
            'status' => 'selesai',
            'skor' => 100,
            'xp_didapat' => 60,
            'completed_at' => Carbon::now()->subMinutes(30),
        ]
    );
}

if ($quiz) {
    SiswaQuizProgres::updateOrCreate(
        ['siswa_id' => $siswa->id, 'quiz_id' => $quiz->id],
        [
            'status' => 'selesai',
            'skor' => 88,
            'jumlah_benar' => 8,
            'total_soal' => 10,
            'xp_didapat' => 70,
            'completed_at' => Carbon::now()->subDays(1),
        ]
    );
}

SiswaFishingProgres::updateOrCreate(
    ['siswa_id' => $siswa->id, 'difficulty' => 'mudah', 'level' => 1],
    [
        'stars' => 3,
        'high_score' => 450,
        'fish_name' => 'Golden Tuna',
        'fish_emoji' => '🐠',
        'is_completed' => true,
    ]
);

// Now test controller
$controller = new AktivitasApiController();
$req = Request::create('/api/siswa/aktivitas-terbaru', 'GET', [
    'siswa_id' => $siswa->id,
    'limit' => 5
]);
$res = $controller->index($req);
$data = json_decode($res->getContent(), true);

echo PHP_EOL . "--- RESULT FROM API ---" . PHP_EOL;
echo "Status: " . $data['status'] . PHP_EOL;
echo "Total Aktivitas: " . $data['total_aktivitas'] . PHP_EOL;
echo "Limit: " . $data['limit'] . PHP_EOL;
foreach ($data['data'] as $act) {
    echo sprintf("[%s] %s | Sub: %s | %s | %s | Badge: %s\n", 
        strtoupper($act['tipe']), 
        $act['judul'], 
        $act['subjudul'],
        $act['tanggal'], 
        $act['waktu_lalu'],
        $act['badge_text']
    );
}

echo "--- TEST COMPLETED ---" . PHP_EOL;
