<?php

namespace App\Modules\Stunting\Models;

use Illuminate\Database\Eloquent\Model;

class StuntingPatient extends Model
{
    protected $table = 'stunting_patients';

    protected $fillable = [
        'nik', 'nama', 'jenis_kelamin',
        'tanggal_lahir', 'bb_lahir', 'tb_lahir',
        'nama_ortu', 'prov', 'kab_kota', 'kec', 'puskesmas', 'desa', 'posyandu',
        'rt', 'rw', 'alamat', 'usia_saat_ukur',
        'tanggal_pengukuran', 'berat', 'tinggi', 'cara_ukur', 'lila',
        'bbu_kategori', 'bbu_zscore',
        'tbu_kategori', 'tbu_zscore',
        'bbtb_kategori', 'bbtb_zscore',
        'naik_berat_badan',
        'jml_vit_a', 'kpsp', 'kia', 'kelas_ibu', 'mbg',
        'test_mantoux', 'test_hemoglobin', 'konsul_spa',
    ];

    protected $casts = [
        'tanggal_lahir'       => 'date',
        'tanggal_pengukuran'  => 'date',
        'bb_lahir'            => 'decimal:2',
        'tb_lahir'            => 'decimal:1',
        'berat'               => 'decimal:2',
        'tinggi'              => 'decimal:1',
        'lila'                => 'decimal:1',
        'bbu_zscore'          => 'decimal:2',
        'tbu_zscore'          => 'decimal:2',
        'bbtb_zscore'         => 'decimal:2',
    ];
}
