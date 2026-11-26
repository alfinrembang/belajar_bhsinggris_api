<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Materi;
use App\Models\SiswaMateriProgres;

echo "Materi count: " . Materi::count() . PHP_EOL;
$m = Materi::first();
echo "First Materi: " . ($m ? $m->id . ' - ' . $m->judul : 'None') . PHP_EOL;

echo "SiswaMateriProgres: " . PHP_EOL;
print_r(SiswaMateriProgres::all()->toArray());
