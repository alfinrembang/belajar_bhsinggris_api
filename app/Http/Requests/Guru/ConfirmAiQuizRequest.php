<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmAiQuizRequest extends FormRequest
{
    use HandlesTargetRombel;

    /**
     * Tentukan apakah pengguna memiliki hak akses untuk mengonfirmasi kuis dokumen ini.
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
     * Aturan validasi konfirmasi paket kuis hasil ekstraksi AI.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'tingkat_kelas' => ['required', 'string', 'max:255'],
            'target_rombel' => ['nullable', 'string', 'max:2000'],
            'target_rombels' => ['nullable', 'array'],
            'target_rombels.*' => ['string', 'max:100'],
            'kode_room' => ['nullable', 'string', 'max:50'],
            'durasi_menit' => ['required', 'integer', 'min:5', 'max:180'],
            'deskripsi' => ['required', 'string'],
            'xp_reward' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'in:aktif,draft'],
            'soals' => ['nullable', 'array'],
            'soals.*.tipe_soal' => ['nullable', 'in:pilgan,multiple_choice,reading,true_false'],
            'soals.*.pernyataan_items' => ['nullable', 'array'],
            'soals.*.pernyataan_items.*.teks' => ['nullable', 'string', 'max:1000'],
            'soals.*.pernyataan_items.*.kunci' => ['nullable', 'string', 'in:true,false,TRUE,FALSE,1,0'],
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
            'judul.required' => 'Judul paket kuis wajib diisi!',
            'kategori.required' => 'Kategori kuis wajib dipilih!',
            'tingkat_kelas.required' => 'Tingkat kelas target kuis wajib ditentukan!',
            'durasi_menit.required' => 'Durasi waktu pengerjaan kuis wajib ditentukan!',
            'durasi_menit.min' => 'Durasi minimal pengerjaan kuis adalah 5 menit!',
            'durasi_menit.max' => 'Durasi maksimal pengerjaan kuis adalah 180 menit!',
            'deskripsi.required' => 'Petunjuk pengerjaan atau deskripsi kuis wajib diisi!',
            'status.required' => 'Pilih status publikasi kuis (aktif atau draft)!',
        ];
    }
}
