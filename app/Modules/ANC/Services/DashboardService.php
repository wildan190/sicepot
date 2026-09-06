<?php

namespace App\Modules\ANC\Services;

use App\Modules\ANC\Models\AncPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getKabupatenList(): \Illuminate\Support\Collection
    {
        return AncPatient::whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->pluck('kabupaten')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKabupaten($k))
            ->unique()
            ->sort()
            ->values();
    }

    public function getKelurahanList(?string $kabupaten): \Illuminate\Support\Collection
    {
        $q = AncPatient::whereNotNull('kelurahan')->where('kelurahan', '!=', '');
        if (!empty($kabupaten)) {
            \App\Services\RegionHelper::filterKabupaten($q, $kabupaten);
        }

        return $q->pluck('kelurahan')
            ->map(fn ($k) => \App\Services\RegionHelper::normalizeKelurahan($k))
            ->unique()
            ->sort()
            ->values();
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

        // Distribution by Kelurahan
        $kelurahanDist = (clone $base)
            ->select('kelurahan', DB::raw('count(*) as total'))
            ->whereNotNull('kelurahan')->where('kelurahan', '!=', '')
            ->groupBy('kelurahan')->orderByDesc('total')->limit(10)->get();

        // Monthly trend (ensure 12 months display or non-zero months sorted)
        $monthOrder = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];
        $monthlyRaw = (clone $base)
            ->select('bulan', DB::raw('count(*) as total'))
            ->whereNotNull('bulan')->where('bulan', '!=', '')
            ->groupBy('bulan')->pluck('total', 'bulan')->all();

        // If bulan column was not populated, fallback to created_at or hpht month
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

        // Kunjungan breakdown chart
        $kunjunganDist = (clone $base)
            ->select('kunjungan_ke', DB::raw('count(*) as total'))
            ->whereNotNull('kunjungan_ke')->where('kunjungan_ke', '!=', '')
            ->groupBy('kunjungan_ke')->orderBy('kunjungan_ke')->get();

        // Age groups
        $umurMuda      = (clone $base)->whereNotNull('umur')->where('umur', '<', 20)->count();
        $umurProduktif = (clone $base)->whereNotNull('umur')->whereBetween('umur', [20, 35])->count();
        $umurRisti     = (clone $base)->whereNotNull('umur')->where('umur', '>', 35)->count();

        // Patients list
        $patients = (clone $base)->orderByDesc('id')->paginate(15)->withQueryString();

        // Check imminent deliveries: H-1 of HPL (Hari Perkiraan Lahir)
        // Tomorrow date in Y-m-d format
        $tomorrow = now()->addDay()->toDateString();
        $today    = now()->toDateString();

        $imminentDeliveries = AncPatient::whereNotNull('hpl')
            ->whereNull('tanggal_bersalin')
            ->whereDate('hpl', '=', $tomorrow)
            ->get(['id', 'nama_lengkap', 'nik', 'no_rekam_medis', 'umur', 'hpl', 'hpht', 'kabupaten', 'kelurahan', 'status_risti', 'faktor_risiko', 'fasyankes_name']);

        // Poedji Rochjati breakdown
        $totalKrr  = (clone $base)->where('kategori_poedji_rochjati', 'KRR')->orWhereNull('kategori_poedji_rochjati')->count();
        $totalKrt  = (clone $base)->where('kategori_poedji_rochjati', 'KRT')->count();
        $totalKrst = (clone $base)->where('kategori_poedji_rochjati', 'KRST')->count();

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

        $ancKelurahanAll = (clone $base)
            ->select('kelurahan', 'kabupaten', DB::raw('count(*) as total'),
                DB::raw("sum(case when kategori_poedji_rochjati in ('KRT', 'KRST') or status_risti like '%tinggi%' then 1 else 0 end) as total_risti"))
            ->whereNotNull('kelurahan')
            ->where('kelurahan', '!=', '')
            ->groupBy('kelurahan', 'kabupaten')
            ->orderByDesc('total')
            ->get();

        $ancMapPoints = [];
        foreach ($ancKelurahanAll as $idx => $row) {
            $key = strtolower(trim($row->kelurahan));
            if (isset($knownCoords[$key])) {
                $lat = $knownCoords[$key][0];
                $lng = $knownCoords[$key][1];
            } else {
                $lat = -6.3053 + (sin($idx * 1.5) * 0.045);
                $lng = 106.6021 + (cos($idx * 1.5) * 0.045);
            }

            $ancMapPoints[] = [
                'kelurahan'   => $row->kelurahan,
                'kabupaten'   => $row->kabupaten ?? 'Kab. Tangerang',
                'total'       => (int) $row->total,
                'total_risti' => (int) $row->total_risti,
                'lat'         => round($lat, 5),
                'lng'         => round($lng, 5),
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
