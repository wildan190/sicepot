<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif SPM KIA (Ibu Hamil) - SICEPOT</title>
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
        <a href="{{ route('anc.dashboard') }}" class="ant-btn ant-btn-default">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="ant-btn ant-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Report Page Document -->
    <div class="print-page max-w-4xl mx-auto bg-white rounded-ant-lg shadow-ant-card p-8 sm:p-12 border border-ant-border">

        <!-- Header / Kop Surat -->
        <div class="border-b-2 border-ant-title pb-4 mb-6 flex items-center gap-6">
            <div class="w-16 h-16 rounded-ant-lg bg-primary text-white flex items-center justify-center font-black text-2xl shadow-ant-card">
                SP
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-black text-ant-title tracking-tight uppercase">PEMERINTAH DAERAH KABUPATEN / KOTA</h1>
                <h2 class="text-sm font-bold text-primary uppercase">DINAS KESEHATAN • UNIT PELAKSANA TEKNIS PUSKESMAS</h2>
                <p class="text-xs text-ant-secondary mt-0.5">Sistem Cepat Post dan Tracking (SICEPOT) - Rekapitulasi Kohort KIA & P4K</p>
            </div>
            <div class="text-right text-xs text-ant-secondary font-mono">
                <div>Dokumen: SPM-KIA-01</div>
                <div>Tanggal: {{ now()->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        <!-- Report Title -->
        <div class="text-center my-6">
            <h3 class="text-lg font-black text-ant-title uppercase underline underline-offset-4 tracking-wider">
                LAPORAN EKSEKUTIF STANDAR PELAYANAN MINIMAL (SPM) KESEHATAN IBU HAMIL
            </h3>
            <p class="text-xs text-ant-secondary mt-1.5 font-medium">
                Wilayah: <span class="font-bold text-ant-title">{{ $selectedKabupaten ?: 'Semua Wilayah Kerja' }}</span> 
                @if($selectedKelurahan) | Kelurahan: <span class="font-bold text-ant-title">{{ $selectedKelurahan }}</span> @endif
                | Periode: <span class="font-bold text-ant-title">{{ $selectedBulan ?: 'Semua Bulan' }}</span>
            </p>
        </div>

        <!-- Key Performance Indicators (KPI Cards) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 my-6">
            <div class="p-3.5 rounded-ant border border-primary-100 bg-primary-50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Total Sasaran Bumil</div>
                <div class="text-2xl font-black text-primary mt-1">{{ number_format($kpi['total_all'] ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Ibu hamil terdata</div>
            </div>
            <div class="p-3.5 rounded-ant border border-success-100 bg-success-50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Cakupan K1 / K4</div>
                <div class="text-2xl font-black text-success-600 mt-1">{{ number_format($kpi['total_k1'] ?? 0) }} / {{ number_format($kpi['total_k4'] ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Trimester 1 & Lengkap</div>
            </div>
            <div class="p-3.5 rounded-ant border border-danger-100 bg-danger-50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Kasus Risiko Tinggi</div>
                <div class="text-2xl font-black text-danger mt-1">{{ number_format($kpi['total_risti'] ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">KRT & KRST Poedji R.</div>
            </div>
            <div class="p-3.5 rounded-ant border border-warning-100 bg-warning-50">
                <div class="text-[10px] font-bold uppercase text-ant-secondary">Bumil Anemia / KEK</div>
                <div class="text-2xl font-black text-warning-600 mt-1">{{ number_format($kpi['total_anemia'] ?? 0) }}</div>
                <div class="text-[10px] text-ant-secondary mt-0.5">Perlu intervensi gizi</div>
            </div>
        </div>

        <!-- Breakdown Poedji Rochjati & Rekomendasi Faskes -->
        <div class="my-6 p-4 rounded-ant-lg bg-[#fafafa] border border-ant-border">
            <h4 class="text-xs font-bold text-ant-title uppercase tracking-wider mb-3">Distribusi Kategori Skrining Poedji Rochjati:</h4>
            <div class="grid grid-cols-3 gap-3 text-center text-xs">
                <div class="p-2.5 rounded-ant bg-white border border-success-100">
                    <div class="font-bold text-success-600">KRR (Risiko Rendah)</div>
                    <div class="text-lg font-black text-success-600 mt-0.5">{{ $kpi['total_krr'] ?? 0 }}</div>
                    <div class="text-[10px] text-ant-secondary mt-0.5">Rujukan: Bidan / BPM / Non-PONED</div>
                </div>
                <div class="p-2.5 rounded-ant bg-white border border-warning-100">
                    <div class="font-bold text-warning-600">KRT (Risiko Tinggi)</div>
                    <div class="text-lg font-black text-warning-600 mt-0.5">{{ $kpi['total_krt'] ?? 0 }}</div>
                    <div class="text-[10px] text-ant-secondary mt-0.5">Rujukan: Puskesmas PONED / Dokter</div>
                </div>
                <div class="p-2.5 rounded-ant bg-white border border-danger-100">
                    <div class="font-bold text-danger">KRST (Sangat Tinggi)</div>
                    <div class="text-lg font-black text-danger mt-0.5">{{ $kpi['total_krst'] ?? 0 }}</div>
                    <div class="text-[10px] text-ant-secondary mt-0.5">Rujukan: Rumah Sakit PONEK</div>
                </div>
            </div>
        </div>

        <!-- Table Sebaran Kasus per Desa / Kelurahan -->
        <div class="my-6">
            <h4 class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2">Sebaran Wilayah Kerja & Capaian Deteksi Dini:</h4>
            <table class="w-full text-left text-xs border border-ant-border">
                <thead class="bg-ant-layout text-ant-text font-bold">
                    <tr>
                        <th class="p-2 border-b border-r border-ant-border text-center w-10">No</th>
                        <th class="p-2 border-b border-r border-ant-border">Desa / Kelurahan</th>
                        <th class="p-2 border-b border-r border-ant-border text-center">Total Bumil</th>
                        <th class="p-2 border-b border-r border-ant-border text-center">Kasus RISTI</th>
                        <th class="p-2 border-b border-ant-border text-center">Status Kewaspadaan</th>
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
                        <td class="p-2 border-r border-ant-border text-center font-bold text-danger">-</td>
                        <td class="p-2 text-center">
                            <span class="ant-tag text-[10px] font-bold {{ $tot > 5 ? 'ant-tag-gold' : 'ant-tag-green' }}">
                                {{ $tot > 5 ? 'Monitoring Ketat' : 'Terkendali' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-ant-secondary">Tidak ada data wilayah tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Rekomendasi Program & Tanda Tangan -->
        <div class="mt-8 pt-6 border-t border-ant-border">
            <h4 class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2">Rekomendasi Rencana Tindak Lanjut (RTL):</h4>
            <ul class="text-xs text-ant-text list-disc list-inside space-y-1 mb-10">
                <li>Melakukan kunjungan rumah (*home visit*) aktif bagi seluruh ibu hamil berstatus KRT & KRST bersama bidan desa.</li>
                <li>Pemberian Makanan Tambahan (PMT) pemulihan dan pemantauan minum Tablet Tambah Darah (TTD) untuk bumil KEK & Anemia.</li>
                <li>Aktivasi Tim Suami SIAGA dan memastikan minimal 2 calon pendonor darah siap untuk setiap persalinan rujukan.</li>
            </ul>

            <div class="flex justify-between items-start text-xs pt-4">
                <div>
                    <div class="text-ant-secondary">Mengetahui,</div>
                    <div class="font-bold text-ant-title mt-1">Kepala UPT Puskesmas</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-ant-title underline">dr. Hj. Koordinator KIA, M.Kes</div>
                    <div class="text-ant-secondary">NIP. 19820514 200801 2 009</div>
                </div>
                <div class="text-right">
                    <div class="text-ant-secondary">{{ $selectedKabupaten ?: 'Tangerang' }}, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold text-ant-title mt-1">Bidan Penanggung Jawab Program KIA</div>
                    <div class="h-20"></div>
                    <div class="font-bold text-ant-title underline">Bdn. Pengelola Program KIA, S.Tr.Keb</div>
                    <div class="text-ant-secondary">NIP. 19890912 201102 2 003</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
