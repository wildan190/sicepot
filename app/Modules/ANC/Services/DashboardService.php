<?php

namespace App\Modules\ANC\Services;

use App\Modules\ANC\Models\AncPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Known coordinates per kelurahan with kecamatan grouping.
     */
    protected array $knownCoords = [
        // Kec. Pagedangan
        'pagedangan'     => ['lat' => -6.3053, 'lng' => 106.6021, 'kecamatan' => 'Pagedangan'],
        'jatake'         => ['lat' => -6.3150, 'lng' => 106.5890, 'kecamatan' => 'Pagedangan'],
        'cijantra'       => ['lat' => -6.3210, 'lng' => 106.6110, 'kecamatan' => 'Pagedangan'],
        'karang tengah'  => ['lat' => -6.3010, 'lng' => 106.5920, 'kecamatan' => 'Pagedangan'],
        'lengkong kulon' => ['lat' => -6.2950, 'lng' => 106.6210, 'kecamatan' => 'Pagedangan'],
        'kadusirung'     => ['lat' => -6.3280, 'lng' => 106.5980, 'kecamatan' => 'Pagedangan'],
        'malang nengah'  => ['lat' => -6.3350, 'lng' => 106.5850, 'kecamatan' => 'Pagedangan'],
        'situgadung'     => ['lat' => -6.3110, 'lng' => 106.6340, 'kecamatan' => 'Pagedangan'],
        'medang'         => ['lat' => -6.2680, 'lng' => 106.6180, 'kecamatan' => 'Pagedangan'],
        // Kec. Curug
        'curug'          => ['lat' => -6.2620, 'lng' => 106.5560, 'kecamatan' => 'Curug'],
        'curug wetan'    => ['lat' => -6.2550, 'lng' => 106.5640, 'kecamatan' => 'Curug'],
        'binong'         => ['lat' => -6.2500, 'lng' => 106.5480, 'kecamatan' => 'Curug'],
        'kadu jaya'      => ['lat' => -6.2440, 'lng' => 106.5390, 'kecamatan' => 'Curug'],
        // Kec. Panongan
        'panongan'       => ['lat' => -6.2890, 'lng' => 106.5410, 'kecamatan' => 'Panongan'],
        'mekar bakti'    => ['lat' => -6.2810, 'lng' => 106.5320, 'kecamatan' => 'Panongan'],
        'ciakar'         => ['lat' => -6.2960, 'lng' => 106.5270, 'kecamatan' => 'Panongan'],
        // Kec. Legok
        'legok'          => ['lat' => -6.3380, 'lng' => 106.5620, 'kecamatan' => 'Legok'],
        'rancagong'      => ['lat' => -6.3460, 'lng' => 106.5510, 'kecamatan' => 'Legok'],
        'babakan'        => ['lat' => -6.3520, 'lng' => 106.5700, 'kecamatan' => 'Legok'],
        // Kec. Cisauk
        'cisauk'         => ['lat' => -6.3450, 'lng' => 106.6420, 'kecamatan' => 'Cisauk'],
        'sampora'        => ['lat' => -6.3530, 'lng' => 106.6500, 'kecamatan' => 'Cisauk'],
        // Kec. Kelapa Dua
        'kelapa dua'     => ['lat' => -6.2480, 'lng' => 106.6140, 'kecamatan' => 'Kelapa Dua'],
        'bencongan'      => ['lat' => -6.2390, 'lng' => 106.6220, 'kecamatan' => 'Kelapa Dua'],
        'sumur pong'     => ['lat' => -6.2420, 'lng' => 106.6060, 'kecamatan' => 'Kelapa Dua'],
    ];

    /** Kecamatan center coordinates for auto-zoom. */
    protected array $kecamatanCenter = [
        'Pagedangan' => ['lat' => -6.3100, 'lng' => 106.6050, 'zoom' => 13],
        'Curug'      => ['lat' => -6.2560, 'lng' => 106.5520, 'zoom' => 13],
        'Panongan'   => ['lat' => -6.2890, 'lng' => 106.5350, 'zoom' => 13],
        'Legok'      => ['lat' => -6.3460, 'lng' => 106.5600, 'zoom' => 13],
        'Cisauk'     => ['lat' => -6.3490, 'lng' => 106.6460, 'zoom' => 13],
        'Kelapa Dua' => ['lat' => -6.2430, 'lng' => 106.6140, 'zoom' => 13],
    ];

    public function getKabupatenList(): \Illuminate\Support\Collection
    {
        return AncPatient::whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->pluck('kabupaten')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKabupaten($k))
            ->unique()->sort()->values();
    }

    public function getKelurahanList(?string $kabupaten): \Illuminate\Support\Collection
    {
        $q = AncPatient::whereNotNull('kelurahan')->where('kelurahan', '!=', '');
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($q, $kabupaten);
        }
        return $q->pluck('kelurahan')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKelurahan($k))
            ->unique()->sort()->values();
    }

    /** Distinct kecamatan for the map widget dropdown. */
    public function getKecamatanList(?string $kabupaten): \Illuminate\Support\Collection
    {
        $q = AncPatient::whereNotNull('kecamatan')->where('kecamatan', '!=', '');
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($q, $kabupaten);
        }

        $fromDb = $q->pluck('kecamatan')
            ->map(fn ($k) => ucwords(strtolower(trim($k))))
            ->unique()->sort()->values();

        $fromCoords = collect(array_unique(array_column($this->knownCoords, 'kecamatan')))->sort()->values();

        return $fromDb->merge($fromCoords)->unique()->sort()->values();
    }

    /**
     * Build per-kelurahan GIS map data with RISTI detection layers.
     * Each point includes: total bumil, RISTI count, KEK, anemia, Poedji Rochjati score,
     * imminent deliveries (HPL ≤ 7 days), and fasyankes breakdown.
     */
    public function getMapData(Request $request): array
    {
        $kabupaten = $request->input('kabupaten');
        $kecamatan = $request->input('kecamatan');

        $query = AncPatient::query();
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($query, $kabupaten);
        }
        if (!empty($kecamatan)) {
            $query->whereRaw('LOWER(TRIM(kecamatan)) = ?', [strtolower(trim($kecamatan))]);
        }

        $today    = now()->toDateString();
        $in7Days  = now()->addDays(7)->toDateString();

        $rows = $query
            ->select(
                'kelurahan', 'kabupaten', 'kecamatan', 'fasyankes_name',
                DB::raw('count(*) as total'),
                // RISTI: Poedji KRT/KRST OR status_risti contains 'tinggi'
                DB::raw("sum(case when kategori_poedji_rochjati in ('KRT','KRST') or status_risti like '%tinggi%' then 1 else 0 end) as total_risti"),
                // KEK: LiLA < 23.5 cm
                DB::raw("sum(case when lila is not null and lila < 23.5 then 1 else 0 end) as total_kek"),
                // Anemia: Hb < 11
                DB::raw("sum(case when hb is not null and hb < 11 then 1 else 0 end) as total_anemia"),
                // Hipertensi / pre-eklamsi: TD sistolik ≥ 140
                DB::raw("sum(case when tekanan_darah_sistolik is not null and tekanan_darah_sistolik >= 140 then 1 else 0 end) as total_hiper"),
                // KRST (highest risk - rujuk RS PONEK)
                DB::raw("sum(case when kategori_poedji_rochjati = 'KRST' then 1 else 0 end) as total_krst"),
                // Imminent delivery: HPL within next 7 days and not yet delivered
                DB::raw("sum(case when hpl is not null and hpl <= '{$in7Days}' and hpl >= '{$today}' and tanggal_bersalin is null then 1 else 0 end) as total_imminent"),
                // HIV co-infection
                DB::raw("sum(case when hiv_status like '%positif%' then 1 else 0 end) as total_hiv")
            )
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten', 'kecamatan', 'fasyankes_name')
            ->orderByDesc('total')
            ->get();

        // Group by kelurahan to merge multiple fasyankes per kelurahan
        $byKelurahan = [];
        foreach ($rows as $idx => $row) {
            $kelKey = strtolower(trim($row->kelurahan ?? ''));
            if (!isset($byKelurahan[$kelKey])) {
                $coords = $this->resolveCoords($kelKey, $idx);
                $byKelurahan[$kelKey] = [
                    'kelurahan'      => $row->kelurahan,
                    'kabupaten'      => $row->kabupaten ?? 'Kab. Tangerang',
                    'kecamatan'      => $row->kecamatan
                        ? ucwords(strtolower(trim($row->kecamatan)))
                        : ($this->knownCoords[$kelKey]['kecamatan'] ?? '-'),
                    'lat'            => $coords['lat'],
                    'lng'            => $coords['lng'],
                    'total'          => 0,
                    'total_risti'    => 0,
                    'total_kek'      => 0,
                    'total_anemia'   => 0,
                    'total_hiper'    => 0,
                    'total_krst'     => 0,
                    'total_imminent' => 0,
                    'total_hiv'      => 0,
                    'fasyankes'      => [],
                ];
            }
            $byKelurahan[$kelKey]['total']          += (int) $row->total;
            $byKelurahan[$kelKey]['total_risti']    += (int) $row->total_risti;
            $byKelurahan[$kelKey]['total_kek']      += (int) $row->total_kek;
            $byKelurahan[$kelKey]['total_anemia']   += (int) $row->total_anemia;
            $byKelurahan[$kelKey]['total_hiper']    += (int) $row->total_hiper;
            $byKelurahan[$kelKey]['total_krst']     += (int) $row->total_krst;
            $byKelurahan[$kelKey]['total_imminent'] += (int) $row->total_imminent;
            $byKelurahan[$kelKey]['total_hiv']      += (int) $row->total_hiv;

            if (!empty($row->fasyankes_name)) {
                $byKelurahan[$kelKey]['fasyankes'][] = [
                    'name'        => $row->fasyankes_name,
                    'total'       => (int) $row->total,
                    'total_risti' => (int) $row->total_risti,
                ];
            }
        }

        $points = array_values($byKelurahan);

        // Compute RISTI density level per point (for color coding)
        foreach ($points as &$pt) {
            $ristiRatio = $pt['total'] > 0 ? ($pt['total_risti'] / $pt['total']) : 0;
            if ($pt['total_krst'] > 0 || $ristiRatio >= 0.4) {
                $pt['risk_level'] = 'critical';   // KRST present or ≥40% RISTI
            } elseif ($pt['total_risti'] > 0 || $ristiRatio >= 0.2) {
                $pt['risk_level'] = 'high';        // KRT / some RISTI
            } else {
                $pt['risk_level'] = 'normal';      // KRR / bumil normal
            }
            $pt['risti_ratio'] = round($ristiRatio * 100, 1);
        }
        unset($pt);

        // Map center
        $center = ['lat' => -6.3053, 'lng' => 106.6021, 'zoom' => 12];
        $kecKey = ucwords(strtolower(trim($kecamatan ?? '')));
        if (!empty($kecamatan) && isset($this->kecamatanCenter[$kecKey])) {
            $center = $this->kecamatanCenter[$kecKey];
        } elseif (!empty($points)) {
            $avgLat = array_sum(array_column($points, 'lat')) / count($points);
            $avgLng = array_sum(array_column($points, 'lng')) / count($points);
            $center = ['lat' => round($avgLat, 5), 'lng' => round($avgLng, 5), 'zoom' => 13];
        }

        return [
            'points'             => $points,
            'center'             => $center,
            'selected_kecamatan' => $kecamatan ?? '',
        ];
    }

    protected function resolveCoords(string $kelKey, int $idx): array
    {
        if (isset($this->knownCoords[$kelKey])) {
            return [
                'lat' => round($this->knownCoords[$kelKey]['lat'], 5),
                'lng' => round($this->knownCoords[$kelKey]['lng'], 5),
            ];
        }
        return [
            'lat' => round(-6.3053 + (sin($idx * 1.5) * 0.045), 5),
            'lng' => round(106.6021 + (cos($idx * 1.5) * 0.045), 5),
        ];
    }

    public function getStatsData(Request $request): array
    {
        $kabupaten = $request->input('kabupaten');
        $kelurahan = $request->input('kelurahan');
        $bulan     = $request->input('bulan');
        $search    = $request->input('search');

        $base = AncPatient::query();

        if (!empty($kabupaten)) { \App\Services\RegionHelper::filterKabupaten($base, $kabupaten); }
        if (!empty($kelurahan)) { \App\Services\RegionHelper::filterKelurahan($base, $kelurahan); }
        if (!empty($bulan))     { $base->where('bulan', $bulan); }
        if (!empty($search)) {
            $base->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nama_suami', 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_rekam_medis', 'like', "%{$search}%");
            });
        }

        $totalAll      = (clone $base)->count();
        $totalK1       = (clone $base)->where('kunjungan_ke', 'like', '%K1%')->count();
        $totalK4       = (clone $base)->where('kunjungan_ke', 'like', '%K4%')->count();
        $totalRisti    = (clone $base)->where(function ($q) {
            $q->where('status_risti', 'like', '%tinggi%')
                ->orWhere('risiko_tinggi', '!=', null)
                ->orWhere('risiko_tinggi', '!=', '');
        })->count();
        $totalAnemia   = (clone $base)->where('status_anemia', 'like', '%anemia%')->count();
        $totalRujukan  = (clone $base)->whereNotNull('dirujuk_ke')->where('dirujuk_ke', '!=', '')->count();
        $totalBersalin = (clone $base)->whereNotNull('tanggal_bersalin')->count();
        $totalFe       = (clone $base)->where('mendapat_fe', true)->count();
        $totalNormal   = (clone $base)->where(function ($q) {
            $q->where('status_risti', 'like', '%normal%')
                ->orWhereNull('status_risti')
                ->orWhere('status_risti', '');
        })->count();

        $kelurahanDist = (clone $base)
            ->select('kelurahan', DB::raw('count(*) as total'))
            ->whereNotNull('kelurahan')->where('kelurahan', '!=', '')
            ->groupBy('kelurahan')->orderByDesc('total')->limit(10)->get();

        $monthOrder = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];
        $monthlyRaw = (clone $base)
            ->select('bulan', DB::raw('count(*) as total'))
            ->whereNotNull('bulan')->where('bulan', '!=', '')
            ->groupBy('bulan')->pluck('total', 'bulan')->all();

        if (empty($monthlyRaw)) {
            $dateRecords = (clone $base)->select('hpht', 'created_at', 'tanggal_kunjungan')->get();
            $indonesianMonthMap = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            foreach ($dateRecords as $rec) {
                $mNum = null;
                if (!empty($rec->tanggal_kunjungan)) {
                    $mNum = (int) \Carbon\Carbon::parse($rec->tanggal_kunjungan)->format('n');
                } elseif (!empty($rec->hpht) && $rec->hpht->year > 2000) {
                    $mNum = (int) $rec->hpht->format('n');
                } elseif (!empty($rec->created_at)) {
                    $mNum = (int) $rec->created_at->format('n');
                }
                if ($mNum && isset($indonesianMonthMap[$mNum])) {
                    $mName = $indonesianMonthMap[$mNum];
                    $monthlyRaw[$mName] = ($monthlyRaw[$mName] ?? 0) + 1;
                }
            }
        }

        $allMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $monthlyLabels = [];
        $monthlyValues = [];
        foreach ($allMonths as $m) {
            $monthlyLabels[] = $m;
            $monthlyValues[] = (int) ($monthlyRaw[$m] ?? 0);
        }

        $kunjunganDist = (clone $base)
            ->select('kunjungan_ke', DB::raw('count(*) as total'))
            ->whereNotNull('kunjungan_ke')->where('kunjungan_ke', '!=', '')
            ->groupBy('kunjungan_ke')->orderBy('kunjungan_ke')->get();

        $umurMuda      = (clone $base)->whereNotNull('umur')->where('umur', '<', 20)->count();
        $umurProduktif = (clone $base)->whereNotNull('umur')->whereBetween('umur', [20, 35])->count();
        $umurRisti     = (clone $base)->whereNotNull('umur')->where('umur', '>', 35)->count();

        $patients = (clone $base)->orderByDesc('id')->paginate(15)->withQueryString();

        $tomorrow = now()->addDay()->toDateString();
        $imminentDeliveries = AncPatient::whereNotNull('hpl')
            ->whereNull('tanggal_bersalin')
            ->whereDate('hpl', '=', $tomorrow)
            ->get(['id', 'nama_lengkap', 'nik', 'no_rekam_medis', 'umur', 'hpl', 'hpht',
                   'kabupaten', 'kelurahan', 'status_risti', 'faktor_risiko', 'fasyankes_name']);

        $totalKrr  = (clone $base)->where('kategori_poedji_rochjati', 'KRR')->orWhereNull('kategori_poedji_rochjati')->count();
        $totalKrt  = (clone $base)->where('kategori_poedji_rochjati', 'KRT')->count();
        $totalKrst = (clone $base)->where('kategori_poedji_rochjati', 'KRST')->count();

        // Legacy map_data for initial page load
        $ancKelurahanAll = (clone $base)
            ->select('kelurahan', 'kabupaten', DB::raw('count(*) as total'),
                DB::raw("sum(case when kategori_poedji_rochjati in ('KRT', 'KRST') or status_risti like '%tinggi%' then 1 else 0 end) as total_risti"))
            ->whereNotNull('kelurahan')->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten')->orderByDesc('total')->get();

        $ancMapPoints = [];
        foreach ($ancKelurahanAll as $idx => $row) {
            $key    = strtolower(trim($row->kelurahan));
            $coords = $this->resolveCoords($key, $idx);
            $ancMapPoints[] = [
                'kelurahan'   => $row->kelurahan,
                'kabupaten'   => $row->kabupaten ?? 'Kab. Tangerang',
                'kecamatan'   => $this->knownCoords[$key]['kecamatan'] ?? '-',
                'total'       => (int) $row->total,
                'total_risti' => (int) $row->total_risti,
                'lat'         => $coords['lat'],
                'lng'         => $coords['lng'],
            ];
        }

        return [
            'kpi' => [
                'total_all'      => $totalAll,
                'total_k1'       => $totalK1,
                'total_k4'       => $totalK4,
                'total_risti'    => $totalRisti,
                'total_anemia'   => $totalAnemia,
                'total_rujukan'  => $totalRujukan,
                'total_bersalin' => $totalBersalin,
                'total_fe'       => $totalFe,
                'total_normal'   => $totalNormal,
                'umur_muda'      => $umurMuda,
                'umur_produktif' => $umurProduktif,
                'umur_risti'     => $umurRisti,
                'total_h1'       => $imminentDeliveries->count(),
                'total_krr'      => $totalKrr,
                'total_krt'      => $totalKrt,
                'total_krst'     => $totalKrst,
            ],
            'kelurahan_chart' => [
                'labels' => $kelurahanDist->pluck('kelurahan')->all(),
                'values' => $kelurahanDist->pluck('total')->all(),
            ],
            'monthly_chart' => [
                'labels' => $monthlyLabels,
                'values' => $monthlyValues,
            ],
            'kunjungan_chart' => [
                'labels' => $kunjunganDist->pluck('kunjungan_ke')->all(),
                'values' => $kunjunganDist->pluck('total')->all(),
            ],
            'age_chart' => [
                'labels' => ['< 20 th (Muda)', '20–35 th (Produktif)', '> 35 th (Risiko)'],
                'values' => [$umurMuda, $umurProduktif, $umurRisti],
            ],
            'poedji_chart' => [
                'labels' => ['KRR (BPM/Puskesmas)', 'KRT (Puskesmas PONED)', 'KRST (RS PONEK)'],
                'values' => [$totalKrr, $totalKrt, $totalKrst],
            ],
            'map_data'            => $ancMapPoints,
            'patients'            => $patients,
            'imminent_deliveries' => $imminentDeliveries,
        ];
    }
}
