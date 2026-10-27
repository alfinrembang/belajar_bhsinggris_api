<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmAiMateriRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses untuk mengonfirmasi materi dokumen ini.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        if (! $user || ! in_array($user->role, ['guru', 'admin'], true)) {
            return false;
        }

        $aiImport = $this->route('aiImport');
        if ($user->role === 'guru' && $aiImport && $aiImport->user_id !== $user->id) {
            return false;
        }

        return true;
    }

    /**
     * Aturan validasi konfirmasi materi hasil ekstraksi AI.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'tingkat_kelas' => ['required', 'string', 'max:50'],
            'target_rombel' => ['nullable', 'string', 'max:100'],
            'deskripsi_singkat' => ['required', 'string'],
            'penjelasan' => ['required', 'string'],
            'contoh_teks' => ['nullable', 'string'],
            'xp_reward' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'in:aktif,draft'],
            'soals' => ['nullable', 'array'],
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
            'judul.required' => 'Judul modul materi wajib diisi!',
            'kategori.required' => 'Kategori pembelajaran wajib dipilih!',
            'tingkat_kelas.required' => 'Tingkat kelas target materi wajib ditentukan!',
            'deskripsi_singkat.required' => 'Rangkuman atau deskripsi singkat materi wajib diisi!',
            'penjelasan.required' => 'Penjelasan teori materi pembelajaran wajib diisi!',
            'status.required' => 'Pilih status publikasi materi (aktif atau draft)!',
        ];
    }
}
