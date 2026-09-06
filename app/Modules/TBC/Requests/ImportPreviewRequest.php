<?php

namespace App\Modules\TBC\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File harus diunggah.',
            'file.mimes'    => 'Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 50MB.',
        ];
    }
}
