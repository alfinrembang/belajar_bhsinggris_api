<?php

namespace App\Http\Requests\Guru;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadAiDocumentRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna memiliki hak akses untuk mengunggah dokumen AI.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['guru', 'admin'], true);
    }

    /**
     * Aturan validasi unggah dokumen AI.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pptx,docx,pdf,txt', 'max:15360'],
            'tipe_target' => ['required', 'in:materi,quiz'],
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
            'file.required' => 'Pilih file dokumen terlebih dahulu.',
            'file.file' => 'Upload harus berupa file dokumen yang valid.',
            'file.mimes' => 'Format file harus berupa PowerPoint (.pptx), Word (.docx), PDF (.pdf), atau Teks (.txt).',
            'file.max' => 'Ukuran file dokumen maksimal 15 MB.',
            'tipe_target.required' => 'Tentukan jenis target konversi dokumen (Materi atau Kuis).',
            'tipe_target.in' => 'Target konversi dokumen tidak valid.',
        ];
    }
}
