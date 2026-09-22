<?php

namespace App\Modules\TBC\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbContactInvestigation extends Model
{
    use HasFactory;

    protected $table = 'tb_contact_investigations';

    protected $fillable = [
        'fasyankes_name',
        'fasyankes_code',
        'periode',
        'petugas_investigasi',
        'fasyankes_kader',
        'dirujuk_oleh',
        'kasus_indeks_sitb',
        'kasus_indeks_nama',
        'kasus_indeks_umur',
        'kasus_indeks_tipe_diagnosis',
        'kasus_indeks_tgl_diagnosis',
        'kasus_indeks_jenis',
        'nama_kontak',
        'nik_kontak',
        'umur_kontak',
        'jenis_kelamin_kontak',
        'alamat_kontak',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'metode_ik',
        'jenis_kontak',
        'tanggal_investigasi',
        'diperiksa_toraks',
        'gejala_batuk',
        'gejala_lain',
        'faktor_risiko',
        'status_rujukan_terduga',
        'hasil_evaluasi',
        'status_tpt',
        'raw_attributes',
    ];

    protected $casts = [
        'raw_attributes' => 'array',
        'umur_kontak' => 'integer',
        'kasus_indeks_umur' => 'integer',
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

    /**
     * Relationship to Index Patient in tb_patients
     */
    public function patient()
    {
        return $this->belongsTo(TbPatient::class, 'kasus_indeks_sitb', 'no_reg_sitb');
    }
}
