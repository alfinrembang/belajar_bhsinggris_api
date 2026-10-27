<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseApiRequest extends FormRequest
{
    /**
     * Tentukan apakah klien API berhak mengirimkan permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Tangani kegagalan validasi agar selalu mengembalikan respons JSON 422 seragam ke Flutter.
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first() ?: 'Validasi data tidak berhasil!',
            'errors' => $validator->errors(),
        ], 422));
    }
}
