<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanSiswaExport implements FromView, ShouldAutoSize, WithTitle
{
    /**
     * @param  Collection<int, array<string, mixed>>  $siswaCollection
     */
    public function __construct(
        protected Collection $siswaCollection,
        protected int $kkm,
        protected int $totalSiswa,
        protected int $totalTuntas,
        protected int $totalRemedial,
        protected float $rataRataKelas,
        protected int $bobotMateri = 30,
        protected int $bobotListening = 30,
        protected int $bobotQuiz = 40
    ) {}

    /**
     * Render data laporan siswa ke dalam view Blade HTML untuk dikonversi menjadi berkas Excel (.xlsx).
     */
    public function view(): View
    {
        return view('guru.laporan_analitik.export_excel', [
            'siswaCollection' => $this->siswaCollection,
            'kkm' => $this->kkm,
            'totalSiswa' => $this->totalSiswa,
            'totalTuntas' => $this->totalTuntas,
            'totalRemedial' => $this->totalRemedial,
            'rataRataKelas' => $this->rataRataKelas,
            'bobotMateri' => $this->bobotMateri,
            'bobotListening' => $this->bobotListening,
            'bobotQuiz' => $this->bobotQuiz,
        ]);
    }

    /**
     * Menentukan judul worksheet di file Excel.
     */
    public function title(): string
    {
        return 'Rekap Nilai Siswa';
    }
}
