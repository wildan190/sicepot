<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Program Penanggulangan TBC - SICEPOT</title>
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
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white px-3.5 py-2 rounded-xl shadow-xs border border-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard TBC
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Report Document -->
    <div class="print-page max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-8 sm:p-12 border border-slate-200/80">
        
        <!-- Header / Kop Surat -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-2xl shadow-sm">
                TB
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-black text-slate-900 tracking-tight uppercase">PEMERINTAH DAERAH KABUPATEN / KOTA</h1>
                <h2 class="text-sm font-bold text-blue-700 uppercase">DINAS KESEHATAN • PROGRAM PENANGGULANGAN TUBERKULOSIS (P2TB)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Sistem Cepat Post dan Tracking (SICEPOT) - Rekapitulasi Register SITB TB-03 / TB-06</p>
            </div>
            <div class="text-right text-xs text-slate-500 font-mono">
                <div>Dokumen: SPM-TB-02</div>
                <div>Tanggal: {{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Report Title -->
        <div class="text-center my-6">
            <h3 class="text-lg font-black text-slate-900 uppercase underline underline-offset-4 tracking-wider">
                LAPORAN EKSEKUTIF STANDAR PELAYANAN MINIMAL (SPM) PROGRAM P2 TBC
            </h3>
            <p class="text-xs text-slate-500 mt-1.5 font-medium">
                Wilayah: <span class="font-bold text-slate-800">{{ $selectedKabupaten ?: 'Semua Wilayah Kerja' }}</span> 
                @if($selectedKelurahan) | Kelurahan: <span class="font-bold text-slate-800">{{ $selectedKelurahan }}</span> @endif
                @if($selectedType) | Tipe Register: <span class="font-bold text-slate-800">{{ $selectedType }}</span> @endif
            </p>
        </div>

        <!-- Key Performance Indicators (KPI Cards) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 my-6">
            <div class="p-3.5 rounded-xl border border-blue-100 bg-blue-50/50">
                <div class="text-[10px] font-bold uppercase text-slate-500">Total Terduga TBC</div>
                <div class="text-2xl font-black text-blue-700 mt-1">{{ number_format($kpi['total_terduga'] ?? 0) }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Skrining awal suspek</div>
            </div>
            <div class="p-3.5 rounded-xl border border-emerald-100 bg-emerald-50/50">
                <div class="text-[10px] font-bold uppercase text-slate-500">Terkonfirmasi TB</div>
                <div class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($kpi['total_terkonfirmasi'] ?? 0) }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Bakteriologis / TCM Positif</div>
            </div>
            <div class="p-3.5 rounded-xl border border-indigo-100 bg-indigo-50/50">
                <div class="text-[10px] font-bold uppercase text-slate-500">Dalam Pengobatan OAT</div>
                <div class="text-2xl font-black text-indigo-700 mt-1">{{ number_format($kpi['total_pengobatan'] ?? 0) }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Sedang terapi aktif</div>
            </div>
            <div class="p-3.5 rounded-xl border border-rose-100 bg-rose-50/50">
                <div class="text-[10px] font-bold uppercase text-slate-500">TBC Anak & Komorbid</div>
                <div class="text-2xl font-black text-rose-700 mt-1">{{ number_format(($kpi['total_anak'] ?? 0) + ($kpi['total_hiv'] ?? 0)) }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Anak {{ $kpi['total_anak'] ?? 0 }} | HIV {{ $kpi['total_hiv'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Table Sebaran Kasus per Kelurahan -->
        <div class="my-6">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Sebaran Kasus & Beban Wilayah Kelurahan:</h4>
            <table class="w-full text-left text-xs border border-slate-200">
                <thead class="bg-slate-100 text-slate-700 font-bold">
                    <tr>
                        <th class="p-2 border-b border-r border-slate-200 text-center w-10">No</th>
                        <th class="p-2 border-b border-r border-slate-200">Desa / Kelurahan</th>
                        <th class="p-2 border-b border-r border-slate-200 text-center">Total Kasus</th>
                        <th class="p-2 border-b border-r border-slate-200 text-center">Status Hotspot Kluster</th>
                        <th class="p-2 border-b border-slate-200 text-center">Rekomendasi Investigasi Kontak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($kelurahan_chart['labels'] ?? [] as $i => $kel)
                    @php 
                        $tot = $kelurahan_chart['values'][$i] ?? 0;
                    @endphp
                    <tr>
                        <td class="p-2 border-r border-slate-200 text-center text-slate-500">{{ $i + 1 }}</td>
                        <td class="p-2 border-r border-slate-200 font-semibold text-slate-800">{{ $kel }}</td>
                        <td class="p-2 border-r border-slate-200 text-center font-bold text-slate-700">{{ $tot }}</td>
                        <td class="p-2 border-r border-slate-200 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tot >= 10 ? 'bg-rose-100 text-rose-800' : ($tot >= 4 ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $tot >= 10 ? 'Hotspot Tinggi (≥10)' : ($tot >= 4 ? 'Kluster Sedang' : 'Terkendali') }}
                            </span>
                        </td>
                        <td class="p-2 text-center text-slate-600 font-medium">
                            {{ $tot >= 4 ? 'Wajib Skrining Radius 500m' : 'Pemantauan Rutin PMO' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-slate-400">Tidak ada data wilayah tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Rekomendasi Program & Tanda Tangan -->
        <div class="mt-8 pt-6 border-t border-slate-200">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Rekomendasi Program Penanggulangan TBC:</h4>
            <ul class="text-xs text-slate-600 list-disc list-inside space-y-1 mb-10">
                <li>Melakukan Investigasi Kontak (IK) minimal 10-15 orang kontak erat serumah dan tetangga sekitar untuk setiap 1 kasus indeks TBC aktif.</li>
                <li>Pemberian Terapi Pencegahan Tuberkulosis (TPT) bagi balita kontak serumah dan populasi rentan non-TBC aktif.</li>
                <li>Pengawasan Ketat Pengawas Menelan Obat (PMO) digital melalui pesan WhatsApp untuk meminimalkan insiden putus obat (*loss to follow up*).</li>
            </ul>

            <div class="flex justify-between items-start text-xs pt-4">
                <div>
                    <div class="text-slate-500">Mengetahui,</div>
                    <div class="font-bold text-slate-800 mt-1">Kepala UPT Puskesmas</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-slate-900 underline">dr. Pimpinan Puskesmas, M.Kes</div>
                    <div class="text-slate-500">NIP. 19781203 200501 1 004</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-500">{{ $selectedKabupaten ?: 'Tangerang' }}, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold text-slate-800 mt-1">Pengelola Program P2TB</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-slate-900 underline">Waspada TBC, S.Kep., Ns</div>
                    <div class="text-slate-500">NIP. 19860417 201001 1 008</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
