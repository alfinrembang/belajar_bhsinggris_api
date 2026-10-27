<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class RegisterSiswaRequest extends BaseApiRequest
{
    /**
     * Aturan validasi pendaftaran akun siswa baru via API Flutter.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['sometimes', 'required', 'string', 'max:100'],
            'nama_lengkap' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:siswas,email', 'max:100'],
            'nisn' => ['required', 'string', 'unique:siswas,nisn', 'max:30'],
            'kelas' => ['required', 'string', 'max:20'],
            'jurusan' => ['nullable', 'string', 'max:30'],
            'no_kelas' => ['nullable', 'string', 'max:10'],
            'no_absen' => ['nullable', 'integer', 'between:1,50'],
            'sandi' => ['sometimes', 'required', 'string', 'min:6'],
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
            'email.required' => 'Alamat email siswa wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email ini sudah terdaftar oleh siswa lain!',
            'nisn.required' => 'Nomor Induk Siswa Nasional (NISN) wajib diisi!',
            'nisn.unique' => 'NISN ini sudah terdaftar di sistem!',
            'kelas.required' => 'Tingkat kelas siswa wajib dipilih!',
            'sandi.min' => 'Kata sandi minimal harus 6 karakter!',
            'password.min' => 'Kata sandi minimal harus 6 karakter!',
        ];
    }
}
