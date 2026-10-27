<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKategoriMateriRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses untuk mengedit kategori.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['guru', 'admin'], true);
    }

    /**
     * Aturan validasi pembaruan kategori materi.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $kategoriId = $this->route('kategori')?->id ?? $this->route('kategori');

        return [
            'nama' => ['required', 'string', 'max:100', Rule::unique('kategori_materis', 'nama')->ignore($kategoriId)],
            'deskripsi' => ['nullable', 'string'],
            'warna_hex' => ['required', 'string', 'max:20'],
            'icon_name' => ['required', 'string', 'max:50'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'is_aktif' => ['nullable', 'boolean'],
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
            'nama.required' => 'Nama kategori wajib diisi!',
            'nama.unique' => 'Nama kategori ini sudah terdaftar!',
            'warna_hex.required' => 'Silakan pilih warna tema untuk kategori ini!',
            'icon_name.required' => 'Silakan pilih ikon penanda kategori!',
        ];
    }
}
