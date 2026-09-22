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
<body class="bg-slate-100 min-h-screen text-slate-800 font-sans p-4 sm:p-8">

    <!-- Action Bar -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('stunting.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-xl shadow-xs border border-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard Stunting
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Report Document -->
    <div class="print-page max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-8 sm:p-12 border border-slate-200/80">
        
        <!-- Header / Kop Surat -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-2xl shadow-sm">
                ST
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-black text-slate-900 tracking-tight uppercase">PEMERINTAH DAERAH KABUPATEN / KOTA</h1>
                <h2 class="text-sm font-bold text-emerald-700 uppercase">DINAS KESEHATAN • GERAKAN PERCEPATAN PENURUNAN STUNTING (GERCEP PENTING)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Sistem Cepat Post dan Tracking (SICEPOT) - Rekapitulasi Pemantauan Pertumbuhan Balita e-PPGBM</p>
            </div>
            <div class="text-right text-xs text-slate-500 font-mono">
                <div>Dokumen: SPM-STUNTING-01</div>
                <div>Tanggal: {{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Report Title -->
        <div class="text-center my-6">
            <h3 class="text-lg font-black text-slate-900 uppercase underline underline-offset-4 tracking-wider">
                LAPORAN EKSEKUTIF PEMANTAUAN STATUS GIZI & BALITA STUNTING
            </h3>
            <p class="text-xs text-slate-500 mt-1.5 font-medium">
                Wilayah Desa: <span class="font-bold text-slate-800">{{ $selectedDesa ?: 'Semua Wilayah Desa / Kelurahan' }}</span> 
                @if($selectedBulan) | Bulan: <span class="font-bold text-slate-800">{{ $selectedBulan }}</span> @endif
                @if($selectedTahun) | Tahun: <span class="font-bold text-slate-800">{{ $selectedTahun }}</span> @endif
            </p>
        </div>

        <!-- Key Performance Indicators (KPI Cards) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 my-6">
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70">
                <div class="text-[10px] font-bold uppercase text-slate-500">Total Balita Terdata</div>
                <div class="text-2xl font-black text-slate-800 mt-1">{{ number_format($total ?? 0) }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Pengukuran terdaftar</div>
            </div>
            <div class="p-3.5 rounded-xl border border-red-200 bg-red-50/50">
                <div class="text-[10px] font-bold uppercase text-red-600">Sangat Pendek (Severely Stunted)</div>
                <div class="text-2xl font-black text-red-700 mt-1">{{ number_format($sangatPendekCount ?? 0) }}</div>
                <div class="text-[10px] text-red-500 mt-0.5">{{ $total > 0 ? round(($sangatPendekCount / $total) * 100, 1) : 0 }}% dari total</div>
            </div>
            <div class="p-3.5 rounded-xl border border-amber-200 bg-amber-50/50">
                <div class="text-[10px] font-bold uppercase text-amber-700">Pendek (Stunted)</div>
                <div class="text-2xl font-black text-amber-700 mt-1">{{ number_format($pendekCount ?? 0) }}</div>
                <div class="text-[10px] text-amber-600 mt-0.5">{{ $total > 0 ? round(($pendekCount / $total) * 100, 1) : 0 }}% dari total</div>
            </div>
            <div class="p-3.5 rounded-xl border border-emerald-200 bg-emerald-50/50">
                <div class="text-[10px] font-bold uppercase text-emerald-700">Tinggi Normal / Baik</div>
                <div class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($normalCount ?? 0) }}</div>
                <div class="text-[10px] text-emerald-600 mt-0.5">{{ $total > 0 ? round(($normalCount / $total) * 100, 1) : 0 }}% dari total</div>
            </div>
        </div>

        <!-- Prevalensi Banner -->
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wide">Total Balita Berisiko &amp; Mengalami Stunting (TB/U):</span>
                <p class="text-xs text-slate-600 mt-0.5">Gabungan kategori balita Sangat Pendek dan Pendek yang memerlukan intervensi gizi spesifik.</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-emerald-700">{{ number_format(($sangatPendekCount ?? 0) + ($pendekCount ?? 0)) }}</span>
                <span class="text-xs font-bold text-emerald-800">
                    ({{ $total > 0 ? round((($sangatPendekCount + $pendekCount) / $total) * 100, 1) : 0 }}%)
                </span>
            </div>
        </div>

        <!-- Table Sebaran Kasus per Desa -->
        <div class="my-6">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Sebaran Status Gizi TB/U Berdasarkan Desa / Kelurahan:</h4>
            <table class="w-full text-left text-xs border border-slate-200">
                <thead class="bg-slate-100 text-slate-700 font-bold">
                    <tr>
                        <th class="p-2 border-b border-r border-slate-200 text-center w-10">No</th>
                        <th class="p-2 border-b border-r border-slate-200">Desa / Kelurahan</th>
                        <th class="p-2 border-b border-r border-slate-200 text-center">Normal</th>
                        <th class="p-2 border-b border-r border-slate-200 text-center">Pendek</th>
                        <th class="p-2 border-b border-r border-slate-200 text-center">Sangat Pendek</th>
                        <th class="p-2 border-b border-slate-200 text-center">Prioritas Intervensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($byDesa ?? [] as $i => $row)
                    @php 
                        $sp = $row['sangat_pendek'] ?? 0;
                        $pd = $row['pendek'] ?? 0;
                        $nm = $row['normal'] ?? 0;
                        $stuntTotal = $sp + $pd;
                    @endphp
                    <tr>
                        <td class="p-2 border-r border-slate-200 text-center text-slate-500">{{ $i + 1 }}</td>
                        <td class="p-2 border-r border-slate-200 font-semibold text-slate-800">{{ $row['desa'] ?: '(Belum Terdata)' }}</td>
                        <td class="p-2 border-r border-slate-200 text-center font-medium text-emerald-700">{{ $nm }}</td>
                        <td class="p-2 border-r border-slate-200 text-center font-bold text-amber-600">{{ $pd }}</td>
                        <td class="p-2 border-r border-slate-200 text-center font-bold text-rose-600">{{ $sp }}</td>
                        <td class="p-2 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $stuntTotal >= 5 ? 'bg-rose-100 text-rose-800' : ($stuntTotal >= 1 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                {{ $stuntTotal >= 5 ? 'Prioritas Utama (Lokus)' : ($stuntTotal >= 1 ? 'Intervensi Terarah' : 'Pemeliharaan Rutin') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400">Tidak ada data wilayah tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Rekomendasi Program & Tanda Tangan -->
        <div class="mt-8 pt-6 border-t border-slate-200">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Rekomendasi Intervensi Percepatan Penurunan Stunting:</h4>
            <ul class="text-xs text-slate-600 list-disc list-inside space-y-1 mb-10">
                <li>Pemberian Makanan Tambahan (PMT) berbahan pangan lokal kaya protein hewani bagi balita kurus dan gizi kurang selama 90 hari.</li>
                <li>Rujukan balita Sangat Pendek ke Dokter Spesialis Anak (Sp.A) di RSUD untuk penapisan *red flags* stunting dan terapi *Food for Special Medical Purpose* (PKMK).</li>
                <li>Peningkatan kunjungan posyandu terintegrasi serta edukasi Sanitasi Total Berbasis Masyarakat (STBM) dan konsumsi air minum aman.</li>
            </ul>

            <div class="flex justify-between items-start text-xs pt-4">
                <div>
                    <div class="text-slate-500">Mengetahui,</div>
                    <div class="font-bold text-slate-800 mt-1">Kepala Dinas Kesehatan / Kepala Puskesmas</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-slate-900 underline">dr. Pimpinan Fasyankes, M.Kes</div>
                    <div class="text-slate-500">NIP. 19800512 200604 1 005</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-500">Kabupaten Tangerang, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold text-slate-800 mt-1">Koordinator Program Gizi &amp; Stunting</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-slate-900 underline">Nutrisionis Pelaksana, S.Gz</div>
                    <div class="text-slate-500">NIP. 19880920 201202 2 003</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
