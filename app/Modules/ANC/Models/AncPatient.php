<?php

namespace App\Modules\ANC\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AncPatient extends Model
{
    use HasFactory;

    protected $table = 'anc_patients';

    protected $fillable = [
        'fasyankes_name', 'fasyankes_code', 'bulan', 'bulan_kunjungan', 'tahun',
        'nik', 'no_telepon', 'no_reg_fasyankes', 'no_rekam_medis', 'no_bpjs',
        'nama_lengkap', 'nama_suami', 'tanggal_lahir',
        'umur', 'pekerjaan', 'pendidikan',
        'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'alamat_lengkap',
        'golongan_darah', 'gravida', 'para', 'abortus',
        'usia_kehamilan', 'hpht', 'hpl',
        'tanggal_kunjungan', 'kunjungan_ke', 'jenis_kunjungan',
        'berat_badan', 'tinggi_badan', 'lila',
        'tekanan_darah_sistolik', 'tekanan_darah_diastolik',
        'tinggi_fundus_uteri', 'presentasi_janin', 'denyut_jantung_janin',
        'status_imunisasi_tt',
        'hb', 'status_anemia', 'gds', 'protein_urine',
        'hbsag', 'hiv_status', 'sifilis_status',
        'mendapat_fe', 'mendapat_vit_a', 'p4k',
        'faktor_risiko', 'risiko_tinggi', 'status_risti',
        'skor_poedji_rochjati', 'kategori_poedji_rochjati', 'rekomendasi_faskes', 'calon_pendonor',
        'dirujuk_ke', 'alasan_rujukan',
        'status_kehamilan', 'tempat_bersalin', 'penolong_persalinan',
        'tanggal_bersalin', 'komplikasi_persalinan',
        'kondisi_bayi', 'berat_lahir_bayi', 'catatan',
        'raw_attributes',
    ];

    protected $casts = [
        'raw_attributes'           => 'array',
        'umur'                     => 'integer',
        'gravida'                  => 'integer',
        'para'                     => 'integer',
        'abortus'                  => 'integer',
        'mendapat_fe'              => 'boolean',
        'mendapat_vit_a'           => 'boolean',
        'tanggal_lahir'            => 'date',
        'hpht'                     => 'date',
        'hpl'                      => 'date',
        'tanggal_kunjungan'        => 'date',
        'tanggal_bersalin'         => 'date',
        'berat_badan'              => 'decimal:2',
        'tinggi_badan'             => 'decimal:2',
        'lila'                     => 'decimal:2',
        'berat_lahir_bayi'         => 'decimal:2',
        'tekanan_darah_sistolik'   => 'decimal:1',
        'tekanan_darah_diastolik'  => 'decimal:1',
        'tinggi_fundus_uteri'      => 'decimal:2',
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
