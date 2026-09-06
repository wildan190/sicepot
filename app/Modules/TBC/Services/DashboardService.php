<?php

namespace App\Modules\TBC\Services;

use App\Modules\TBC\Models\TbPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Retrieve the list of distinct kabupaten for filter dropdown.
     */
    public function getKabupatenList(): \Illuminate\Support\Collection
    {
        return TbPatient::whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->pluck('kabupaten')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKabupaten($k))
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Retrieve the list of distinct kelurahan, optionally filtered by kabupaten.
     */
    public function getKelurahanList(?string $kabupaten): \Illuminate\Support\Collection
    {
        $query = TbPatient::whereNotNull('kelurahan')->where('kelurahan', '!=', '');

        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($query, $kabupaten);
        }

        return $query->pluck('kelurahan')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKelurahan($k))
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Compute KPI statistics and chart data based on request filters.
     */
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

        // Summary Counts
        $totalAll             = (clone $baseQuery)->count();
        $totalTerduga         = (clone $baseQuery)->where('report_type', 'tb_06')->count();
        $totalTerkonfirmasi   = (clone $baseQuery)->where(function ($q) {
            $q->where('report_type', 'tb_03')
                ->orWhere('hasil_diagnosis', 'like', '%TBC SO%')
                ->orWhere('hasil_diagnosis', 'like', '%Terkonfirmasi%');
        })->count();

        $totalSembuh              = (clone $baseQuery)->where('hasil_akhir_pengobatan', 'like', '%Sembuh%')->count();
        $totalPengobatanLengkap   = (clone $baseQuery)->where('hasil_akhir_pengobatan', 'like', '%Lengkap%')->count();
        $totalPutusBerobat        = (clone $baseQuery)->where('hasil_akhir_pengobatan', 'like', '%Putus%')->count();
        $totalSedangPengobatan    = (clone $baseQuery)->where('report_type', 'tb_03')
            ->where(function ($q) {
                $q->whereNull('hasil_akhir_pengobatan')->orWhere('hasil_akhir_pengobatan', '');
            })->count();

        // Gender breakdown
        $totalLaki     = (clone $baseQuery)->where('jenis_kelamin', 'L')->count();
        $totalPerempuan = (clone $baseQuery)->where('jenis_kelamin', 'P')->count();

        // Age group breakdown
        $totalAnak      = (clone $baseQuery)->whereNotNull('umur')->where('umur', '<', 15)->count();
        $totalProduktif = (clone $baseQuery)->whereNotNull('umur')->whereBetween('umur', [15, 59])->count();
        $totalLansia    = (clone $baseQuery)->whereNotNull('umur')->where('umur', '>=', 60)->count();

        // Distribution by Kelurahan (Top 10)
        $kelurahanDist = (clone $baseQuery)
            ->select('kelurahan', DB::raw('count(*) as total'))
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->groupBy('kelurahan')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Monthly trends
        $monthlyDist = (clone $baseQuery)
            ->select('bulan', DB::raw('count(*) as total'))
            ->whereNotNull('bulan')
            ->where('bulan', '!=', '')
            ->groupBy('bulan')
            ->get();

        // Sort months chronologically
        $monthOrder = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];

        $sortedMonthly = $monthlyDist->sortBy(function ($item) use ($monthOrder) {
            return $monthOrder[$item->bulan] ?? 99;
        })->values();

        // Latest patients with pagination
        $patients = (clone $baseQuery)
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Geo-Coordinates Mapping for Heatmap / Leaflet GIS
        $knownCoords = [
            'pagedangan'      => [-6.3053, 106.6021],
            'jatake'          => [-6.3150, 106.5890],
            'cijantra'        => [-6.3210, 106.6110],
            'karang tengah'   => [-6.3010, 106.5920],
            'lengkong kulon'  => [-6.2950, 106.6210],
            'kadusirung'      => [-6.3280, 106.5980],
            'malang nengah'   => [-6.3350, 106.5850],
            'situgadung'      => [-6.3110, 106.6340],
            'medang'          => [-6.2680, 106.6180],
            'curug'           => [-6.2620, 106.5560],
            'panongan'        => [-6.2890, 106.5410],
            'legok'           => [-6.3380, 106.5620],
            'cisauk'          => [-6.3450, 106.6420],
            'kelapa dua'      => [-6.2480, 106.6140],
        ];

        $kelurahanAll = (clone $baseQuery)
            ->select('kelurahan', 'kabupaten', DB::raw('count(*) as total'),
                DB::raw("sum(case when status_pengobatan like '%pengobatan%' or status_pengobatan like '%aktif%' then 1 else 0 end) as total_active"),
                DB::raw("sum(case when hasil_tcm like '%rr%' or hasil_tcm like '%ro%' or hasil_diagnosis like '%ro%' then 1 else 0 end) as total_ro"))
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten')
            ->orderByDesc('total')
            ->get();

        $mapPoints = [];
        foreach ($kelurahanAll as $idx => $row) {
            $key = strtolower(trim($row->kelurahan));
            if (isset($knownCoords[$key])) {
                $lat = $knownCoords[$key][0];
                $lng = $knownCoords[$key][1];
            } else {
                // Synthetic jitter around Pagedangan Tangerang center for unmapped kelurahan
                $lat = -6.3053 + (sin($idx * 1.7) * 0.045);
                $lng = 106.6021 + (cos($idx * 1.7) * 0.045);
            }

            $mapPoints[] = [
                'kelurahan'    => $row->kelurahan,
                'kabupaten'    => $row->kabupaten ?? 'Kab. Tangerang',
                'total'        => (int) $row->total,
                'total_active' => (int) $row->total_active,
                'total_ro'     => (int) $row->total_ro,
                'lat'          => round($lat, 5),
                'lng'          => round($lng, 5),
            ];
        }

        return [
            'kpi' => [
                'total_all'              => $totalAll,
                'total_terduga'          => $totalTerduga,
                'total_terkonfirmasi'    => $totalTerkonfirmasi,
                'total_sedang_pengobatan' => $totalSedangPengobatan,
                'total_sembuh'           => $totalSembuh,
                'total_lengkap'          => $totalPengobatanLengkap,
                'total_putus'            => $totalPutusBerobat,
                'total_laki'             => $totalLaki,
                'total_perempuan'        => $totalPerempuan,
                'total_anak'             => $totalAnak,
                'total_produktif'        => $totalProduktif,
                'total_lansia'           => $totalLansia,
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
