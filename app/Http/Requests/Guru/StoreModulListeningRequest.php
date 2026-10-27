<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreModulListeningRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses untuk membuat modul listening.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['guru', 'admin'], true);
    }

    /**
     * Aturan validasi penyimpanan modul listening baru.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tingkat_kesulitan' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'tingkat_kelas' => ['required', 'string', 'max:50'],
            'target_rombel' => ['nullable', 'string', 'max:50'],
            'xp_reward' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:aktif,draft'],
            'tujuan_belajar' => ['nullable', 'array'],
            'tujuan_belajar.*' => ['nullable', 'string'],
            'audios' => ['nullable', 'array'],
            'audios.*.judul_audio' => ['nullable', 'string', 'max:100'],
            'audios.*.audio_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:25600'],
            'audios.*.audio_url_input' => ['nullable', 'url', 'max:500'],
            'audios.*.durasi' => ['nullable', 'string', 'max:20'],
            'soals' => ['nullable', 'array'],
            'soals.*.pertanyaan' => ['required_with:soals', 'string'],
            'soals.*.audio_track_nomor' => ['nullable', 'integer'],
            'soals.*.pilihan_a' => ['required_with:soals', 'string'],
            'soals.*.pilihan_b' => ['required_with:soals', 'string'],
            'soals.*.pilihan_c' => ['required_with:soals', 'string'],
            'soals.*.pilihan_d' => ['required_with:soals', 'string'],
            'soals.*.jawaban_benar' => ['required_with:soals', 'in:A,B,C,D'],
            'soals.*.pembahasan' => ['nullable', 'string'],
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
            'judul.required' => 'Judul percakapan listening wajib diisi!',
            'tingkat_kesulitan.required' => 'Silakan pilih tingkat kesulitan!',
            'tingkat_kelas.required' => 'Tingkat kelas modul listening wajib dipilih!',
            'audios.*.audio_file.mimes' => 'File audio harus berformat MP3, WAV, OGG, M4A, atau AAC!',
            'audios.*.audio_file.max' => 'Ukuran file audio maksimal 25 MB!',
            'soals.*.pertanyaan.required_with' => 'Kalimat pertanyaan soal listening wajib diisi!',
            'soals.*.jawaban_benar.required_with' => 'Kunci jawaban benar wajib ditentukan (A, B, C, atau D)!',
        ];
    }
}
