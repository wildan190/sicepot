<?php

namespace App\Modules\TBC\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbPatient extends Model
{
    use HasFactory;

    protected $table = 'tb_patients';

    protected $fillable = [
        'report_type',
        'fasyankes_name',
        'fasyankes_code',
        'no_urut',
        'no_identitas_sediaan',
        'no_reg_fasyankes',
        'no_reg_kab_kota',
        'no_rekam_medis',
        'nik',
        'no_reg_sitb',
        'no_reg_terduga',
        'no_reg_pasien',
        'no_bpjs',
        'nama_lengkap',
        'umur',
        'jenis_kelamin',
        'pekerjaan',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'alamat_lengkap',
        'bulan',
        'tanggal_daftar',
        'tanggal_mulai_pengobatan',
        'status_pengobatan',
        'tipe_diagnosis',
        'lokasi_anatomi',
        'riwayat_pengobatan',
        'status_hiv',
        'riwayat_dm',
        'hasil_tcm',
        'hasil_mikroskopis',
        'hasil_diagnosis',
        'hasil_akhir_pengobatan',
        'raw_attributes',
    ];

    protected $casts = [
        'raw_attributes' => 'array',
        'umur' => 'integer',
    ];

    public function setKabupatenAttribute($value)
    {
        $this->attributes['kabupaten'] = \App\Services\RegionHelper::normalizeKabupaten($value);
    }

    public function setKelurahanAttribute($value)
    {
        $this->attributes['kelurahan'] = \App\Services\RegionHelper::normalizeKelurahan($value);
    }

    public function setKecamatanAttribute($value)
    {
        $this->attributes['kecamatan'] = \App\Services\RegionHelper::normalizeKelurahan($value);
    }
}
