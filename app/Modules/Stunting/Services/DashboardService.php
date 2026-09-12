<?php

namespace App\Modules\Stunting\Services;

use App\Modules\Stunting\Models\StuntingPatient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardService
{
    /**
     * Build a filtered query from request params (desa, bulan, tahun).
     */
    private function baseQuery(Request $request): Builder
    {
        $q = StuntingPatient::query();

        if ($desa = $request->input('desa')) {
            $q->where('desa', $desa);
        }

        // Menggunakan method bawaan Laravel yang kompatibel dengan PostgreSQL & database lain
        if ($bulan = $request->input('bulan')) {
            $q->whereMonth('tanggal_pengukuran', $bulan);
        }

        if ($tahun = $request->input('tahun')) {
            $q->whereYear('tanggal_pengukuran', $tahun);
        }

        if ($search = $request->input('search')) {
            $q->where(function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        return $q;
    }

    /**
     * Return all KPI stats and chart data.
     */
    public function getStatsData(Request $request): array
    {
        $q = $this->baseQuery($request);

        $total = (clone $q)->count();

        // TB/U (stunting status)
        $normalCount = (clone $q)->where('tbu_kategori', 'Normal')->count();
        $pendekCount = (clone $q)->where('tbu_kategori', 'Pendek')->count();
        $sangatPendekCount = (clone $q)->where('tbu_kategori', 'Sangat Pendek')->count();
        $stuntingTotal = $pendekCount + $sangatPendekCount;

        // BB/U (weight-for-age)
        $bbuGiziBaikCount = (clone $q)->where('bbu_kategori', 'Normal')->count();
        $bbuGiziBaikCount += (clone $q)->where('bbu_kategori', 'Gizi Baik')->count();
        $bbuKurangCount = (clone $q)->where('bbu_kategori', 'Kurang')->count();
        $bbuSangatKurangCount = (clone $q)->where('bbu_kategori', 'Sangat Kurang')->count();
        $bbuRisikoLebihCount = (clone $q)->whereIn('bbu_kategori', ['Risiko Lebih', 'Risiko Gizi Lebih'])->count();
        $bbuLebihCount = (clone $q)->whereIn('bbu_kategori', ['Lebih', 'Gizi Lebih'])->count();
        $bbuObesitasCount = (clone $q)->where('bbu_kategori', 'Obesitas')->count();

        // BB/TB (wasting status)
        $bbtbGiziBaikCount = (clone $q)->whereIn('bbtb_kategori', ['Gizi Baik', 'Normal'])->count();
        $bbtbGiziKurangCount = (clone $q)->whereIn('bbtb_kategori', ['Kurang', 'Gizi Kurang'])->count();
        $bbtbGiziLebihCount = (clone $q)->whereIn('bbtb_kategori', ['Lebih', 'Gizi Lebih', 'Risiko Gizi Lebih'])->count();
        $bbtbObesitasCount = (clone $q)->where('bbtb_kategori', 'Obesitas')->count();

        // Naik berat badan
        $naikBBCount = (clone $q)->whereIn('naik_berat_badan', ['N', 'Y', 'T'])->count();
        $naikBBYCount = (clone $q)->where('naik_berat_badan', 'N')->count(); // N = Naik
        $naikBBTCount = (clone $q)->where('naik_berat_badan', 'T')->count(); // T = Turun

        // Per-desa breakdown for chart
        $byDesa = (clone $q)->selectRaw("
                desa,
                COUNT(*) as total,
                SUM(CASE WHEN tbu_kategori = 'Pendek' THEN 1 ELSE 0 END) as pendek,
                SUM(CASE WHEN tbu_kategori = 'Sangat Pendek' THEN 1 ELSE 0 END) as sangat_pendek,
                SUM(CASE WHEN tbu_kategori = 'Normal' THEN 1 ELSE 0 END) as normal
            ")
            ->groupBy('desa')
            ->orderByDesc('total')
            ->get();

        // Gender breakdown
        $lakiLaki = (clone $q)->where('jenis_kelamin', 'L')->count();
        $perempuan = (clone $q)->where('jenis_kelamin', 'P')->count();

        // Monthly trend (last 12 months) - Menggunakan TO_CHAR untuk PostgreSQL
        $monthlyTrend = (clone $q)->selectRaw("
                TO_CHAR(tanggal_pengukuran, 'YYYY-MM') as bulan_label,
                COUNT(*) as total,
                SUM(CASE WHEN tbu_kategori IN ('Pendek','Sangat Pendek') THEN 1 ELSE 0 END) as stunting
            ")
            ->whereNotNull('tanggal_pengukuran')
            ->groupByRaw("TO_CHAR(tanggal_pengukuran, 'YYYY-MM')")
            ->orderBy('bulan_label')
            ->get();

        // Patient list for table (latest 200)
        $patients = (clone $q)
            ->orderByDesc('tanggal_pengukuran')
            ->limit(200)
            ->get();

        return compact(
            'total',
            'stuntingTotal',
            'normalCount',
            'pendekCount',
            'sangatPendekCount',
            'bbuGiziBaikCount',
            'bbuKurangCount',
            'bbuSangatKurangCount',
            'bbuRisikoLebihCount',
            'bbuLebihCount',
            'bbuObesitasCount',
            'bbtbGiziBaikCount',
            'bbtbGiziKurangCount',
            'bbtbGiziLebihCount',
            'bbtbObesitasCount',
            'naikBBYCount',
            'naikBBTCount',
            'lakiLaki',
            'perempuan',
            'byDesa',
            'monthlyTrend',
            'patients'
        );
    }

    /**
     * Get distinct list of desa values.
     */
    public function getDesaList(): array
    {
        return StuntingPatient::query()
            ->whereNotNull('desa')
            ->distinct()
            ->orderBy('desa')
            ->pluck('desa')
            ->toArray();
    }

    /**
     * Get available years for filter - Menggunakan TO_CHAR untuk PostgreSQL
     */
    public function getYearList(): array
    {
        return StuntingPatient::query()
            ->selectRaw("TO_CHAR(tanggal_pengukuran, 'YYYY') as tahun")
            ->whereNotNull('tanggal_pengukuran')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();
    }
}