<?php

namespace App\Modules\ANC\Requests;

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
            'nama_lengkap'            => 'sometimes|required|string|max:255',
            'nama_suami'              => 'nullable|string|max:255',
            'no_telepon'              => 'nullable|string|max:50',
            'tanggal_lahir'           => 'nullable|date',
            'nik'                     => 'nullable|string|max:50',
            'no_rekam_medis'          => 'nullable|string|max:50',
            'umur'                    => 'nullable|integer|min:10|max:80',
            'gravida'                 => 'nullable|integer|min:1|max:20',
            'para'                    => 'nullable|integer|min:0|max:20',
            'abortus'                 => 'nullable|integer|min:0|max:20',
            'usia_kehamilan'          => 'nullable|string|max:50',
            'hpht'                    => 'nullable|date',
            'hpl'                     => 'nullable|date',
            'kunjungan_ke'            => 'nullable|string|max:50',
            'tanggal_kunjungan'       => 'nullable|date',
            'kabupaten'               => 'nullable|string|max:100',
            'kecamatan'               => 'nullable|string|max:100',
            'kelurahan'               => 'nullable|string|max:100',
            'alamat_lengkap'          => 'nullable|string',
            'hb'                      => 'nullable|string|max:20',
            'status_anemia'           => 'nullable|string|max:50',
            'lila'                    => 'nullable|numeric|min:0|max:100',
            'status_risti'            => 'nullable|string|max:50',
            'skor_poedji_rochjati'    => 'nullable|integer|min:2|max:50',
            'kategori_poedji_rochjati'=> 'nullable|string|max:50',
            'rekomendasi_faskes'      => 'nullable|string|max:100',
            'calon_pendonor'          => 'nullable|string|max:255',
            'golongan_darah'          => 'nullable|string|max:10',
            'faktor_risiko'           => 'nullable|string|max:255',
            'dirujuk_ke'              => 'nullable|string|max:100',
            'alasan_rujukan'          => 'nullable|string|max:255',
            'fasyankes_name'          => 'nullable|string|max:150',
            'catatan'                 => 'nullable|string',
        ];
    }
}
