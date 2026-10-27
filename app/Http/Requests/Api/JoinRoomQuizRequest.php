<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;

class JoinRoomQuizRequest extends BaseApiRequest
{
    /**
     * Aturan validasi bergabung ke room kuis siswa.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_room' => ['required', 'string', 'max:20'],
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
            'kode_room.required' => 'Kode room wajib dimasukkan!',
        ];
    }
}
