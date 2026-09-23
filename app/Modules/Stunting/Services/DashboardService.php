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
     * Build query for monthly trend chart: filter desa + tahun ONLY (no bulan filter),
     * so the candlestick chart always shows all months in the selected year.
     */
    private function trendQuery(Request $request): Builder
    {
        $q = StuntingPatient::query();

        if ($desa = $request->input('desa')) {
            $q->where('desa', $desa);
        }

        // Sengaja TIDAK filter bulan — agar grafik tren selalu tampilkan semua bulan
        if ($tahun = $request->input('tahun')) {
            $q->whereYear('tanggal_pengukuran', $tahun);
        }

        return $q;
    }


    public function getStatsData(Request $request): array
    {
        $q = $this->baseQuery($request);

        $total = (clone $q)->count();

        // Total balita unik (distinct NIK)
        $totalBalita = (clone $q)->whereNotNull('nik')->distinct()->count('nik');

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
        $naikBBYCount = (clone $q)->whereIn('tbu_kategori', ['Pendek', 'Sangat Pendek'])->where('naik_berat_badan', 'N')->count(); // N = Naik, hanya stunting
        $naikBBTCount = (clone $q)->where('naik_berat_badan', 'T')->count(); // T = Turun

        // Skrining Klinis & Intervensi Spesifik — hanya dari balita STUNTING (Pendek + Sangat Pendek)
        $stuntingQ = (clone $q)->whereIn('tbu_kategori', ['Pendek', 'Sangat Pendek']);
        $hemoglobinCount = (clone $stuntingQ)->where('test_hemoglobin', 'Ya')->count();
        $mantouxCount    = (clone $stuntingQ)->where('test_mantoux', 'Ya')->count();
        $konsulSpaCount  = (clone $stuntingQ)->where('konsul_spa', 'Ya')->count();
        $vitACount       = (clone $stuntingQ)->whereNotNull('jml_vit_a')->where('jml_vit_a', '>', 0)->count();
        $kelasIbuCount   = (clone $stuntingQ)->where('kelas_ibu', 'Ya')->count();
        $mbgCount        = (clone $stuntingQ)->where('mbg', 'Ya')->count();

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

        // Monthly trend — driver-aware (PostgreSQL / MySQL / SQLite)
        $driver = \Illuminate\Support\Facades\DB::getDriverName();

        if ($driver === 'pgsql') {
            $monthExpr   = "TO_CHAR(tanggal_pengukuran, 'YYYY-MM')";
        } elseif (in_array($driver, ['mysql', 'mariadb'])) {
            $monthExpr   = "DATE_FORMAT(tanggal_pengukuran, '%Y-%m')";
        } else {
            // SQLite
            $monthExpr   = "strftime('%Y-%m', tanggal_pengukuran)";
        }

        // Grafik tren pakai trendQuery (tidak filter bulan) agar semua bulan dalam tahun tampil
        $monthlyTrend = $this->trendQuery($request)->selectRaw("
                {$monthExpr} as bulan_label,
                COUNT(*) as total,
                SUM(CASE WHEN tbu_kategori IN ('Pendek','Sangat Pendek') THEN 1 ELSE 0 END) as stunting
            ")
            ->whereNotNull('tanggal_pengukuran')
            ->groupByRaw($monthExpr)
            ->orderBy('bulan_label')
            ->get();

        // Patient list for table (paginated)
        $perPage = (int) $request->input('per_page', 15);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        $patients = (clone $q)
            ->orderByDesc('tanggal_pengukuran')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return compact(
            'total',
            'totalBalita',
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
            'hemoglobinCount',
            'mantouxCount',
            'konsulSpaCount',
            'vitACount',
            'kelasIbuCount',
            'mbgCount',
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
     * Get available years for filter - SQLite compatible strftime
     */
    public function getYearList(): array
    {
        $driver = \Illuminate\Support\Facades\DB::getDriverName();

        if ($driver === 'pgsql') {
            $expr = "EXTRACT(YEAR FROM tanggal_pengukuran)::TEXT";
        } elseif (in_array($driver, ['mysql', 'mariadb'])) {
            $expr = "DATE_FORMAT(tanggal_pengukuran, '%Y')";
        } else {
            $expr = "strftime('%Y', tanggal_pengukuran)";
        }

        return StuntingPatient::query()
            ->selectRaw("{$expr} as tahun")
            ->whereNotNull('tanggal_pengukuran')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();
    }
}