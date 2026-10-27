<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class SubmitQuizRequest extends BaseApiRequest
{
    /**
     * Aturan validasi pengiriman lembar jawaban kuis siswa.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'siswa_id' => ['nullable', 'integer'],
            'jawaban' => ['required', 'array'],
            'waktu_pengerjaan_detik' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Pesan kustom validasi dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jawaban.required' => 'Lembar jawaban tidak boleh kosong!',
        ];
    }
}
