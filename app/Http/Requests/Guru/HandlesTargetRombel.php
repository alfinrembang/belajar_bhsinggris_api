<?php

namespace App\Http\Requests\Guru;

use App\Models\Siswa;
use Illuminate\Validation\Validator;

trait HandlesTargetRombel
{
    /**
     * Mempersiapkan dan menormalisasi input target rombel / tingkat kelas sebelum validasi.
     * Mendukung pilihan tunggal maupun multi-rombel sekaligus.
     */
    protected function prepareForValidation(): void
    {
        $hasMultiInput = $this->has('target_rombels');
        if (! $this->has('target_rombel') && ! $this->has('tingkat_kelas') && ! $hasMultiInput) {
            return;
        }

        $rombelInput = $this->input('target_rombel');
        $tingkatInput = $this->input('tingkat_kelas');
        $multiInput = $this->input('target_rombels');

        // 1. Jika memilih mode multi-rombel ('__multi__' atau mengirim target_rombels[])
        if ($rombelInput === '__multi__' || (is_array($multiInput) && ! empty($multiInput))) {
            $rombels = collect(is_array($multiInput) ? $multiInput : [])
                ->map(fn ($item): string => trim((string) $item))
                ->filter(fn (string $item): bool => $item !== '')
                ->unique()
                ->values();

            if ($rombels->isEmpty()) {
                $this->merge([
                    'tingkat_kelas' => '',
                    'target_rombel' => null,
                ]);

                return;
            }

            $grades = $rombels
                ->map(fn (string $item): string => explode(' ', $item)[0] ?? '')
                ->filter(fn (string $g): bool => $g !== '')
                ->unique()
                ->sort()
                ->values();

            $this->merge([
                'tingkat_kelas' => $grades->isNotEmpty() ? $grades->implode(', ') : 'Semua Kelas',
                'target_rombel' => $rombels->implode(', '),
            ]);

            return;
        }

        $val = trim((string) ($rombelInput ?? $tingkatInput ?? ''));
        $cleanedVal = str_ireplace('Kelas ', '', $val);

        if ($val === '' && ! $this->filled('tingkat_kelas')) {
            $this->merge([
                'tingkat_kelas' => '',
                'target_rombel' => null,
            ]);

            return;
        }

        if ($val === 'Semua Kelas' || $val === 'semua' || $val === 'umum') {
            $tingkat = 'Semua Kelas';
            $rombel = null;
        } elseif (str_starts_with($val, 'tingkat:')) {
            $tingkat = trim(str_replace('tingkat:', '', $val));
            $rombel = null;
        } elseif (in_array($cleanedVal, ['10', '11', '12', '7', '8', '9'], true)) {
            $tingkat = $cleanedVal;
            $rombel = null;
        } elseif (str_contains($val, ',')) {
            $rombels = collect(explode(',', $val))
                ->map(fn (string $item): string => trim($item))
                ->filter(fn (string $item): bool => $item !== '')
                ->unique()
                ->values();
            $grades = $rombels
                ->map(fn (string $item): string => explode(' ', $item)[0] ?? '')
                ->filter(fn (string $g): bool => $g !== '')
                ->unique()
                ->sort()
                ->values();
            $tingkat = $grades->isNotEmpty() ? $grades->implode(', ') : 'Semua Kelas';
            $rombel = $rombels->implode(', ');
        } else {
            // Rombel spesifik (contoh: '10 DKV 1', '11 RPL 2')
            $parts = explode(' ', $val);
            $tingkat = $parts[0] ?? 'Semua Kelas';
            $rombel = $val;
        }

        // Kompatibilitas jika request/test lama tetap mengirimkan tingkat_kelas dan target_rombel secara terpisah
        if ($this->filled('tingkat_kelas') && $this->has('target_rombel') && ! str_starts_with((string) $rombelInput, 'tingkat:') && $rombelInput !== 'Semua Kelas') {
            $tingkatRaw = (string) $tingkatInput;
            $cleanedTingkat = str_ireplace('Kelas ', '', $tingkatRaw);
            $tingkat = in_array($cleanedTingkat, ['10', '11', '12', '7', '8', '9'], true) ? $cleanedTingkat : $tingkatRaw;
            $rombel = $rombelInput ?: null;
        }

        $this->merge([
            'tingkat_kelas' => $tingkat,
            'target_rombel' => $rombel,
        ]);
    }

    /**
     * Validasi tambahan: wajib sudah ada minimal 1 siswa terdaftar di sistem sebelum membuat/memperbarui konten.
     *
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (! Siswa::exists()) {
                    $validator->errors()->add(
                        'target_rombel',
                        'Belum ada data siswa terdaftar. Silakan daftarkan atau import data siswa terlebih dahulu di menu Manajemen Siswa & Kelas!'
                    );

                    return;
                }

                if ($this->input('target_rombel') === '__multi__' && empty(array_filter((array) $this->input('target_rombels', [])))) {
                    $validator->errors()->add(
                        'target_rombel',
                        'Pilih minimal satu rombel kelas tujuan pada daftar rombel!'
                    );
                }
            },
        ];
    }
}
