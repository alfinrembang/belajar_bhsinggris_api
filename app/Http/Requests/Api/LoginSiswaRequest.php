<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class LoginSiswaRequest extends BaseApiRequest
{
    /**
     * Aturan validasi login siswa via API Flutter.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'string'],
            'nisn' => ['nullable', 'string'],
            'sandi' => ['sometimes', 'required', 'string'],
            'password' => ['sometimes', 'required', 'string'],
        ];
    }
}
