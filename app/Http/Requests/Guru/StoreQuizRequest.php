<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'tingkat_kelas' => ['required', 'string', 'max:50'],
            'target_rombel' => ['nullable', 'string', 'max:50'],
            'kode_room' => ['nullable', 'string', 'max:20', 'unique:quizzes,kode_room'],
            'durasi_menit' => ['required', 'integer', 'min:1', 'max:180'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'xp_reward' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'in:aktif,draft'],
            'soals' => ['nullable', 'array'],
            'soals.*.tipe_soal' => ['nullable', 'in:pilgan,reading,listening'],
            'soals.*.reading_passage' => ['nullable', 'string'],
            'soals.*.audio_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:15360'],
            'soals.*.pertanyaan' => ['nullable', 'string', 'max:1000'],
            'soals.*.pilihan_a' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_b' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_c' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_d' => ['nullable', 'string', 'max:500'],
            'soals.*.kunci_jawaban' => ['nullable', 'in:A,B,C,D,a,b,c,d'],
            'soals.*.pembahasan' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Pesan validasi berbahasa Indonesia yang informatif.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul paket kuis wajib diisi!',
            'kategori.required' => 'Kategori kuis wajib dipilih!',
            'tingkat_kelas.required' => 'Tingkat kelas target wajib dipilih!',
            'durasi_menit.required' => 'Durasi waktu pengerjaan wajib ditentukan!',
            'durasi_menit.min' => 'Durasi minimal pengerjaan adalah 1 menit!',
            'kode_room.unique' => 'Kode room ini sudah digunakan, silakan buat atau acak kode baru!',
            'soals.*.audio_file.mimes' => 'File audio kuis listening harus berformat MP3, WAV, OGG, atau M4A!',
            'soals.*.audio_file.max' => 'Ukuran file audio kuis listening maksimal 15MB!',
        ];
    }
}
