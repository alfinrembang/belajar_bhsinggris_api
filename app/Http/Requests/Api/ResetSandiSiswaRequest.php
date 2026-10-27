<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class ResetSandiSiswaRequest extends BaseApiRequest
{
    /**
     * Aturan validasi reset kata sandi siswa dengan token.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'sandi_baru' => ['sometimes', 'required', 'string', 'min:6'],
            'password' => ['sometimes', 'required', 'string', 'min:6'],
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
            'email.required' => 'Alamat email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'token.required' => 'Token kode reset kata sandi wajib disertakan!',
            'sandi_baru.min' => 'Kata sandi baru minimal harus 6 karakter!',
            'password.min' => 'Kata sandi baru minimal harus 6 karakter!',
        ];
    }
}
