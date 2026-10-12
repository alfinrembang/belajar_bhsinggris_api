<?php

namespace App\Http\Requests\Guru;

use App\Models\Siswa;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
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
        /** @var Siswa|string|null $siswa */
        $siswa = $this->route('siswa');
        $siswaId = is_object($siswa) ? $siswa->id : $siswa;

        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('siswas', 'email')->ignore($siswaId),
            ],
            'nis' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('siswas', 'nis')->ignore($siswaId),
            ],
            'nisn' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('siswas', 'nisn')->ignore($siswaId),
            ],
            'kelas' => ['required', 'string', 'in:10,11,12'],
            'jurusan' => ['required', 'string', 'max:50'],
            'no_kelas' => ['nullable', 'string', 'max:10'],
            'no_absen' => ['nullable', 'string', 'max:10'],
            'tahun_ajaran_aktif' => ['nullable', 'string', 'max:20'],
            'status_kelulusan' => ['nullable', 'string', 'in:aktif,lulus,tinggal_kelas'],
            'password' => ['nullable', 'string', 'min:6', 'max:100'],
        ];
    }

    /**
     * Get custom error messages for validator.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap siswa wajib diisi.',
            'email.required' => 'Alamat email siswa wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar oleh siswa lain.',
            'nis.unique' => 'NIS ini sudah digunakan oleh siswa lain.',
            'nisn.unique' => 'NISN ini sudah digunakan oleh siswa lain.',
            'kelas.required' => 'Tingkat kelas siswa wajib dipilih.',
            'jurusan.required' => 'Jurusan siswa wajib diisi.',
            'password.min' => 'Kata sandi minimal berjumlah 6 karakter.',
        ];
    }
}
