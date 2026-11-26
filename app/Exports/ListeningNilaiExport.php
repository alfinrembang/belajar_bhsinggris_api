<?php

namespace App\Exports;

use App\Models\ModulListening;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ListeningNilaiExport implements FromView, ShouldAutoSize, WithTitle
{
    /**
     * @param  Collection<int, mixed>  $riwayatListening
     */
    public function __construct(
        protected Collection $riwayatListening,
        protected int $kkm,
        protected int $totalRiwayat,
        protected int $totalTuntas,
        protected int $totalRemedial,
        protected float $rataRataSkor,
        protected ?ModulListening $listeningPilihan = null
    ) {}

    /**
     * Render data rekap nilai modul listening siswa ke dalam view Blade HTML untuk berkas Excel (.xlsx).
     */
    public function view(): View
    {
        return view('guru.laporan_analitik.export_listening_excel', [
            'riwayatListening' => $this->riwayatListening,
            'kkm' => $this->kkm,
            'totalRiwayat' => $this->totalRiwayat,
            'totalTuntas' => $this->totalTuntas,
            'totalRemedial' => $this->totalRemedial,
            'rataRataSkor' => $this->rataRataSkor,
            'listeningPilihan' => $this->listeningPilihan,
        ]);
    }

    /**
     * Menentukan judul worksheet di file Excel.
     */
    public function title(): string
    {
        return 'Rekap Nilai Listening';
    }
}
