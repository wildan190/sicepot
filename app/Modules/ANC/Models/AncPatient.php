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

    /**
     * Hitung HPL (Hari Perkiraan Lahir) berdasarkan rumus Naegele:
     * Tanggal + 7, Bulan - 3, Tahun + 1
     * (Untuk bulan 1-3: Tanggal + 7, Bulan + 9, Tahun tetap)
     */
    public static function calculateHpl(?string $hpht): ?string
    {
        if (empty($hpht)) {
            return null;
        }

        try {
            $c = \Carbon\Carbon::parse($hpht);
            $day = $c->day + 7;
            if ($c->month >= 4) {
                $month = $c->month - 3;
                $year  = $c->year + 1;
            } else {
                $month = $c->month + 9;
                $year  = $c->year;
            }

            return \Carbon\Carbon::create($year, $month, 1)->addDays($day - 1)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Hitung Usia Kehamilan dalam satuan minggu dari HPHT hingga tanggal acuan (atau hari ini)
     */
    public static function calculateUsiaKehamilan(?string $hpht, ?string $refDate = null): ?int
    {
        if (empty($hpht)) {
            return null;
        }

        try {
            $hphtDate = \Carbon\Carbon::parse($hpht)->startOfDay();
            $ref = !empty($refDate) ? \Carbon\Carbon::parse($refDate)->startOfDay() : now()->startOfDay();
            $diffDays = $hphtDate->diffInDays($ref, false);
            if ($diffDays < 0) {
                return 0;
            }

            return (int) floor($diffDays / 7);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Deteksi faktor risiko klinis otomatis dari data fisik & laboratorium
     */
    public static function detectFaktorRisiko(array|AncPatient $data): ?string
    {
        $get = fn($k) => is_array($data) ? ($data[$k] ?? null) : ($data->{$k} ?? null);

        $risks = [];
        $existing = (string) $get('faktor_risiko');
        if (!empty(trim($existing))) {
            $parts = array_map('trim', explode(',', $existing));
            foreach ($parts as $p) {
                if ($p !== '') {
                    $risks[] = $p;
                }
            }
        }

        // 1. KEK dari LiLA < 23.5 cm
        $lila = $get('lila');
        if ($lila !== null && (float) $lila > 0 && (float) $lila < 23.5) {
            if (!in_array('KEK', $risks, true)) {
                $risks[] = 'KEK';
            }
        }

        // 2. Anemia dari Hb < 11.0 g/dL
        $hb = $get('hb');
        if ($hb !== null) {
            $hbNum = (float) str_replace(',', '.', (string) $hb);
            if ($hbNum > 0 && $hbNum < 11.0 && !in_array('Anemia', $risks, true)) {
                $risks[] = 'Anemia';
            }
        }

        // 3. Hipertensi / HDK (TD Sistolik >= 140 atau Diastolik >= 90)
        $tdSistol = $get('tekanan_darah_sistolik');
        $tdDiastol = $get('tekanan_darah_diastolik');
        if (($tdSistol !== null && (float) $tdSistol >= 140) || ($tdDiastol !== null && (float) $tdDiastol >= 90)) {
            if (!in_array('HDK', $risks, true) && !in_array('Hipertensi', $risks, true)) {
                $risks[] = 'HDK';
            }
        }

        // 4. Umur Risti (< 17 th atau >= 35 th)
        $umur = $get('umur');
        if ($umur !== null) {
            $u = (int) $umur;
            if ($u > 0 && $u < 17 && !in_array('Primi Muda (< 17 th)', $risks, true)) {
                $risks[] = 'Primi Muda (< 17 th)';
            } elseif ($u >= 35 && !in_array('Umur Hamil Lanjut (≥ 35 th)', $risks, true)) {
                $risks[] = 'Umur Hamil Lanjut (≥ 35 th)';
            }
        }

        // 5. Tinggi Badan Kurang (< 145 cm)
        $tb = $get('tinggi_badan');
        if ($tb !== null && (float) $tb > 0 && (float) $tb < 145) {
            if (!in_array('TB < 145 cm', $risks, true)) {
                $risks[] = 'TB < 145 cm';
            }
        }

        // 6. Riwayat Paritas & Abortus
        $abortus = $get('abortus');
        if ($abortus !== null && (int) $abortus > 0 && !in_array('Riwayat Abortus', $risks, true)) {
            $risks[] = 'Riwayat Abortus';
        }

        $para = $get('para');
        if ($para !== null && (int) $para >= 4 && !in_array('Grande Multi', $risks, true)) {
            $risks[] = 'Grande Multi';
        }

        return !empty($risks) ? implode(', ', array_unique($risks)) : null;
    }

    /**
     * Accessor: Auto-derive Usia Kehamilan jika null di database tetapi ada HPHT
     */
    public function getUsiaKehamilanAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $hpht = $this->attributes['hpht'] ?? null;
        if (!empty($hpht)) {
            $tglKunjungan = $this->attributes['tanggal_kunjungan'] ?? null;
            return (string) static::calculateUsiaKehamilan($hpht, $tglKunjungan);
        }

        return null;
    }

    /**
     * Accessor: Auto-derive HPL dari HPHT jika belum ada
     */
    public function getHplAttribute($value)
    {
        if (!empty($value)) {
            return $this->asDate($value);
        }

        $hpht = $this->attributes['hpht'] ?? null;
        if (!empty($hpht)) {
            $calc = static::calculateHpl($hpht);
            return $calc ? $this->asDate($calc) : null;
        }

        return null;
    }

    /**
     * Umur dalam tahun dari tanggal lahir.
     */
    public static function calculateUmur($tanggalLahir): ?int
    {
        if (empty($tanggalLahir)) {
            return null;
        }

        try {
            $age = (int) \Carbon\Carbon::parse($tanggalLahir)->age;

            return ($age >= 0 && $age <= 120) ? $age : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Tanggal lahir dari NIK 16 digit (hari +40 untuk perempuan).
     */
    public static function birthDateFromNik(?string $nik): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $nik);
        if (strlen($digits) !== 16) {
            return null;
        }

        $day = (int) substr($digits, 6, 2);
        $month = (int) substr($digits, 8, 2);
        $yy = (int) substr($digits, 10, 2);
        if ($day > 40) {
            $day -= 40;
        }
        if ($day < 1 || $day > 31 || $month < 1 || $month > 12) {
            return null;
        }

        $year = $yy > (int) now()->format('y') ? 1900 + $yy : 2000 + $yy;
        if (now()->year - $year < 10) {
            $year -= 100;
        }

        try {
            return \Carbon\Carbon::createSafe($year, $month, $day)?->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    protected static function booted(): void
    {
        static::saving(function (AncPatient $patient) {
            if (empty($patient->tanggal_lahir) && !empty($patient->nik)) {
                $dob = static::birthDateFromNik($patient->nik);
                if ($dob) {
                    $patient->tanggal_lahir = $dob;
                }
            }

            if (empty($patient->umur) && !empty($patient->tanggal_lahir)) {
                $patient->umur = static::calculateUmur($patient->tanggal_lahir);
            }

            // Auto compute HPL dari HPHT dengan rumus: tgl + 7, bulan - 3, tahun + 1
            if (!empty($patient->hpht) && empty($patient->hpl)) {
                $patient->hpl = static::calculateHpl($patient->hpht);
            }

            // Auto compute usia_kehamilan dari HPHT
            if (!empty($patient->hpht) && empty($patient->usia_kehamilan)) {
                $patient->usia_kehamilan = (string) static::calculateUsiaKehamilan($patient->hpht, $patient->tanggal_kunjungan);
            }

            // Auto detect faktor risiko jika belum ada atau lengkapi
            $detected = static::detectFaktorRisiko($patient);
            if (!empty($detected)) {
                if (empty($patient->faktor_risiko)) {
                    $patient->faktor_risiko = $detected;
                } else {
                    $existing = array_map('trim', explode(',', $patient->faktor_risiko));
                    $new = array_map('trim', explode(',', $detected));
                    $merged = array_unique(array_filter(array_merge($existing, $new)));
                    $patient->faktor_risiko = implode(', ', $merged);
                }
            }

            // Pastikan status_risti terupdate jika ada faktor risiko
            if (!empty($patient->faktor_risiko) && (empty($patient->status_risti) || $patient->status_risti === 'Normal')) {
                $fr = strtolower($patient->faktor_risiko);
                if (str_contains($fr, 'kek') || str_contains($fr, 'anemia') || str_contains($fr, 'hdk') ||
                    str_contains($fr, 'sc') || str_contains($fr, 'sesar') || str_contains($fr, 'abortus') ||
                    str_contains($fr, 'lanjut') || str_contains($fr, 'muda')) {
                    $patient->status_risti = 'Risiko Tinggi';
                }
            }
        });
    }
}
