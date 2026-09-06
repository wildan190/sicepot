<?php

namespace App\Modules\TBC\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap'           => 'required|string|max:255',
            'nik'                    => 'nullable|string|max:50',
            'umur'                   => 'nullable|integer|min:0|max:120',
            'jenis_kelamin'          => 'nullable|string|in:L,P',
            'kabupaten'              => 'nullable|string|max:100',
            'kecamatan'              => 'nullable|string|max:100',
            'kelurahan'              => 'nullable|string|max:100',
            'alamat_lengkap'         => 'nullable|string',
            'status_pengobatan'      => 'nullable|string|max:100',
            'tipe_diagnosis'         => 'nullable|string|max:100',
            'lokasi_anatomi'         => 'nullable|string|max:100',
            'hasil_diagnosis'        => 'nullable|string|max:100',
            'hasil_akhir_pengobatan' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap pasien wajib diisi.',
        ];
    }
}
