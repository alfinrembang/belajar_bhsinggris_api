<?php

namespace App\Http\Requests\Guru;

trait HandlesTargetRombel
{
    /**
     * Mempersiapkan dan menormalisasi input target rombel / tingkat kelas sebelum validasi.
     * Menggabungkan pemilihan rombel & tingkat kelas menjadi satu input terpadu.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('target_rombel') && ! $this->has('tingkat_kelas')) {
            return;
        }

        $rombelInput = $this->input('target_rombel');
        $tingkatInput = $this->input('tingkat_kelas');

        $val = trim((string) ($rombelInput ?? $tingkatInput ?? ''));
        $cleanedVal = str_ireplace('Kelas ', '', $val);

        if ($val === '' || $val === 'Semua Kelas' || $val === 'semua' || $val === 'umum') {
            $tingkat = 'Semua Kelas';
            $rombel = null;
        } elseif (str_starts_with($val, 'tingkat:')) {
            $tingkat = str_replace('tingkat:', '', $val);
            $rombel = null;
        } elseif (in_array($cleanedVal, ['10', '11', '12', '7', '8', '9'], true)) {
            $tingkat = $cleanedVal;
            $rombel = null;
        } else {
            // Rombel spesifik (contoh: '10 DKV 1', '11 RPL 2')
            $parts = explode(' ', $val);
            $tingkat = $parts[0] ?? 'Semua Kelas';
            $rombel = $val;
        }

        // Kompatibilitas jika request/test lama tetap mengirimkan tingkat_kelas dan target_rombel secara terpisah
        if ($this->filled('tingkat_kelas') && $this->has('target_rombel') && ! str_starts_with((string) $rombelInput, 'tingkat:')) {
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
}
