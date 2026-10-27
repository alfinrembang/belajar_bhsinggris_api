<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordGuruRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses sebagai administrator.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    /**
     * Aturan validasi reset kata sandi akun guru oleh administrator.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_password' => ['required', 'string', 'min:6'],
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
            'new_password.required' => 'Kata sandi baru wajib diisi!',
            'new_password.min' => 'Kata sandi minimal 6 karakter!',
        ];
    }
}
