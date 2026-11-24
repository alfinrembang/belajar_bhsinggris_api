<?php

namespace App\Exports;

use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class QuizNilaiExport implements FromView, ShouldAutoSize, WithTitle
{
    /**
     * @param  Collection<int, mixed>  $riwayatQuiz
     */
    public function __construct(
        protected Collection $riwayatQuiz,
        protected int $kkm,
        protected int $totalRiwayat,
        protected int $totalTuntas,
        protected int $totalRemedial,
        protected float $rataRataSkor,
        protected ?Quiz $quizPilihan = null
    ) {}

    /**
     * Render data rekap nilai kuis siswa ke dalam view Blade HTML untuk dikonversi menjadi berkas Excel (.xlsx).
     */
    public function view(): View
    {
        return view('guru.laporan_analitik.export_quiz_excel', [
            'riwayatQuiz' => $this->riwayatQuiz,
            'kkm' => $this->kkm,
            'totalRiwayat' => $this->totalRiwayat,
            'totalTuntas' => $this->totalTuntas,
            'totalRemedial' => $this->totalRemedial,
            'rataRataSkor' => $this->rataRataSkor,
            'quizPilihan' => $this->quizPilihan,
        ]);
    }

    /**
     * Menentukan judul worksheet di file Excel.
     */
    public function title(): string
    {
        return 'Rekap Nilai Kuis';
    }
}
