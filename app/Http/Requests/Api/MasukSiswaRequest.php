<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class MasukSiswaRequest extends BaseApiRequest
{
    /**
     * Aturan validasi masuk siswa metode cepat (kompatibilitas tanpa password).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'max:50'],
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'kelas' => ['required', 'string', 'max:50'],
            'no_absen' => ['required', 'string', 'max:10'],
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
            'nis.required' => 'NIS siswa wajib diisi!',
            'nama_lengkap.required' => 'Nama lengkap siswa wajib diisi!',
            'kelas.required' => 'Kelas siswa wajib dipilih!',
            'no_absen.required' => 'Nomor absen siswa wajib diisi!',
        ];
    }
}
