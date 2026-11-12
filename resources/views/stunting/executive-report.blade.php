<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Program Intervensi Balita Stunting - SICEPOT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11pt; }
            .print-page { box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-ant-layout min-h-screen text-ant-title font-sans p-4 sm:p-8">

    <!-- Action Bar -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('stunting.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-ant-text hover:text-ant-title bg-white px-3.5 py-2 rounded-ant shadow-ant-card border border-ant-border">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard Stunting
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="ant-btn ant-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Report Document -->
    <div class="print-page max-w-4xl mx-auto bg-white rounded-ant-lg shadow-ant-card p-8 sm:p-12 border border-ant-split">

        <!-- Header / Kop Surat -->
        <div class="border-b-2 border-ant-title pb-4 mb-6 flex items-center gap-6">
            <div class="w-16 h-16 rounded-ant-lg bg-success text-white flex items-center justify-center font-black text-2xl shadow-ant-card">
                ST
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-black text-ant-title tracking-tight uppercase">PEMERINTAH DAERAH KABUPATEN / KOTA</h1>
                <h2 class="text-sm font-bold text-success-700 uppercase">DINAS KESEHATAN • GERAKAN PERCEPATAN PENURUNAN STUNTING (GERCEP PENTING)</h2>
                <p class="text-xs text-ant-secondary mt-0.5">Sistem Cepat Post dan Tracking (SICEPOT) - Rekapitulasi Pemantauan Pertumbuhan Balita e-PPGBM</p>
            </div>
            <div class="text-right text-xs text-ant-secondary font-mono">
                <div>Dokumen: SPM-STUNTING-01</div>
                <div>Tanggal: {{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Report Title -->
        <div class="text-center my-6">
            <h3 class="text-lg font-black text-ant-title uppercase underline underline-offset-4 tracking-wider">
                LAPORAN EKSEKUTIF PEMANTAUAN STATUS GIZI & BALITA STUNTING
            </h3>
            <p class="text-xs text-ant-secondary mt-1.5 font-medium">
                Wilayah Desa: <span class="font-bold text-ant-title">{{ $selectedDesa ?: 'Semua Wilayah Desa / Kelurahan' }}</span> 
                @if($selectedBulan) | Bulan: <span class="font-bold text-ant-title">{{ $selectedBulan }}</span> @endif
                @if($selectedTahun) | Tahun: <span class="font-bold text-ant-title">{{ $selectedTahun }}</span> @endif
            </p>
        </div>

        <!-- Key Performance Indicators (KPI Cards) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 my-6">
            <div class="p-3.5 rounded-ant border border-ant-border bg-ant-layout">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Total Balita Terdata</div>
                <div class="text-2xl font-black text-ant-title mt-1">{{ number_format($total ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Pengukuran terdaftar</div>
            </div>
            <div class="p-3.5 rounded-ant border border-danger-200 bg-danger-50">
                <div class="text-[10px] font-bold uppercase text-danger-600">Sangat Pendek (Severely Stunted)</div>
                <div class="text-2xl font-black text-danger-700 mt-1">{{ number_format($sangatPendekCount ?? 0) }}</div>
                <div class="text-[10px] text-danger-600 mt-0.5">{{ $total > 0 ? round(($sangatPendekCount / $total) * 100, 1) : 0 }}% dari total</div>
            </div>
            <div class="p-3.5 rounded-ant border border-warning-200 bg-warning-50">
                <div class="text-[10px] font-bold uppercase text-warning-700">Pendek (Stunted)</div>
                <div class="text-2xl font-black text-warning-700 mt-1">{{ number_format($pendekCount ?? 0) }}</div>
                <div class="text-[10px] text-warning-600 mt-0.5">{{ $total > 0 ? round(($pendekCount / $total) * 100, 1) : 0 }}% dari total</div>
            </div>
            <div class="p-3.5 rounded-ant border border-success-200 bg-success-50">
                <div class="text-[10px] font-bold uppercase text-success-700">Tinggi Normal / Baik</div>
                <div class="text-2xl font-black text-success-700 mt-1">{{ number_format($normalCount ?? 0) }}</div>
                <div class="text-[10px] text-success-600 mt-0.5">{{ $total > 0 ? round(($normalCount / $total) * 100, 1) : 0 }}% dari total</div>
            </div>
        </div>

        <!-- Prevalensi Banner -->
        <div class="p-4 rounded-ant-lg bg-success-50 border border-success-200 mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-success-700 uppercase tracking-wide">Total Balita Berisiko &amp; Mengalami Stunting (TB/U):</span>
                <p class="text-xs text-ant-text mt-0.5">Gabungan kategori balita Sangat Pendek dan Pendek yang memerlukan intervensi gizi spesifik.</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-success-700">{{ number_format(($sangatPendekCount ?? 0) + ($pendekCount ?? 0)) }}</span>
                <span class="text-xs font-bold text-success-700">
                    ({{ $total > 0 ? round((($sangatPendekCount + $pendekCount) / $total) * 100, 1) : 0 }}%)
                </span>
            </div>
        </div>

        <!-- Table Sebaran Kasus per Desa -->
        <div class="my-6">
            <h4 class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2">Sebaran Status Gizi TB/U Berdasarkan Desa / Kelurahan:</h4>
            <table class="w-full text-left text-xs border border-ant-border">
                <thead class="bg-ant-layout text-ant-text font-bold">
                    <tr>
                        <th class="p-2 border-b border-r border-ant-border text-center w-10">No</th>
                        <th class="p-2 border-b border-r border-ant-border">Desa / Kelurahan</th>
                        <th class="p-2 border-b border-r border-ant-border text-center">Normal</th>
                        <th class="p-2 border-b border-r border-ant-border text-center">Pendek</th>
                        <th class="p-2 border-b border-r border-ant-border text-center">Sangat Pendek</th>
                        <th class="p-2 border-b border-ant-border text-center">Prioritas Intervensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ant-split">
                    @forelse($byDesa ?? [] as $i => $row)
                    @php 
                        $sp = $row['sangat_pendek'] ?? 0;
                        $pd = $row['pendek'] ?? 0;
                        $nm = $row['normal'] ?? 0;
                        $stuntTotal = $sp + $pd;
                    @endphp
                    <tr>
                        <td class="p-2 border-r border-ant-border text-center text-ant-secondary">{{ $i + 1 }}</td>
                        <td class="p-2 border-r border-ant-border font-semibold text-ant-title">{{ $row['desa'] ?: '(Belum Terdata)' }}</td>
                        <td class="p-2 border-r border-ant-border text-center font-medium text-success-700">{{ $nm }}</td>
                        <td class="p-2 border-r border-ant-border text-center font-bold text-warning-600">{{ $pd }}</td>
                        <td class="p-2 border-r border-ant-border text-center font-bold text-danger-600">{{ $sp }}</td>
                        <td class="p-2 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $stuntTotal >= 5 ? 'bg-danger-100 text-danger-700' : ($stuntTotal >= 1 ? 'bg-warning-100 text-warning-700' : 'bg-success-100 text-success-700') }}">
                                {{ $stuntTotal >= 5 ? 'Prioritas Utama (Lokus)' : ($stuntTotal >= 1 ? 'Intervensi Terarah' : 'Pemeliharaan Rutin') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-ant-disabled">Tidak ada data wilayah tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Rekomendasi Program & Tanda Tangan -->
        <div class="mt-8 pt-6 border-t border-ant-split">
            <h4 class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2">Rekomendasi Intervensi Percepatan Penurunan Stunting:</h4>
            <ul class="text-xs text-ant-text list-disc list-inside space-y-1 mb-10">
                <li>Pemberian Makanan Tambahan (PMT) berbahan pangan lokal kaya protein hewani bagi balita kurus dan gizi kurang selama 90 hari.</li>
                <li>Rujukan balita Sangat Pendek ke Dokter Spesialis Anak (Sp.A) di RSUD untuk penapisan *red flags* stunting dan terapi *Food for Special Medical Purpose* (PKMK).</li>
                <li>Peningkatan kunjungan posyandu terintegrasi serta edukasi Sanitasi Total Berbasis Masyarakat (STBM) dan konsumsi air minum aman.</li>
            </ul>

            <div class="flex justify-between items-start text-xs pt-4">
                <div>
                    <div class="text-ant-secondary">Mengetahui,</div>
                    <div class="font-bold text-ant-title mt-1">Kepala Dinas Kesehatan / Kepala Puskesmas</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-ant-title underline">dr. Pimpinan Fasyankes, M.Kes</div>
                    <div class="text-ant-secondary">NIP. 19800512 200604 1 005</div>
                </div>
                <div class="text-right">
                    <div class="text-ant-secondary">Kabupaten Tangerang, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold text-ant-title mt-1">Koordinator Program Gizi &amp; Stunting</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-ant-title underline">Nutrisionis Pelaksana, S.Gz</div>
                    <div class="text-ant-secondary">NIP. 19880920 201202 2 003</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
