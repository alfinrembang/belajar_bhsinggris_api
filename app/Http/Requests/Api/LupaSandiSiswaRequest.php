<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class LupaSandiSiswaRequest extends BaseApiRequest
{
    /**
     * Aturan validasi permintaan reset sandi siswa.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
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
            'email.required' => 'Alamat email wajib dimasukkan!',
            'email.email' => 'Format alamat email tidak valid!',
        ];
    }
}
