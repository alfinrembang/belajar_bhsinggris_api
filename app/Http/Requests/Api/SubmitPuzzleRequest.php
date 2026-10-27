<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class SubmitPuzzleRequest extends BaseApiRequest
{
    /**
     * Aturan validasi penyelesaian level game puzzle oleh siswa.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'siswa_id' => ['nullable', 'integer'],
            'difficulty' => ['required', 'string'],
            'level' => ['required', 'integer'],
            'stars' => ['required', 'integer', 'min:1', 'max:3'],
            'time_spent' => ['required', 'string'],
            'coins_earned' => ['required', 'integer'],
        ];
    }
}
