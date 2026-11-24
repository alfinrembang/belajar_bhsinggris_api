<?php

namespace App\Exports;

use App\Models\Materi;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class MateriNilaiExport implements FromView, ShouldAutoSize, WithTitle
{
    /**
     * @param  Collection<int, mixed>  $progresList
     */
    public function __construct(
        protected Collection $progresList,
        protected int $kkm,
        protected int $totalProgres,
        protected int $totalTuntas,
        protected int $totalRemedial,
        protected float $rataRataSkor,
        protected ?Materi $materiPilihan = null,
        protected ?string $kategoriPilihan = null
    ) {}

    /**
     * Render data nilai pemahaman materi siswa ke dalam view Blade HTML untuk dikonversi menjadi berkas Excel (.xlsx).
     */
    public function view(): View
    {
        return view('guru.laporan_analitik.export_materi_excel', [
            'progresList' => $this->progresList,
            'kkm' => $this->kkm,
            'totalProgres' => $this->totalProgres,
            'totalTuntas' => $this->totalTuntas,
            'totalRemedial' => $this->totalRemedial,
            'rataRataSkor' => $this->rataRataSkor,
            'materiPilihan' => $this->materiPilihan,
            'kategoriPilihan' => $this->kategoriPilihan,
        ]);
    }

    /**
     * Menentukan judul worksheet di file Excel.
     */
    public function title(): string
    {
        return 'Nilai Pemahaman Materi';
    }
}
