<?php

namespace App\Modules\TBC\Services;

use App\Modules\TBC\Models\TbPatient;
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
    public function getMapData(Request $request): array
    {
        $kabupaten = $request->input('kabupaten');
        $kecamatan = $request->input('kecamatan');

        $query = TbPatient::query();
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($query, $kabupaten);
        }
        if (!empty($kecamatan)) {
            $query->whereRaw('LOWER(TRIM(kecamatan)) = ?', [strtolower(trim($kecamatan))]);
        }

        $rows = $query
            ->select(
                'kelurahan', 'kabupaten', 'kecamatan', 'fasyankes_name',
                DB::raw('count(*) as total'),
                DB::raw("sum(case when report_type = 'tb_03' then 1 else 0 end) as total_terkonfirmasi"),
                DB::raw("sum(case when report_type = 'tb_06' then 1 else 0 end) as total_terduga"),
                DB::raw("sum(case when (status_pengobatan like '%pengobatan%' or status_pengobatan like '%aktif%') and hasil_akhir_pengobatan is null then 1 else 0 end) as total_active"),
                DB::raw("sum(case when hasil_tcm like '%rr%' or hasil_tcm like '%ro%' or hasil_diagnosis like '%ro%' then 1 else 0 end) as total_ro"),
                DB::raw("sum(case when status_hiv like '%positif%' then 1 else 0 end) as total_hiv")
            )
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten', 'kecamatan', 'fasyankes_name')
            ->orderByDesc('total')
            ->get();

        // Group by kelurahan (multiple fasyankes per kelurahan → cluster)
        $byKelurahan = [];
        foreach ($rows as $idx => $row) {
            $kelKey = strtolower(trim($row->kelurahan ?? ''));
            if (!isset($byKelurahan[$kelKey])) {
                $coords = $this->resolveCoords($kelKey, $idx);
                $byKelurahan[$kelKey] = [
                    'kelurahan'         => $row->kelurahan,
                    'kabupaten'         => $row->kabupaten ?? 'Kab. Tangerang',
                    'kecamatan'         => $row->kecamatan ? ucwords(strtolower(trim($row->kecamatan))) : ($this->knownCoords[$kelKey]['kecamatan'] ?? '-'),
                    'lat'               => $coords['lat'],
                    'lng'               => $coords['lng'],
                    'total'             => 0,
                    'total_terkonfirmasi' => 0,
                    'total_terduga'     => 0,
                    'total_active'      => 0,
                    'total_ro'          => 0,
                    'total_hiv'         => 0,
                    'fasyankes'         => [],
                ];
            }
            $byKelurahan[$kelKey]['total']               += (int) $row->total;
            $byKelurahan[$kelKey]['total_terkonfirmasi'] += (int) $row->total_terkonfirmasi;
            $byKelurahan[$kelKey]['total_terduga']       += (int) $row->total_terduga;
            $byKelurahan[$kelKey]['total_active']        += (int) $row->total_active;
            $byKelurahan[$kelKey]['total_ro']            += (int) $row->total_ro;
            $byKelurahan[$kelKey]['total_hiv']           += (int) $row->total_hiv;

            if (!empty($row->fasyankes_name)) {
                $byKelurahan[$kelKey]['fasyankes'][] = [
                    'name'  => $row->fasyankes_name,
                    'total' => (int) $row->total,
                ];
            }
        }

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
            'points'           => $points,
            'center'           => $center,
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

        $totalAll           = (clone $baseQuery)->count();
        $totalTerduga       = (clone $baseQuery)->where('report_type', 'tb_06')->count();
        $totalTerkonfirmasi = (clone $baseQuery)->where(function ($q) {
            $q->where('report_type', 'tb_03')
                ->orWhere('hasil_diagnosis', 'like', '%TBC SO%')
                ->orWhere('hasil_diagnosis', 'like', '%Terkonfirmasi%');
        })->count();
        $totalSembuh            = (clone $baseQuery)->where('hasil_akhir_pengobatan', 'like', '%Sembuh%')->count();
        $totalPengobatanLengkap = (clone $baseQuery)->where('hasil_akhir_pengobatan', 'like', '%Lengkap%')->count();
        $totalPutusBerobat      = (clone $baseQuery)->where('hasil_akhir_pengobatan', 'like', '%Putus%')->count();
        $totalSedangPengobatan  = (clone $baseQuery)->where('report_type', 'tb_03')
            ->where(function ($q) {
                $q->whereNull('hasil_akhir_pengobatan')->orWhere('hasil_akhir_pengobatan', '');
            })->count();

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

        // Legacy map_data for backward-compat (used by initial page load)
        $kelurahanAll = (clone $baseQuery)
            ->select('kelurahan', 'kabupaten', DB::raw('count(*) as total'),
                DB::raw("sum(case when status_pengobatan like '%pengobatan%' or status_pengobatan like '%aktif%' then 1 else 0 end) as total_active"),
                DB::raw("sum(case when hasil_tcm like '%rr%' or hasil_tcm like '%ro%' or hasil_diagnosis like '%ro%' then 1 else 0 end) as total_ro"))
            ->whereNotNull('kelurahan')->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten')->orderByDesc('total')->get();

        $mapPoints = [];
        foreach ($kelurahanAll as $idx => $row) {
            $key    = strtolower(trim($row->kelurahan));
            $coords = $this->resolveCoords($key, $idx);
            $mapPoints[] = [
                'kelurahan'    => $row->kelurahan,
                'kabupaten'    => $row->kabupaten ?? 'Kab. Tangerang',
                'kecamatan'    => $this->knownCoords[$key]['kecamatan'] ?? '-',
                'total'        => (int) $row->total,
                'total_active' => (int) $row->total_active,
                'total_ro'     => (int) $row->total_ro,
                'lat'          => $coords['lat'],
                'lng'          => $coords['lng'],
            ];
        }

        return [
            'kpi' => [
                'total_all'               => $totalAll,
                'total_terduga'           => $totalTerduga,
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
            'map_data'  => $mapPoints,
            'patients'  => $patients,
        ];
    }
}
