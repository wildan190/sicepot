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
<body class="bg-ant-layout min-h-screen text-ant-text font-sans p-4 sm:p-8">

    <!-- Action Bar -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('dashboard') }}" class="ant-btn ant-btn-default">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard TBC
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
            <div class="w-16 h-16 rounded-ant-lg bg-primary text-white flex items-center justify-center font-bold text-2xl">
                TB
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-bold text-ant-title tracking-tight uppercase">PEMERINTAH DAERAH KABUPATEN / KOTA</h1>
                <h2 class="text-sm font-bold text-primary uppercase">DINAS KESEHATAN • PROGRAM PENANGGULANGAN TUBERKULOSIS (P2TB)</h2>
                <p class="text-xs text-ant-secondary mt-0.5">Sistem Cepat Post dan Tracking (SICEPOT) - Rekapitulasi Register SITB TB-03 / TB-06</p>
            </div>
            <div class="text-right text-xs text-ant-secondary font-mono">
                <div>Dokumen: SPM-TB-02</div>
                <div>Tanggal: {{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Report Title -->
        <div class="text-center my-6">
            <h3 class="text-lg font-bold text-ant-title uppercase underline underline-offset-4 tracking-wider">
                LAPORAN EKSEKUTIF STANDAR PELAYANAN MINIMAL (SPM) PROGRAM P2 TBC
            </h3>
            <p class="text-xs text-ant-secondary mt-1.5 font-medium">
                Wilayah: <span class="font-bold text-ant-title">{{ $selectedKabupaten ?: 'Semua Wilayah Kerja' }}</span> 
                @if($selectedKelurahan) | Kelurahan: <span class="font-bold text-ant-title">{{ $selectedKelurahan }}</span> @endif
                @if($selectedType) | Tipe Register: <span class="font-bold text-ant-title">{{ $selectedType }}</span> @endif
            </p>
        </div>

        <!-- Key Performance Indicators (KPI Cards) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 my-6">
            <div class="p-3.5 rounded-ant border border-primary-100 bg-primary-50/50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Total Terduga TBC</div>
                <div class="text-2xl font-bold text-primary-600 mt-1">{{ number_format($kpi['total_terduga'] ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Skrining awal suspek</div>
            </div>
            <div class="p-3.5 rounded-ant border border-success-100 bg-success-50/50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Terkonfirmasi TB</div>
                <div class="text-2xl font-bold text-success-600 mt-1">{{ number_format($kpi['total_terkonfirmasi'] ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Bakteriologis / TCM Positif</div>
            </div>
            <div class="p-3.5 rounded-ant border border-primary-100 bg-primary-50/50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Dalam Pengobatan OAT</div>
                <div class="text-2xl font-bold text-primary-600 mt-1">{{ number_format($kpi['total_pengobatan'] ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Sedang terapi aktif</div>
            </div>
            <div class="p-3.5 rounded-ant border border-danger-100 bg-danger-50/50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">TBC Anak & Komorbid</div>
                <div class="text-2xl font-bold text-danger-600 mt-1">{{ number_format(($kpi['total_anak'] ?? 0) + ($kpi['total_hiv'] ?? 0)) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Anak {{ $kpi['total_anak'] ?? 0 }} | HIV {{ $kpi['total_hiv'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Table Sebaran Kasus per Kelurahan -->
        <div class="my-6">
            <h4 class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2">Sebaran Kasus & Beban Wilayah Kelurahan:</h4>
            <table class="w-full text-left text-xs border border-ant-border">
                <thead class="bg-ant-split text-ant-title font-bold">
                    <tr>
                        <th class="p-2 border-b border-r border-ant-border text-center w-10">No</th>
                        <th class="p-2 border-b border-r border-ant-border">Desa / Kelurahan</th>
                        <th class="p-2 border-b border-r border-ant-border text-center">Total Kasus</th>
                        <th class="p-2 border-b border-r border-ant-border text-center">Status Hotspot Kluster</th>
                        <th class="p-2 border-b border-ant-border text-center">Rekomendasi Investigasi Kontak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ant-split">
                    @forelse($kelurahan_chart['labels'] ?? [] as $i => $kel)
                    @php 
                        $tot = $kelurahan_chart['values'][$i] ?? 0;
                    @endphp
                    <tr>
                        <td class="p-2 border-r border-ant-border text-center text-ant-secondary">{{ $i + 1 }}</td>
                        <td class="p-2 border-r border-ant-border font-semibold text-ant-title">{{ $kel }}</td>
                        <td class="p-2 border-r border-ant-border text-center font-bold text-ant-text">{{ $tot }}</td>
                        <td class="p-2 border-r border-ant-border text-center">
                            <span class="px-2 py-0.5 rounded-ant-sm text-[10px] font-bold border {{ $tot >= 10 ? 'bg-danger-50 text-danger-700 border-danger-100' : ($tot >= 4 ? 'bg-warning-50 text-warning-700 border-warning-100' : 'bg-primary-50 text-primary-700 border-primary-100') }}">
                                {{ $tot >= 10 ? 'Hotspot Tinggi (≥10)' : ($tot >= 4 ? 'Kluster Sedang' : 'Terkendali') }}
                            </span>
                        </td>
                        <td class="p-2 text-center text-ant-secondary font-medium">
                            {{ $tot >= 4 ? 'Wajib Skrining Radius 500m' : 'Pemantauan Rutin PMO' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-ant-disabled">Tidak ada data wilayah tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Rekomendasi Program & Tanda Tangan -->
        <div class="mt-8 pt-6 border-t border-ant-split">
            <h4 class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2">Rekomendasi Program Penanggulangan TBC:</h4>
            <ul class="text-xs text-ant-secondary list-disc list-inside space-y-1 mb-10">
                <li>Melakukan Investigasi Kontak (IK) minimal 10-15 orang kontak erat serumah dan tetangga sekitar untuk setiap 1 kasus indeks TBC aktif.</li>
                <li>Pemberian Terapi Pencegahan Tuberkulosis (TPT) bagi balita kontak serumah dan populasi rentan non-TBC aktif.</li>
                <li>Pengawasan Ketat Pengawas Menelan Obat (PMO) digital melalui pesan WhatsApp untuk meminimalkan insiden putus obat (*loss to follow up*).</li>
            </ul>

            <div class="flex justify-between items-start text-xs pt-4">
                <div>
                    <div class="text-ant-secondary">Mengetahui,</div>
                    <div class="font-bold text-ant-title mt-1">Kepala UPT Puskesmas</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-ant-title underline">dr. Pimpinan Puskesmas, M.Kes</div>
                    <div class="text-ant-secondary">NIP. 19781203 200501 1 004</div>
                </div>
                <div class="text-right">
                    <div class="text-ant-secondary">{{ $selectedKabupaten ?: 'Tangerang' }}, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold text-ant-title mt-1">Pengelola Program P2TB</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-ant-title underline">Waspada TBC, S.Kep., Ns</div>
                    <div class="text-ant-secondary">NIP. 19860417 201001 1 008</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
