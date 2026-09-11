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
            'nama_lengkap'       => 'required|string|max:255',
            'nik'                => 'nullable|string|max:50',
            'umur'               => 'nullable|integer|min:0|max:120',
            'kategori_usia'      => 'nullable|string|max:50',
            'jenis_kelamin'      => 'nullable|string|in:L,P',
            'kelurahan'          => 'nullable|string|max:100',
            'kecamatan'          => 'nullable|string|max:100',
            'kabupaten'          => 'nullable|string|max:100',
            'alamat_lengkap'     => 'nullable|string',
            'no_telepon'         => 'nullable|string|max:30',
            'nama_pelapor'       => 'nullable|string|max:255',
            'batuk_2_minggu'     => 'nullable|string|in:Ya,Tidak',
            'bb_turun'           => 'nullable|string|in:Ya,Tidak',
            'keringat_malam'     => 'nullable|string|in:Ya,Tidak',
            'kontak_tb'          => 'nullable|string|in:Ya,Tidak',
            'sudah_pengobatan'   => 'nullable|string|in:Sudah,Belum',
            // Optional advanced fields from AI/import
            'report_type'            => 'nullable|string|in:tb_03,tb_06',
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
