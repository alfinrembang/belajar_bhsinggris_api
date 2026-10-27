<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGuruRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses sebagai administrator.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    /**
     * Aturan validasi pendaftaran akun guru baru oleh administrator.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:users,nip'],
            'password' => ['required', 'string', 'min:6'],
            'kelas_diampu' => ['nullable', 'array'],
            'kelas_diampu.*' => ['string'],
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
            'name.required' => 'Nama lengkap guru wajib diisi!',
            'email.required' => 'Alamat email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email ini sudah terdaftar oleh pengguna lain!',
            'nip.unique' => 'NIP ini sudah terdaftar oleh guru lain!',
            'password.required' => 'Kata sandi wajib diisi!',
            'password.min' => 'Kata sandi minimal 6 karakter!',
        ];
    }
}
