<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginGuruRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berhak mengirimkan permintaan masuk ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi formulir login web guru & administrator.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
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
            'email.required' => 'Email belum diisi! Silakan masukkan alamat email akun Anda.',
            'email.email' => 'Format penulisan email tidak valid (contoh: guru@gmail.com)!',
            'password.required' => 'Kata sandi belum diisi! Silakan masukkan kata sandi Anda.',
        ];
    }
}
