<?php

namespace App\Http\Requests\Guru;

use App\Models\QuizSoal;
use Illuminate\Validation\Validator;

trait ValidatesQuizSoals
{
    /**
     * Aturan validasi bersama untuk daftar butir soal kuis (Pilgan, Reading, Listening, dan True/False).
     *
     * @return array<string, array<int, mixed>>
     */
    protected function quizSoalRules(bool $withExistingAudio = false): array
    {
        $rules = [
            'soals' => ['nullable', 'array'],
            'soals.*.tipe_soal' => ['nullable', 'in:pilgan,reading,listening,true_false'],
            'soals.*.reading_passage' => ['nullable', 'string'],
            'soals.*.audio_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:15360'],
            'soals.*.pertanyaan' => ['nullable', 'string', 'max:1000'],
            'soals.*.pilihan_a' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_b' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_c' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_d' => ['nullable', 'string', 'max:500'],
            'soals.*.kunci_jawaban' => ['nullable', 'string', 'max:255'],
            'soals.*.pembahasan' => ['nullable', 'string', 'max:2000'],
            'soals.*.pernyataan_items' => ['nullable', 'array'],
            'soals.*.pernyataan_items.*.teks' => ['nullable', 'string', 'max:1000'],
            'soals.*.pernyataan_items.*.kunci' => ['nullable', 'string', 'in:true,false,TRUE,FALSE,1,0'],
        ];

        if ($withExistingAudio) {
            $rules['soals.*.existing_audio_file'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    /**
     * Callback validasi tambahan per tipe butir soal setelah aturan dasar.
     *
     * @return callable(Validator): void
     */
    protected function quizSoalAfterCallback(): callable
    {
        return function (Validator $validator): void {
            $soals = $this->input('soals', []);
            if (! is_array($soals)) {
                return;
            }

            foreach ($soals as $index => $item) {
                $tipe = $item['tipe_soal'] ?? 'pilgan';
                $pertanyaan = trim((string) ($item['pertanyaan'] ?? ''));

                if ($tipe === 'true_false') {
                    $items = is_array($item['pernyataan_items'] ?? null) ? $item['pernyataan_items'] : [];
                    $filledItems = array_filter($items, fn ($row): bool => ! empty(trim((string) ($row['teks'] ?? ''))));

                    if ($pertanyaan !== '' && empty($filledItems)) {
                        $validator->errors()->add(
                            "soals.{$index}.pernyataan_items",
                            'Soal True / False wajib memiliki minimal 1 pernyataan di dalam tabel.'
                        );
                    }

                    foreach ($filledItems as $rowIdx => $row) {
                        if (QuizSoal::normalizeTrueFalseValue($row['kunci'] ?? null) === null) {
                            $validator->errors()->add(
                                "soals.{$index}.pernyataan_items.{$rowIdx}.kunci",
                                'Kunci jawaban pernyataan harus bernilai True atau False.'
                            );
                        }
                    }

                    continue;
                }

                if ($pertanyaan !== '' && isset($item['kunci_jawaban'])) {
                    $kunci = strtoupper(trim((string) $item['kunci_jawaban']));
                    if (! in_array($kunci, ['A', 'B', 'C', 'D'], true)) {
                        $validator->errors()->add(
                            "soals.{$index}.kunci_jawaban",
                            'Kunci jawaban soal pilihan ganda harus A, B, C, atau D.'
                        );
                    }
                }
            }
        };
    }
}
