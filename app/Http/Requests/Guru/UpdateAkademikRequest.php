<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAkademikRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak memperbarui konfigurasi akademik.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['guru', 'admin'], true);
    }

    /**
     * Aturan validasi pengaturan akademik.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tahun_ajaran' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
            'is_periode_kenaikan_aktif' => ['nullable', 'boolean'],
            'kkm_default' => ['required', 'integer', 'min:0', 'max:100'],
            'bobot_materi' => ['required', 'integer', 'min:0', 'max:100'],
            'bobot_listening' => ['required', 'integer', 'min:0', 'max:100'],
            'bobot_quiz' => ['required', 'integer', 'min:0', 'max:100'],
            'durasi_kuis_default' => ['required', 'integer', 'min:5', 'max:180'],
            'is_leaderboard_aktif' => ['nullable', 'boolean'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Konfigurasi validasi lanjutan untuk memastikan akumulasi seluruh bobot tepat 100%.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $materi = (int) $this->input('bobot_materi', 0);
            $listening = (int) $this->input('bobot_listening', 0);
            $quiz = (int) $this->input('bobot_quiz', 0);
            $total = $materi + $listening + $quiz;

            if ($total !== 100) {
                $validator->errors()->add(
                    'bobot_materi',
                    "Total akumulasi bobot persentase nilai (Materi + Listening + Kuis) harus tepat 100%. Saat ini total akumulasi: {$total}%."
                );
            }
        });
    }

    /**
     * Pesan kustom validasi dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'tahun_ajaran.regex' => 'Format tahun ajaran harus berupa format Tahun/Tahun (contoh: 2026/2027).',
            'kkm_default.required' => 'Standar KKM kuis wajib ditentukan.',
            'kkm_default.min' => 'Nilai KKM minimal 0.',
            'kkm_default.max' => 'Nilai KKM maksimal 100.',
            'bobot_materi.required' => 'Bobot nilai materi wajib ditentukan.',
            'bobot_materi.min' => 'Bobot nilai materi minimal 0%.',
            'bobot_materi.max' => 'Bobot nilai materi maksimal 100%.',
            'bobot_listening.required' => 'Bobot nilai listening wajib ditentukan.',
            'bobot_listening.min' => 'Bobot nilai listening minimal 0%.',
            'bobot_listening.max' => 'Bobot nilai listening maksimal 100%.',
            'bobot_quiz.required' => 'Bobot nilai kuis wajib ditentukan.',
            'bobot_quiz.min' => 'Bobot nilai kuis minimal 0%.',
            'bobot_quiz.max' => 'Bobot nilai kuis maksimal 100%.',
            'durasi_kuis_default.required' => 'Durasi kuis default wajib diisi.',
            'durasi_kuis_default.min' => 'Durasi kuis default minimal 5 menit.',
            'durasi_kuis_default.max' => 'Durasi kuis default maksimal 180 menit.',
        ];
    }
}
