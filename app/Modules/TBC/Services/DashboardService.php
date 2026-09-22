<?php

namespace App\Modules\TBC\Services;

use App\Modules\TBC\Models\TbPatient;
use App\Modules\TBC\Models\TbContactInvestigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Known coordinate map per kelurahan (Kec. Pagedangan & sekitarnya, Kab. Tangerang).
     * Kecamatan key added for grouping.
     */
    protected array $knownCoords = [
        // Kec. Pagedangan
        'pagedangan'     => ['lat' => -6.3053, 'lng' => 106.6021, 'kecamatan' => 'Pagedangan'],
        'jatake'         => ['lat' => -6.3150, 'lng' => 106.5890, 'kecamatan' => 'Pagedangan'],
        'cijantra'       => ['lat' => -6.3210, 'lng' => 106.6110, 'kecamatan' => 'Pagedangan'],
        'karang tengah'  => ['lat' => -6.3010, 'lng' => 106.5920, 'kecamatan' => 'Pagedangan'],
        'lengkong kulon' => ['lat' => -6.2950, 'lng' => 106.6210, 'kecamatan' => 'Pagedangan'],
        'kadusirung'     => ['lat' => -6.3280, 'lng' => 106.5980, 'kecamatan' => 'Pagedangan'],
        'kadu sirung'    => ['lat' => -6.3280, 'lng' => 106.5980, 'kecamatan' => 'Pagedangan'],
        'malang nengah'  => ['lat' => -6.3350, 'lng' => 106.5850, 'kecamatan' => 'Pagedangan'],
        'situgadung'     => ['lat' => -6.3110, 'lng' => 106.6340, 'kecamatan' => 'Pagedangan'],
        'situ gadung'    => ['lat' => -6.3110, 'lng' => 106.6340, 'kecamatan' => 'Pagedangan'],
        'medang'         => ['lat' => -6.2680, 'lng' => 106.6180, 'kecamatan' => 'Pagedangan'],
        'cicalengka'     => ['lat' => -6.3250, 'lng' => 106.5720, 'kecamatan' => 'Pagedangan'],
        'cihuni'         => ['lat' => -6.2750, 'lng' => 106.6300, 'kecamatan' => 'Pagedangan'],
        'jatirasa'       => ['lat' => -6.3150, 'lng' => 106.5890, 'kecamatan' => 'Pagedangan'],
        'nanggeleng'     => ['lat' => -6.3200, 'lng' => 106.6000, 'kecamatan' => 'Pagedangan'],
        'ciburuy'        => ['lat' => -6.3080, 'lng' => 106.6080, 'kecamatan' => 'Pagedangan'],
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
        'cirarab'        => ['lat' => -6.3400, 'lng' => 106.5400, 'kecamatan' => 'Legok'],
        // Kec. Cisauk
        'cisauk'         => ['lat' => -6.3450, 'lng' => 106.6420, 'kecamatan' => 'Cisauk'],
        'sampora'        => ['lat' => -6.3530, 'lng' => 106.6500, 'kecamatan' => 'Cisauk'],
        // Kec. Kelapa Dua
        'kelapa dua'     => ['lat' => -6.2480, 'lng' => 106.6140, 'kecamatan' => 'Kelapa Dua'],
        'bencongan'      => ['lat' => -6.2390, 'lng' => 106.6220, 'kecamatan' => 'Kelapa Dua'],
        'sumur pong'     => ['lat' => -6.2420, 'lng' => 106.6060, 'kecamatan' => 'Kelapa Dua'],
    ];

    /**
     * Default kecamatan center coordinates (for map centering).
     */
    protected array $kecamatanCenter = [
        'Pagedangan' => ['lat' => -6.3100, 'lng' => 106.6050, 'zoom' => 13],
        'Curug'      => ['lat' => -6.2560, 'lng' => 106.5520, 'zoom' => 13],
        'Panongan'   => ['lat' => -6.2890, 'lng' => 106.5350, 'zoom' => 13],
        'Legok'      => ['lat' => -6.3460, 'lng' => 106.5600, 'zoom' => 13],
        'Cisauk'     => ['lat' => -6.3490, 'lng' => 106.6460, 'zoom' => 13],
        'Kelapa Dua' => ['lat' => -6.2430, 'lng' => 106.6140, 'zoom' => 13],
    ];

    /** Retrieve distinct kabupaten list. */
    public function getKabupatenList(): \Illuminate\Support\Collection
    {
        return TbPatient::whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->pluck('kabupaten')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKabupaten($k))
            ->unique()->sort()->values();
    }

    /** Retrieve distinct kelurahan, optionally filtered by kabupaten. */
    public function getKelurahanList(?string $kabupaten): \Illuminate\Support\Collection
    {
        $query = TbPatient::whereNotNull('kelurahan')->where('kelurahan', '!=', '');
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($query, $kabupaten);
        }
        return $query->pluck('kelurahan')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKelurahan($k))
            ->unique()->sort()->values();
    }

    /** Retrieve distinct kecamatan, optionally filtered by kabupaten. */
    public function getKecamatanList(?string $kabupaten): \Illuminate\Support\Collection
    {
        $query = TbPatient::whereNotNull('kecamatan')->where('kecamatan', '!=', '');
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($query, $kabupaten);
        }

        $fromDb = $query->pluck('kecamatan')
            ->map(fn ($k) => ucwords(strtolower(trim($k))))
            ->unique()->sort()->values();

        // Merge with known kecamatan from coord map so there's always a usable list
        $fromCoords = collect(array_unique(array_column($this->knownCoords, 'kecamatan')))->sort()->values();

        return $fromDb->merge($fromCoords)->unique()->sort()->values();
    }

    /**
     * Build GIS map points per-kelurahan, filtered optionally by kecamatan.
     * Includes fasyankes clustering aggregation and density levels.
     */
    /**
     * Build GIS map points per-kelurahan, filtered optionally by kecamatan.
     * Integrates both Data Penderita TB (TB-03) and Investigasi Kontak (TB-16K).
     */
    public function getMapData(Request $request): array
    {
        $kabupaten = $request->input('kabupaten');
        $kecamatan = $request->input('kecamatan');

        // 1. Query Penderita TBC (tb_patients)
        $pQuery = TbPatient::query();
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($pQuery, $kabupaten);
        }
        if (!empty($kecamatan)) {
            $pQuery->whereRaw('LOWER(TRIM(kecamatan)) = ?', [strtolower(trim($kecamatan))]);
        }

        $pRows = $pQuery
            ->select(
                'kelurahan', 'kabupaten', 'kecamatan', 'fasyankes_name',
                DB::raw('count(*) as total'),
                DB::raw("sum(case when report_type = 'tb_03' then 1 else 0 end) as total_terkonfirmasi"),
                DB::raw("sum(case when report_type = 'tb_06' and batuk_2_minggu is null and bb_turun is null and keringat_malam is null and kontak_tb is null then 1 else 0 end) as total_terduga"),
                DB::raw("sum(case when report_type = 'tb_03' and (hasil_akhir_pengobatan is null or hasil_akhir_pengobatan = '') then 1 else 0 end) as total_active"),
                DB::raw("sum(case when hasil_akhir_pengobatan like '%Sembuh%' or hasil_akhir_pengobatan like '%Lengkap%' then 1 else 0 end) as total_sembuh")
            )
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten', 'kecamatan', 'fasyankes_name')
            ->get();

        // 2. Query Investigasi Kontak (tb_contact_investigations)
        $ikQuery = TbContactInvestigation::query();
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($ikQuery, $kabupaten);
        }
        if (!empty($kecamatan)) {
            $ikQuery->whereRaw('LOWER(TRIM(kecamatan)) = ?', [strtolower(trim($kecamatan))]);
        }

        $ikRows = $ikQuery
            ->select(
                'kelurahan', 'kabupaten', 'kecamatan',
                DB::raw('count(*) as total_investigasi'),
                DB::raw('count(distinct kasus_indeks_sitb) as total_indeks'),
                DB::raw("sum(case when jenis_kontak like '%Serumah%' then 1 else 0 end) as total_serumah"),
                DB::raw("sum(case when jenis_kontak like '%Erat%' then 1 else 0 end) as total_erat"),
                DB::raw("sum(case when hasil_evaluasi = 'Sakit TBC' then 1 else 0 end) as total_sakit"),
                DB::raw("sum(case when hasil_evaluasi = 'Terduga TBC' then 1 else 0 end) as total_terduga_ik")
            )
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten', 'kecamatan')
            ->get();

        $byKelurahan = [];

        // Ingest Penderita
        foreach ($pRows as $idx => $row) {
            $kelKey = strtolower(trim($row->kelurahan ?? ''));
            if (!isset($byKelurahan[$kelKey])) {
                $coords = $this->resolveCoords($kelKey, count($byKelurahan));
                $byKelurahan[$kelKey] = [
                    'kelurahan'                => $row->kelurahan,
                    'kabupaten'                => $row->kabupaten ?? 'Kab. Tangerang',
                    'kecamatan'                => $row->kecamatan ? ucwords(strtolower(trim($row->kecamatan))) : ($this->knownCoords[$kelKey]['kecamatan'] ?? '-'),
                    'lat'                      => $coords['lat'],
                    'lng'                      => $coords['lng'],
                    'penderita_total'          => 0,
                    'penderita_active'         => 0,
                    'penderita_terkonfirmasi'  => 0,
                    'penderita_sembuh'         => 0,
                    'investigasi_total'        => 0,
                    'investigasi_indeks'       => 0,
                    'investigasi_serumah'      => 0,
                    'investigasi_erat'         => 0,
                    'investigasi_sakit'        => 0,
                    'investigasi_terduga'      => 0,
                    'total'                    => 0,
                    'total_active'             => 0,
                    'fasyankes'                => [],
                ];
            }
            $byKelurahan[$kelKey]['penderita_total']         += (int) $row->total;
            $byKelurahan[$kelKey]['penderita_active']        += (int) $row->total_active;
            $byKelurahan[$kelKey]['penderita_terkonfirmasi'] += (int) $row->total_terkonfirmasi;
            $byKelurahan[$kelKey]['penderita_sembuh']        += (int) $row->total_sembuh;
            $byKelurahan[$kelKey]['total']                   += (int) $row->total;
            $byKelurahan[$kelKey]['total_active']            += (int) $row->total_active;

            if (!empty($row->fasyankes_name)) {
                $byKelurahan[$kelKey]['fasyankes'][] = [
                    'name'  => $row->fasyankes_name,
                    'total' => (int) $row->total,
                ];
            }
        }

        // Ingest Investigasi Kontak
        foreach ($ikRows as $idx => $row) {
            $kelKey = strtolower(trim($row->kelurahan ?? ''));
            if (!isset($byKelurahan[$kelKey])) {
                $coords = $this->resolveCoords($kelKey, count($byKelurahan));
                $byKelurahan[$kelKey] = [
                    'kelurahan'                => $row->kelurahan,
                    'kabupaten'                => $row->kabupaten ?? 'Kab. Tangerang',
                    'kecamatan'                => $row->kecamatan ? ucwords(strtolower(trim($row->kecamatan))) : ($this->knownCoords[$kelKey]['kecamatan'] ?? '-'),
                    'lat'                      => $coords['lat'],
                    'lng'                      => $coords['lng'],
                    'penderita_total'          => 0,
                    'penderita_active'         => 0,
                    'penderita_terkonfirmasi'  => 0,
                    'penderita_sembuh'         => 0,
                    'investigasi_total'        => 0,
                    'investigasi_indeks'       => 0,
                    'investigasi_serumah'      => 0,
                    'investigasi_erat'         => 0,
                    'investigasi_sakit'        => 0,
                    'investigasi_terduga'      => 0,
                    'total'                    => 0,
                    'total_active'             => 0,
                    'fasyankes'                => [],
                ];
            }
            $byKelurahan[$kelKey]['investigasi_total']   += (int) $row->total_investigasi;
            $byKelurahan[$kelKey]['investigasi_indeks']  += (int) $row->total_indeks;
            $byKelurahan[$kelKey]['investigasi_serumah'] += (int) $row->total_serumah;
            $byKelurahan[$kelKey]['investigasi_erat']    += (int) $row->total_erat;
            $byKelurahan[$kelKey]['investigasi_sakit']   += (int) $row->total_sakit;
            $byKelurahan[$kelKey]['investigasi_terduga'] += (int) $row->total_terduga_ik;
        }

        // Calculate visual offsets for dual points
        foreach ($byKelurahan as &$point) {
            $hasPenderita = $point['penderita_total'] > 0;
            $hasIK = $point['investigasi_total'] > 0;

            if ($hasPenderita && $hasIK) {
                // Side-by-side offset: Penderita slightly South-West, Investigasi slightly North-East
                $point['lat_penderita']   = round($point['lat'] - 0.0016, 5);
                $point['lng_penderita']   = round($point['lng'] - 0.0016, 5);
                $point['lat_investigasi'] = round($point['lat'] + 0.0016, 5);
                $point['lng_investigasi'] = round($point['lng'] + 0.0016, 5);
            } else {
                $point['lat_penderita']   = $point['lat'];
                $point['lng_penderita']   = $point['lng'];
                $point['lat_investigasi'] = $point['lat'];
                $point['lng_investigasi'] = $point['lng'];
            }
        }
        unset($point);

        $points = array_values($byKelurahan);

        // Determine center based on kecamatan filter
        $center = ['lat' => -6.3053, 'lng' => 106.6021, 'zoom' => 12];
        if (!empty($kecamatan) && isset($this->kecamatanCenter[ucwords(strtolower(trim($kecamatan)))])) {
            $center = $this->kecamatanCenter[ucwords(strtolower(trim($kecamatan)))];
        } elseif (!empty($points)) {
            // Auto-center on data centroid
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

    /** Resolve lat/lng for a kelurahan key, falling back to jitter around center. */
    protected function resolveCoords(string $kelKey, int $idx): array
    {
        if (isset($this->knownCoords[$kelKey])) {
            return [
                'lat' => round($this->knownCoords[$kelKey]['lat'], 5),
                'lng' => round($this->knownCoords[$kelKey]['lng'], 5),
            ];
        }
        return [
            'lat' => round(-6.3053 + (sin($idx * 1.7) * 0.045), 5),
            'lng' => round(106.6021 + (cos($idx * 1.7) * 0.045), 5),
        ];
    }

    /** Compute KPI statistics and chart data based on request filters. */
    public function getStatsData(Request $request): array
    {
        $kabupaten  = $request->input('kabupaten');
        $kelurahan  = $request->input('kelurahan');
        $reportType = $request->input('report_type');
        $search     = $request->input('search');

        $baseQuery = TbPatient::query();

        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($baseQuery, $kabupaten);
        }
        if (!empty($kelurahan)) {
            \App\Services\RegionHelper::filterKelurahan($baseQuery, $kelurahan);
        }
        if (!empty($reportType)) {
            $baseQuery->where('report_type', $reportType);
        }
        if (!empty($search)) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_reg_sitb', 'like', "%{$search}%")
                    ->orWhere('no_reg_terduga', 'like', "%{$search}%");
            });
        }

        // Query with geographic & text filters for specialized KPI cards
        $geoQuery = TbPatient::query();
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($geoQuery, $kabupaten);
        }
        if (!empty($kelurahan)) {
            \App\Services\RegionHelper::filterKelurahan($geoQuery, $kelurahan);
        }
        if (!empty($search)) {
            $geoQuery->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('no_reg_sitb', 'like', "%{$search}%")
                    ->orWhere('no_reg_terduga', 'like', "%{$search}%");
            });
        }

        // TB-06: Register Terduga TBC (Data Pelacakan Kasus Terduga)
        $tb06Query = (clone $geoQuery)->where('report_type', 'tb_06');
        $totalTerduga      = (clone $tb06Query)->count();
        $totalDiperiksaTcm = (clone $tb06Query)
            ->whereNotNull('hasil_tcm')
            ->where('hasil_tcm', '!=', '')
            ->count();
        // Total Pelacakan secara eksplisit bersumber dari file Register Terduga TBC (TB-06)
        $totalPelacakan    = $totalTerduga > 0 ? $totalTerduga : (clone $geoQuery)->count();
        $totalAll          = $totalPelacakan;

        // TB-03: Register Pasien TBC (Pengobatan OAT)
        $tb03Query = (clone $geoQuery)->where('report_type', 'tb_03');
        $totalTerkonfirmasi     = (clone $tb03Query)->count();
        $totalSedangPengobatan  = (clone $tb03Query)
            ->where(function ($q) {
                $q->whereNull('hasil_akhir_pengobatan')->orWhere('hasil_akhir_pengobatan', '');
            })->count();
        $totalSembuh            = (clone $tb03Query)->where('hasil_akhir_pengobatan', 'like', '%Sembuh%')->count();
        $totalPengobatanLengkap = (clone $tb03Query)->where('hasil_akhir_pengobatan', 'like', '%Lengkap%')->count();
        $totalPutusBerobat      = (clone $tb03Query)->where('hasil_akhir_pengobatan', 'like', '%Putus%')->count();

        $totalLaki      = (clone $baseQuery)->where('jenis_kelamin', 'L')->count();
        $totalPerempuan = (clone $baseQuery)->where('jenis_kelamin', 'P')->count();
        $totalAnak      = (clone $baseQuery)->whereNotNull('umur')->where('umur', '<', 15)->count();
        $totalProduktif = (clone $baseQuery)->whereNotNull('umur')->whereBetween('umur', [15, 59])->count();
        $totalLansia    = (clone $baseQuery)->whereNotNull('umur')->where('umur', '>=', 60)->count();

        $kelurahanDist = (clone $baseQuery)
            ->select('kelurahan', DB::raw('count(*) as total'))
            ->whereNotNull('kelurahan')->where('kelurahan', '!=', '')
            ->groupBy('kelurahan')->orderByDesc('total')->limit(10)->get();

        $monthlyDist = (clone $baseQuery)
            ->select('bulan', DB::raw('count(*) as total'))
            ->whereNotNull('bulan')->where('bulan', '!=', '')
            ->groupBy('bulan')->get();

        $monthOrder = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];
        $sortedMonthly = $monthlyDist->sortBy(function ($item) use ($monthOrder) {
            return $monthOrder[$item->bulan] ?? 99;
        })->values();

        $patients = (clone $baseQuery)
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Query Investigasi Kontak metrics
        $ikQuery = TbContactInvestigation::query();
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($ikQuery, $kabupaten);
        }
        if (!empty($kelurahan)) {
            \App\Services\RegionHelper::filterKelurahan($ikQuery, $kelurahan);
        }

        $totalKontak        = (clone $ikQuery)->count();
        $totalIndeksIk      = (clone $ikQuery)->distinct('kasus_indeks_sitb')->count('kasus_indeks_sitb');
        $totalKontakSerumah = (clone $ikQuery)->where('jenis_kontak', 'like', '%Serumah%')->count();
        $totalKontakErat    = (clone $ikQuery)->where('jenis_kontak', 'like', '%Erat%')->count();
        $totalKontakSakit   = (clone $ikQuery)->where('hasil_evaluasi', 'Sakit TBC')->count();
        $totalKontakTerduga = (clone $ikQuery)->where('hasil_evaluasi', 'Terduga TBC')->count();
        $totalKontakTpt     = (clone $ikQuery)->whereNotNull('status_tpt')->count();
        $rasioKontak        = $totalIndeksIk > 0 ? round($totalKontak / $totalIndeksIk, 1) : 0;

        // Map data using unified dual-layer getMapData
        $mapData = $this->getMapData($request)['points'] ?? [];

        return [
            'kpi' => [
                'total_all'               => $totalAll,
                'total_pelacakan'         => $totalPelacakan,
                'total_terduga'           => $totalTerduga,
                'total_diperiksa_tcm'     => $totalDiperiksaTcm,
                'total_terkonfirmasi'     => $totalTerkonfirmasi,
                'total_sedang_pengobatan' => $totalSedangPengobatan,
                'total_sembuh'            => $totalSembuh,
                'total_lengkap'           => $totalPengobatanLengkap,
                'total_putus'             => $totalPutusBerobat,
                'total_laki'              => $totalLaki,
                'total_perempuan'         => $totalPerempuan,
                'total_anak'              => $totalAnak,
                'total_produktif'         => $totalProduktif,
                'total_lansia'            => $totalLansia,
                // Investigasi Kontak KPI
                'total_kontak'            => $totalKontak,
                'total_indeks_ik'         => $totalIndeksIk,
                'total_kontak_serumah'    => $totalKontakSerumah,
                'total_kontak_erat'       => $totalKontakErat,
                'total_kontak_sakit'      => $totalKontakSakit,
                'total_kontak_terduga'    => $totalKontakTerduga,
                'total_kontak_tpt'        => $totalKontakTpt,
                'rasio_kontak'            => $rasioKontak,
            ],
            'kelurahan_chart' => [
                'labels' => $kelurahanDist->pluck('kelurahan')->all(),
                'values' => $kelurahanDist->pluck('total')->all(),
            ],
            'monthly_chart' => [
                'labels' => $sortedMonthly->pluck('bulan')->all(),
                'values' => $sortedMonthly->pluck('total')->all(),
            ],
            'gender_chart' => [
                'labels' => ['Laki-laki (L)', 'Perempuan (P)'],
                'values' => [$totalLaki, $totalPerempuan],
            ],
            'age_chart' => [
                'labels' => ['Anak (<15 th)', 'Produktif (15-59 th)', 'Lansia (≥60 th)'],
                'values' => [$totalAnak, $totalProduktif, $totalLansia],
            ],
            'map_data'  => $mapData,
            'patients'  => $patients,
        ];
    }
}
