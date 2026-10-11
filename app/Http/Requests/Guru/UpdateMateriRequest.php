<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMateriRequest extends FormRequest
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
            'deskripsi_singkat' => ['nullable', 'string', 'max:1000'],
            'penjelasan' => ['required', 'string'],
            'contoh_teks' => ['nullable', 'string'],
            'xp_reward' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'in:aktif,draft'],
            'gambar_materi' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'audio_materi' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:15360'],
            'soals' => ['nullable', 'array'],
            'soals.*.tipe_soal' => ['nullable', 'in:pilgan,listening'],
            'soals.*.pertanyaan' => ['nullable', 'string', 'max:1000'],
            'soals.*.audio_soal' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:15360'],
            'soals.*.existing_audio_soal' => ['nullable', 'string', 'max:255'],
            'soals.*.pilihan_a' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_b' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_c' => ['nullable', 'string', 'max:500'],
            'soals.*.pilihan_d' => ['nullable', 'string', 'max:500'],
            'soals.*.kunci_jawaban' => ['nullable', 'in:A,B,C,D,a,b,c,d'],
            'soals.*.pembahasan' => ['nullable', 'string', 'max:2000'],
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
            'judul.required' => 'Judul materi pembelajaran wajib diisi!',
            'kategori.required' => 'Kategori pembelajaran wajib dipilih!',
            'tingkat_kelas.required' => 'Target tingkat kelas wajib ditentukan!',
            'penjelasan.required' => 'Penjelasan teori materi wajib diisi!',
            'gambar_materi.image' => 'File cover harus berupa gambar yang valid (jpg, jpeg, png, webp)!',
            'gambar_materi.max' => 'Ukuran file gambar maksimal 3MB!',
            'audio_materi.mimes' => 'File audio materi harus berformat MP3, WAV, OGG, M4A, atau AAC!',
            'audio_materi.max' => 'Ukuran file audio materi maksimal 15MB!',
            'soals.*.audio_soal.mimes' => 'File audio soal listening harus berformat MP3, WAV, OGG, M4A, atau AAC!',
            'soals.*.audio_soal.max' => 'Ukuran file audio soal listening maksimal 15MB!',
        ];
    }
}
