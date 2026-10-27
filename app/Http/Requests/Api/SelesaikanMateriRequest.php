<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class SelesaikanMateriRequest extends BaseApiRequest
{
    /**
     * Aturan validasi penyelesaian modul materi pembelajaran oleh siswa.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'siswa_id' => ['nullable', 'integer'],
            'skor' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'jawaban' => ['nullable', 'array'],
            'jawaban.*' => ['nullable', 'string', 'max:10'],
        ];
    }
}
