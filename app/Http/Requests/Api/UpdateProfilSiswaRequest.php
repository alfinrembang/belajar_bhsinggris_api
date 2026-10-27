<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class UpdateProfilSiswaRequest extends BaseApiRequest
{
    /**
     * Aturan validasi pembaruan profil siswa dan rombel via API Flutter.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'siswa_id' => ['nullable', 'integer'],
            'nama' => ['nullable', 'string', 'max:100'],
            'nama_lengkap' => ['nullable', 'string', 'max:100'],
            'kelas' => ['nullable', 'string', 'max:20'],
            'kelas_lengkap' => ['nullable', 'string', 'max:50'],
            'no_kelas' => ['nullable', 'string', 'max:10'],
            'no_absen' => ['nullable', 'string', 'max:10'],
            'sandi_baru' => ['nullable', 'string', 'min:6'],
            'password' => ['nullable', 'string', 'min:6'],
            'sandi_lama' => ['nullable', 'string'],
            'password_lama' => ['nullable', 'string'],
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
            'sandi_baru.min' => 'Kata sandi baru minimal harus 6 karakter!',
            'password.min' => 'Kata sandi baru minimal harus 6 karakter!',
        ];
    }
}
