<?php

namespace App\Modules\ANC\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportCommitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'temp_token' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'temp_token.required' => 'Token sesi import tidak ditemukan. Silakan upload ulang.',
        ];
    }
}
