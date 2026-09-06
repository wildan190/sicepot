<?php

namespace App\Modules\TBC\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'report_type'            => 'required|string|in:tb_03,tb_06',
            'umur'                   => 'nullable|integer|min:0|max:120',
            'jenis_kelamin'          => 'nullable|string|in:L,P',
            'kabupaten'              => 'nullable|string|max:100',
            'kecamatan'              => 'nullable|string|max:100',
            'kelurahan'              => 'nullable|string|max:100',
            'alamat_lengkap'         => 'nullable|string',
            'hasil_diagnosis'        => 'nullable|string|max:100',
            'hasil_akhir_pengobatan' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap pasien wajib diisi.',
            'report_type.required'  => 'Tipe register (TB-03 / TB-06) wajib dipilih.',
            'report_type.in'        => 'Tipe register tidak valid.',
        ];
    }
}
