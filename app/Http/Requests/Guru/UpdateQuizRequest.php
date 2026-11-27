<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateQuizRequest extends FormRequest
{
    use HandlesTargetRombel {
        after as protected targetRombelAfter;
    }
    use ValidatesQuizSoals;

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
        $quiz = $this->route('quiz');
        $quizId = is_object($quiz) ? $quiz->id : $quiz;

        return array_merge([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'tingkat_kelas' => ['required', 'string', 'max:255'],
            'target_rombel' => ['nullable', 'string', 'max:2000'],
            'target_rombels' => ['nullable', 'array'],
            'target_rombels.*' => ['string', 'max:100'],
            'kode_room' => ['required', 'string', 'max:20', Rule::unique('quizzes', 'kode_room')->ignore($quizId)],
            'durasi_menit' => ['required', 'integer', 'min:1', 'max:180'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'xp_reward' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'status' => ['required', 'in:aktif,draft'],
        ], $this->quizSoalRules(true));
    }

    /**
     * Gabungkan callback validasi rombel dan butir soal.
     *
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            ...$this->targetRombelAfter(),
            $this->quizSoalAfterCallback(),
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
            'kode_room.required' => 'Kode room wajib diisi!',
            'kode_room.unique' => 'Kode room ini sudah digunakan oleh kuis lain!',
            'soals.*.audio_file.mimes' => 'File audio kuis listening harus berformat MP3, WAV, OGG, atau M4A!',
            'soals.*.audio_file.max' => 'Ukuran file audio kuis listening maksimal 15MB!',
        ];
    }
}
