<x-app-layout>
    <div x-data="ancDashboard()" x-init="initDashboard()">
        <!-- PAGE HEADER -->
        <div class="bg-white border-b border-slate-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-slate-800 tracking-tight flex items-center gap-2.5">
                            <span class="p-2 rounded-xl bg-pink-50 text-pink-600 border border-pink-100 shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </span>
                            Dashboard Pelayanan Ibu Hamil (ANC)
                        </h2>
                        <p class="text-xs md:text-sm text-slate-500 mt-1">Sistem Informasi Pemantauan Kesehatan Ibu Hamil, Kunjungan K1-K6 & Deteksi Risiko Tinggi (RISTI)</p>
                    </div>
                    <div class="flex items-center flex-wrap gap-2">
                        <!-- Ekspor Excel -->
                        <a :href="`{{ route('anc.export.excel') }}?kabupaten=${encodeURIComponent(selectedKabupaten)}&kelurahan=${encodeURIComponent(selectedKelurahan)}&bulan=${encodeURIComponent(selectedBulan)}&search=${encodeURIComponent(searchQuery)}`"
                            title="Unduh Rekapitulasi Data Excel"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Ekspor</span>
                        </a>

                        <!-- Laporan Eksekutif SPM Dinkes -->
                        <a :href="`{{ route('anc.report.executive') }}?kabupaten=${encodeURIComponent(selectedKabupaten)}&kelurahan=${encodeURIComponent(selectedKelurahan)}&bulan=${encodeURIComponent(selectedBulan)}`"
                            target="_blank" title="Cetak Ringkasan Eksekutif SPM Dinkes"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Laporan SPM</span>
                        </a>

                        <!-- Tombol Sirine H-1 -->
                        <button @click="testSirineAudio()" type="button" title="Uji Coba Suara Sirine Peringatan H-1"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <span class="relative flex h-2 w-2 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                            </span>
                            <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                            </svg>
                            <span>Tes Sirine</span>
                        </button>

                        <!-- Import Excel / CSV -->
                        <button @click="showImportModal = true" type="button" title="Import Data Excel atau CSV"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span>Import</span>
                        </button>

                        <!-- Tambah Data -->
                        <button @click="openAddModal()" type="button" title="Tambah Data Baru"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-pink-600 hover:bg-pink-700 active:bg-pink-800 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Tambah Data</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN DASHBOARD CONTENT -->
        <div class="py-8 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- PERINGATAN H-1 PERSALINAN (SIAP SIAGA / SIRINE) -->
                <template x-if="imminentDeliveries && imminentDeliveries.length > 0">
                    <div class="bg-gradient-to-r from-rose-500 via-red-500 to-pink-600 rounded-2xl p-4 sm:p-5 text-white shadow-lg border border-red-400 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 animate-pulse">
                        <div class="flex items-center gap-3.5">
                            <div class="p-3 bg-white/20 backdrop-blur-md rounded-2xl shrink-0">
                                <svg class="w-7 h-7 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full bg-white text-rose-700 text-xs font-black uppercase tracking-wider">Perhatian Khusus H-1</span>
                                    <span class="text-xs text-rose-100 font-medium" x-text="imminentDeliveries.length + ' Ibu Hamil Perkiraan Lahir Besok'"></span>
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold mt-0.5 tracking-tight">Peringatan Hari Perkiraan Lahir (HPL) Tinggal 1 Hari!</h3>
                                <p class="text-xs text-rose-100 mt-0.5">Segera lakukan persiapan pertolongan persalinan, transportasi rujukan, dan kesiapan donor darah (P4K).</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                            <button @click="showH1ModalAlert()" type="button"
                                class="w-full md:w-auto px-4 py-2.5 bg-white hover:bg-rose-50 text-rose-700 text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Lihat Daftar & Bunyikan Sirine</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- FILTER BAR -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 transition-all">
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 flex-1">
                            <!-- Filter Kabupaten -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kabupaten / Kota</label>
                                <div class="relative">
                                    <select x-model="selectedKabupaten" @change="onKabupatenChange()"
                                        class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                        <option value="">Semua Kabupaten / Kota</option>
                                        @foreach($kabupatenList as $kab)
                                            <option value="{{ $kab }}">{{ $kab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Kelurahan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kelurahan / Desa</label>
                                <div class="relative">
                                    <select x-model="selectedKelurahan" @change="applyFilters()"
                                        class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                        <option value="">Semua Kelurahan / Desa</option>
                                        <template x-for="kel in kelurahanList" :key="kel">
                                            <option :value="kel" x-text="kel" :selected="kel === selectedKelurahan"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Bulan -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Bulan Pelaporan</label>
                                <select x-model="selectedBulan" @change="applyFilters()"
                                    class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                    <option value="">Semua Periode</option>
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search & Reset Actions -->
                        <div class="flex items-center gap-2 pt-2 lg:pt-5 border-t lg:border-t-0 border-slate-100">
                            <div class="relative flex-1 sm:w-64">
                                <input type="text" x-model="searchQuery" @keydown.enter.prevent="applyFilters()"
                                    placeholder="Cari NIK / Nama / No RM..."
                                    class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button @click="applyFilters()" type="button"
                                class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl transition-colors cursor-pointer">
                                Filter
                            </button>
                            <button @click="resetFilters()" type="button" title="Reset filter"
                                class="p-2 text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KPI SUMMARY CARDS -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3.5">
                    <!-- Card 1: Total Ibu Hamil Terdaftar -->
                    <div class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Ibu Hamil</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-slate-900" x-text="kpi.total_all">0</span>
                            <span class="p-1.5 rounded-lg bg-pink-50 text-pink-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Terdaftar di Fasyankes</span>
                    </div>

                    <!-- Card 2: Cakupan K1 -->
                    <div class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Kunjungan K1</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-blue-700" x-text="kpi.total_k1">0</span>
                            <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Kontak Pertama Trimester 1</span>
                    </div>

                    <!-- Card 3: Cakupan K4 -->
                    <div class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Cakupan K4 / K6</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-emerald-700" x-text="kpi.total_k4">0</span>
                            <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Standar Kunjungan Lengkap</span>
                    </div>

                    <!-- Card 4: Risiko Tinggi (RISTI) -->
                    <div class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Risiko Tinggi (RISTI)</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-rose-700" x-text="kpi.total_risti">0</span>
                            <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Deteksi Faktor Risiko</span>
                    </div>

                    <!-- Card 5: Anemia / Hb Rendah -->
                    <div class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Kasus Anemia</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-amber-700" x-text="kpi.total_anemia">0</span>
                            <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Hb < 11 g/dL</span>
                    </div>

                    <!-- Card 6: Rujukan Faskes Lanjut -->
                    <div class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-purple-700 uppercase tracking-wider">Dirujuk ke RS</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-purple-700" x-text="kpi.total_rujukan">0</span>
                            <span class="p-1.5 rounded-lg bg-purple-50 text-purple-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Tatalaksana Lanjutan</span>
                    </div>

                    <!-- Card 7: Peringatan Persalinan H-1 (Sirine Alert) -->
                    <div @click="showH1ModalAlert()"
                        :class="kpi.total_h1 > 0 ? 'bg-rose-50/70 border-rose-300 ring-2 ring-rose-300 cursor-pointer hover:bg-rose-100/70' : 'bg-white border-slate-200/80'"
                        class="p-4 rounded-2xl shadow-xs border flex flex-col justify-between transition-all">
                        <div class="flex items-center justify-between">
                            <span :class="kpi.total_h1 > 0 ? 'text-rose-700 font-bold' : 'text-slate-500 font-semibold'"
                                class="text-xs uppercase tracking-wider">H-1 Persalinan</span>
                            <template x-if="kpi.total_h1 > 0">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                                </span>
                            </template>
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span :class="kpi.total_h1 > 0 ? 'text-rose-700' : 'text-slate-900'" class="text-2xl font-bold" x-text="kpi.total_h1 || 0">0</span>
                            <span :class="kpi.total_h1 > 0 ? 'bg-rose-200/60 text-rose-700' : 'bg-slate-100 text-slate-500'" class="p-1.5 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2" x-text="kpi.total_h1 > 0 ? 'Perkiraan Lahir Besok!' : 'Tidak ada H-1'"></span>
                    </div>
                </div>

                <!-- VISUAL CHARTS SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart 1: Persebaran Ibu Hamil per Kelurahan (Bar Chart) -->
                    <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-base text-slate-800">Distribusi Ibu Hamil Terbanyak per Kelurahan</h3>
                                <p class="text-xs text-slate-500">10 Kelurahan / Desa dengan ibu hamil terdaftar tertinggi</p>
                            </div>
                        </div>
                        <div class="h-72">
                            <canvas id="ancKelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Proporsi Usia Ibu Hamil (Doughnut) -->
                    <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Kelompok Usia Ibu Hamil</h3>
                            <p class="text-xs text-slate-500 mb-4">Klasifikasi risiko umur kehamilan</p>
                            <div class="h-52 relative">
                                <canvas id="ancAgeChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-amber-600 font-medium">< 20 th (Muda)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.umur_muda">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-emerald-600 font-medium">20–35 (Produktif)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.umur_produktif">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-rose-600 font-medium">> 35 th (Risiko)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.umur_risti">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Tren Pendaftaran Bulanan (Line Chart) -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Tren Registrasi ANC per Bulan</h3>
                            <p class="text-xs text-slate-500">Perkembangan jumlah kunjungan pemeriksaan ibu hamil sepanjang tahun</p>
                        </div>
                    </div>
                    <div class="h-60">
                        <canvas id="ancMonthlyChart"></canvas>
                    </div>
                </div>

                <!-- INNOVATION 2 & 3: SKOR POEDJI ROCHJATI & PETA GEOSPASIAL IBU HAMIL -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Poedji Rochjati Classification Card -->
                    <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-bold text-base text-slate-800">Skor Poedji Rochjati (Kategori Risiko)</h3>
                                    <p class="text-xs text-slate-500">Standar skrining faskes rujukan persalinan</p>
                                </div>
                                <span class="p-2 rounded-xl bg-pink-50 text-pink-600 border border-pink-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="h-48 relative">
                                <canvas id="poedjiChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 space-y-2 text-xs">
                            <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/60 border border-emerald-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span class="font-semibold text-emerald-800">KRR (Skor 2)</span>
                                    <span class="text-[11px] text-emerald-600 hidden sm:inline">- Bidan / Puskesmas</span>
                                </div>
                                <span class="font-bold text-emerald-700" x-text="poedjiChartData?.values?.[0] || 0">0</span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-xl bg-amber-50/60 border border-amber-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                    <span class="font-semibold text-amber-800">KRT (Skor 6-10)</span>
                                    <span class="text-[11px] text-amber-600 hidden sm:inline">- Puskesmas PONED/RS</span>
                                </div>
                                <span class="font-bold text-amber-700" x-text="poedjiChartData?.values?.[1] || 0">0</span>
                            </div>
                            <div class="flex items-center justify-between p-2 rounded-xl bg-rose-50/60 border border-rose-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span class="font-semibold text-rose-800">KRST (Skor ≥ 12)</span>
                                    <span class="text-[11px] text-rose-600 hidden sm:inline">- RS PONEK</span>
                                </div>
                                <span class="font-bold text-rose-700" x-text="poedjiChartData?.values?.[2] || 0">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- GIS Leaflet Map Section -->
                    <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    <span class="p-2.5 rounded-xl bg-pink-50 text-pink-600 border border-pink-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="font-bold text-base text-slate-800">Peta Sebaran Ibu Hamil & Deteksi Wilayah RISTI</h3>
                                        <p class="text-xs text-slate-500">Konsentrasi ibu hamil dan deteksi dini kasus risiko tinggi per kelurahan</p>
                                    </div>
                                </div>
                                <div class="flex items-center flex-wrap gap-2 text-xs">
                                    <div class="inline-flex rounded-xl p-0.5 bg-slate-100 border border-slate-200">
                                        <button @click="mapViewMode = 'markers'; updateMap()" type="button"
                                            :class="mapViewMode === 'markers' ? 'bg-white text-slate-800 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                            class="px-2.5 py-1 rounded-lg transition-colors cursor-pointer text-[11px]">
                                            Titik Sebaran
                                        </button>
                                        <button @click="mapViewMode = 'heatmap'; updateMap()" type="button"
                                            :class="mapViewMode === 'heatmap' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                            class="px-2.5 py-1 rounded-lg transition-colors cursor-pointer text-[11px] inline-flex items-center gap-1">
                                            <span>Heatmap Risiko KEK</span>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs ml-2">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                            <span class="text-slate-600 font-medium">Ada RISTI</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                                            <span class="text-slate-600 font-medium">Bumil Normal</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="ancMap" style="height: 380px; width: 100%; min-height: 350px; isolation: isolate;" class="map-container w-full rounded-xl border border-slate-200 z-0 overflow-hidden shadow-inner relative"></div>
                        </div>
                    </div>
                </div>

                <!-- DATA TABLE SECTION -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
                    <div class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Daftar Rekam Pemeriksaan Ibu Hamil (ANC)</h3>
                            <p class="text-xs text-slate-500">Data dapat di-edit langsung secara realtime melalui tombol aksi di setiap baris</p>
                        </div>
                        <span class="text-xs font-medium px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg self-start sm:self-auto"
                            x-text="'Menampilkan (' + (patientsData.data ? patientsData.data.length : 0) + ' dari ' + patientsData.total + ' total rekam data)'"></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50/80 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200/80">
                                <tr>
                                    <th class="px-4 py-3">Nama & NIK</th>
                                    <th class="px-4 py-3">Nama Suami & Telp</th>
                                    <th class="px-4 py-3">Umur & G-P-A</th>
                                    <th class="px-4 py-3">Usia Hamil & Kunjungan</th>
                                    <th class="px-4 py-3">HPL / TP</th>
                                    <th class="px-4 py-3">Wilayah (Kab / Kel)</th>
                                    <th class="px-4 py-3">LiLA & Hb</th>
                                    <th class="px-4 py-3">Status RISTI & Risiko</th>
                                    <th class="px-4 py-3">Poedji Rochjati</th>
                                    <th class="px-4 py-3">Rekomendasi Faskes</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="p in (patientsData.data || [])" :key="p.id">
                                    <tr class="hover:bg-pink-50/20 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800 text-sm" x-text="p.nama_lengkap || '-'"></div>
                                            <div class="text-slate-400 text-[11px]" x-text="'NIK: ' + (p.nik || '-')"></div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            <div class="font-medium text-slate-700" x-text="p.nama_suami ? 'Tn. ' + p.nama_suami : '-'"></div>
                                            <template x-if="p.no_telepon">
                                                <div class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    <span x-text="p.no_telepon"></span>
                                                </div>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                            <span class="font-medium" x-text="(p.umur !== null ? p.umur + ' th' : '-')"></span>
                                            <template x-if="p.tanggal_lahir">
                                                <div class="text-[10px] text-slate-400" x-text="p.tanggal_lahir ? p.tanggal_lahir.substring(0, 10) : ''"></div>
                                            </template>
                                            <div class="text-[11px] text-pink-600 font-semibold" x-text="'G' + (p.gravida || 1) + 'P' + (p.para || 0) + 'A' + (p.abortus || 0)"></div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-700" x-text="p.usia_kehamilan ? p.usia_kehamilan + ' mgg' : (p.kunjungan_ke || '-')"></div>
                                            <div class="text-[11px] text-pink-600 font-medium" x-text="p.kunjungan_ke || ''"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-medium text-slate-800" x-text="p.hpl ? p.hpl.substring(0, 10) : '-'"></div>
                                            <template x-if="isDueTomorrow(p.hpl)">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 animate-pulse border border-rose-300 mt-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                                    H-1 Lahir!
                                                </span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-slate-700" x-text="p.kelurahan || '-'"></div>
                                            <div class="text-[11px] text-slate-400" x-text="p.kabupaten || '-'"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold text-slate-700" x-text="p.lila ? p.lila + ' cm' : (p.hb ? p.hb + ' g/dL' : '-')"></span>
                                                <template x-if="p.lila && parseFloat(p.lila) < 23.5">
                                                    <span class="px-1.5 py-0.2 bg-amber-100 text-amber-800 border border-amber-300 rounded text-[9px] font-black">KEK</span>
                                                </template>
                                            </div>
                                            <template x-if="p.status_anemia && p.status_anemia !== '-'">
                                                <span :class="{
                                                    'bg-rose-50 text-rose-700 border-rose-200': p.status_anemia.toLowerCase().includes('anemia'),
                                                    'bg-emerald-50 text-emerald-700 border-emerald-200': !p.status_anemia.toLowerCase().includes('anemia')
                                                }" class="px-1.5 py-0.5 rounded text-[10px] font-semibold border inline-block mt-0.5"
                                                    x-text="p.status_anemia"></span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <template x-if="p.status_risti && p.status_risti.toLowerCase().includes('tinggi')">
                                                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-md text-[10px] font-bold inline-block">
                                                        RISTI
                                                    </span>
                                                </template>
                                                <template x-if="!p.status_risti || !p.status_risti.toLowerCase().includes('tinggi')">
                                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-semibold inline-block">
                                                        Normal
                                                    </span>
                                                </template>
                                            </div>
                                            <template x-if="p.faktor_risiko">
                                                <div class="text-[10px] text-rose-600 font-semibold mt-0.5 max-w-[140px] truncate" :title="p.faktor_risiko" x-text="p.faktor_risiko"></div>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <span :class="{
                                                    'bg-emerald-100 text-emerald-800 border-emerald-300': p.kategori_poedji_rochjati === 'KRR',
                                                    'bg-amber-100 text-amber-800 border-amber-300': p.kategori_poedji_rochjati === 'KRT',
                                                    'bg-rose-100 text-rose-800 border-rose-300': p.kategori_poedji_rochjati === 'KRST',
                                                    'bg-slate-100 text-slate-600 border-slate-200': !p.kategori_poedji_rochjati
                                                }" class="px-2 py-0.5 rounded-md font-bold text-[11px] border"
                                                    x-text="p.kategori_poedji_rochjati || 'KRR'"></span>
                                                <span class="text-slate-500 text-[11px] font-semibold" x-text="'(' + (p.skor_poedji_rochjati ?? 2) + ')'"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-slate-700 font-medium text-[11px]" x-text="p.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></span>
                                            <template x-if="p.calon_pendonor">
                                                <div class="text-[10px] text-rose-600 font-semibold mt-0.5" x-text="'Donor: ' + p.calon_pendonor"></div>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <!-- View / Detail Pasien Modal -->
                                                <button @click="openViewModal(p)" type="button"
                                                    class="p-1.5 text-pink-600 hover:text-pink-800 hover:bg-pink-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Lihat Rekam Lengkap Ibu Hamil">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>

                                                <!-- WhatsApp Direct Quick Reminder -->
                                                <button @click="openWhatsAppModal(p)" type="button"
                                                    class="p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Kirim Pesan WhatsApp (wa.me)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Skrining Duplikasi -->
                                                <button @click="openDuplicateModal(p)" type="button"
                                                    class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Skrining Data Ganda / Lintas Wilayah">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Rekam Jejak / Cohort Timeline -->
                                                <button @click="openTimelineModal(p)" type="button"
                                                    class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Rekam Jejak Pasien (Cohort Timeline)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>

                                                <!-- AI Triage (Gemini) -->
                                                <button @click="openAiTriage(p)" type="button"
                                                    class="p-1.5 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-colors cursor-pointer"
                                                    title="AI Triage & Deteksi Anomali (Google Gemini)">
                                                    <span class="text-xs font-black px-1 py-0.5 bg-purple-100 text-purple-700 rounded">AI</span>
                                                </button>

                                                <!-- Edit Pasien -->
                                                <button @click="openEditModal(p)" type="button"
                                                    class="p-1.5 text-pink-600 hover:text-pink-900 hover:bg-pink-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Edit Data Realtime">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Hapus Pasien -->
                                                <button @click="deletePatient(p)" type="button"
                                                    class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Hapus Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="!patientsData.data || patientsData.data.length === 0">
                                    <tr>
                                        <td colspan="11" class="text-center py-8 text-slate-400">
                                            Tidak ada data ibu hamil yang cocok dengan filter yang dipilih. Silakan klik tombol "Import Excel / CSV" atau "Tambah Data".
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION CONTROLS -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-600">
                        <span x-text="'Halaman ' + (patientsData.current_page || 1) + ' dari ' + (patientsData.last_page || 1)"></span>
                        <div class="flex items-center gap-2">
                            <button :disabled="(patientsData.current_page || 1) <= 1"
                                @click="changePage((patientsData.current_page || 1) - 1)"
                                class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">Sebelumnya</button>
                            <button :disabled="(patientsData.current_page || 1) >= (patientsData.last_page || 1)"
                                @click="changePage((patientsData.current_page || 1) + 1)"
                                class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">Berikutnya</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: IMPORT & PREVIEW EXCEL/CSV UNIVERSAL ================= -->
            <div x-show="showImportModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="closeImportModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Import Data Ibu Hamil (Universal Excel / CSV)</h3>
                                <p class="text-xs text-slate-500">Mendukung format otomatis register KIA, kohort ANC fasyankes, atau berkas custom puskesmas tanpa batasan template kaku.</p>
                            </div>
                            <button @click="closeImportModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4">
                            <!-- Step 1: Upload Box with Full Drag & Drop Support -->
                            <div x-show="!importPreview" @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false" @drop.prevent="onFileDrop($event)"
                                :class="isDragging ? 'border-pink-500 bg-pink-100/50 scale-[1.01] ring-4 ring-pink-200' : 'border-pink-200 bg-pink-50/20 hover:bg-pink-50/40'"
                                class="border-2 border-dashed rounded-2xl p-10 text-center transition-all duration-200 cursor-pointer relative">
                                <input type="file" id="ancFileInput" @change="onFileSelected($event)" accept=".xlsx,.xls,.csv" class="hidden">
                                <label for="ancFileInput" class="cursor-pointer flex flex-col items-center">
                                    <div :class="isDragging ? 'bg-pink-600 text-white scale-110' : 'bg-pink-100 text-pink-600'"
                                        class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3.5 transition-all duration-200 shadow-xs">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-base font-bold text-slate-800"
                                        x-text="isDragging ? 'Lepaskan berkas di sini untuk mem-parsing' : 'Tarik & Letakkan (Drag & Drop) berkas Excel / CSV di sini'"></span>
                                    <span class="text-xs text-slate-500 mt-1">atau <span class="text-pink-600 font-semibold underline underline-offset-2 hover:text-pink-800">klik untuk menjelajahi komputer</span></span>
                                    <span class="text-[11px] text-slate-400 mt-2">Mendukung berkas Excel (.xlsx, .xls) & CSV</span>
                                </label>
                                <div x-show="isParsing" class="mt-4 flex items-center justify-center gap-2 text-pink-600 text-xs font-semibold">
                                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Sedang membaca dan mem-parsing berkas ANC secara universal...
                                </div>
                            </div>

                            <!-- Step 2: Preview Area -->
                            <div x-show="importPreview" class="space-y-4">
                                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-emerald-600 text-white rounded text-xs font-bold" x-text="importPreview?.type_label"></span>
                                            <span class="font-bold text-emerald-950 text-sm" x-text="importPreview?.original_name"></span>
                                        </div>
                                        <div class="text-xs text-emerald-700 mt-1"
                                            x-text="'Fasyankes: ' + (importPreview?.fasyankes_name || '-') + ' | Periode: ' + (importPreview?.period || '-')"></div>
                                        <div class="text-[11px] text-emerald-600 mt-1 flex flex-wrap gap-1 items-center">
                                            <span class="font-semibold text-emerald-800">Kolom Terdeteksi:</span>
                                            <template x-for="field in (importPreview?.detected_fields || [])" :key="field">
                                                <span class="px-1.5 py-0.2 bg-emerald-100 text-emerald-800 rounded font-mono text-[10px]" x-text="field"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-slate-500">Total Baris Terdeteksi:</span>
                                        <div class="text-xl font-black text-emerald-800" x-text="(importPreview?.total_rows || 0) + ' Baris'"></div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Cuplikan Preview 10 Baris Pertama:</h4>
                                    <div class="overflow-x-auto max-h-60 border border-slate-200 rounded-xl">
                                        <table class="w-full text-left text-xs">
                                            <thead class="bg-slate-100 text-slate-700 font-semibold sticky top-0">
                                                <tr>
                                                    <th class="p-2.5">No</th>
                                                    <th class="p-2.5">Nama Lengkap</th>
                                                    <th class="p-2.5">Nama Suami</th>
                                                    <th class="p-2.5">NIK</th>
                                                    <th class="p-2.5">Telp</th>
                                                    <th class="p-2.5">Umur / Tgl Lahir</th>
                                                    <th class="p-2.5">G-P-A</th>
                                                    <th class="p-2.5">HPL / TP</th>
                                                    <th class="p-2.5">LiLA</th>
                                                    <th class="p-2.5">Kelurahan</th>
                                                    <th class="p-2.5">Risiko</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                <template x-for="(row, idx) in (importPreview?.preview_samples || [])" :key="idx">
                                                    <tr class="hover:bg-slate-50">
                                                        <td class="p-2.5 text-slate-500" x-text="idx + 1"></td>
                                                        <td class="p-2.5 font-medium text-slate-800" x-text="row.nama_lengkap || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.nama_suami || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.nik || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.no_telepon || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="(row.umur ? row.umur + ' th' : (row.tanggal_lahir || '-'))"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.gravida ? 'G' + row.gravida + (row.para !== undefined ? 'P' + row.para : '') + (row.abortus !== undefined ? 'A' + row.abortus : '') : '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.hpl || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.lila ? row.lila + ' cm' : '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.kelurahan || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.faktor_risiko || (row.status_risti || 'Normal')"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <button x-show="importPreview" @click="resetImport()" type="button"
                                class="text-xs font-semibold text-slate-500 hover:text-slate-800 cursor-pointer">
                                ← Ganti Berkas
                            </button>
                            <div class="flex items-center gap-2 ml-auto">
                                <button @click="closeImportModal()" type="button"
                                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">Batal</button>
                                <button x-show="importPreview" :disabled="isSubmittingImport" @click="commitImport()"
                                    type="button"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-2 disabled:opacity-50 cursor-pointer">
                                    <span x-show="!isSubmittingImport">Submit & Simpan ke Database</span>
                                    <span x-show="isSubmittingImport" class="flex items-center gap-2">
                                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        Menyimpan Data...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: EDIT IBU HAMIL REALTIME ================= -->
            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showEditModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Edit Data Ibu Hamil (Real-time)</h3>
                                <p class="text-xs text-slate-500">Perubahan data akan langsung terupdate di tabel & indikator dashboard</p>
                            </div>
                            <button @click="showEditModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="saveEditPatient()" class="mt-4 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" x-model="editingPatient.nama_lengkap" required
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Suami</label>
                                    <input type="text" x-model="editingPatient.nama_suami" placeholder="Nama suami"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">NIK</label>
                                    <input type="text" x-model="editingPatient.nik" placeholder="16 digit NIK"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">No. Telp / WhatsApp</label>
                                    <input type="text" x-model="editingPatient.no_telepon" placeholder="Nomor telepon/HP"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" x-model="editingPatient.tanggal_lahir"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Umur (Thn)</label>
                                    <input type="number" x-model="editingPatient.umur"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Gravida (G)</label>
                                    <input type="number" x-model="editingPatient.gravida"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Para (P)</label>
                                    <input type="number" x-model="editingPatient.para"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Abortus (A)</label>
                                    <input type="number" x-model="editingPatient.abortus"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Usia Kehamilan (Minggu)</label>
                                    <input type="text" x-model="editingPatient.usia_kehamilan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kunjungan Ke</label>
                                    <select x-model="editingPatient.kunjungan_ke"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="K1">K1 (Trimester 1)</option>
                                        <option value="K2">K2 (Trimester 1 / 2)</option>
                                        <option value="K3">K3 (Trimester 2)</option>
                                        <option value="K4">K4 (Trimester 3)</option>
                                        <option value="K5">K5 (Trimester 3)</option>
                                        <option value="K6">K6 (Trimester 3)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">LiLA (cm)</label>
                                    <input type="number" step="0.1" x-model="editingPatient.lila" placeholder="misal: 23.5"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kadar Hb (g/dL)</label>
                                    <input type="text" x-model="editingPatient.hb" placeholder="misal: 11.5"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">HPHT</label>
                                    <input type="date" x-model="editingPatient.hpht"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-rose-700 mb-1">HPL / TP (Taksiran Persalinan)</label>
                                    <input type="date" x-model="editingPatient.hpl"
                                        class="w-full px-3 py-2 text-xs border border-rose-300 bg-rose-50/20 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Status RISTI</label>
                                    <select x-model="editingPatient.status_risti"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="Normal">Normal</option>
                                        <option value="Risiko Tinggi">Risiko Tinggi (RISTI)</option>
                                        <option value="Risiko Sangat Tinggi">Risiko Sangat Tinggi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Status Anemia</label>
                                    <select x-model="editingPatient.status_anemia"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="Tidak Anemia">Tidak Anemia</option>
                                        <option value="Anemia Ringan">Anemia Ringan</option>
                                        <option value="Anemia Sedang">Anemia Sedang</option>
                                        <option value="Anemia Berat">Anemia Berat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Faktor Risiko (jika ada)</label>
                                    <input type="text" x-model="editingPatient.faktor_risiko" placeholder="misal: KEK, Anemia, HT"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kabupaten / Kota</label>
                                    <input type="text" x-model="editingPatient.kabupaten"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kelurahan / Desa</label>
                                    <input type="text" x-model="editingPatient.kelurahan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Calon Pendonor Darah (P4K)</label>
                                <input type="text" x-model="editingPatient.calon_pendonor" placeholder="Nama & Gol. Darah calon pendonor"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea rows="2" x-model="editingPatient.alamat_lengkap"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500"></textarea>
                            </div>

                            <div class="p-3 bg-pink-50/50 rounded-xl border border-pink-100 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-semibold text-pink-900">Klasifikasi Poedji Rochjati Otomatis:</span>
                                    <span class="ml-1 text-slate-600" x-text="(editingPatient.kategori_poedji_rochjati || 'KRR') + ' (Skor: ' + (editingPatient.skor_poedji_rochjati ?? 2) + ')'"></span>
                                </div>
                                <div class="text-[11px] text-pink-700 font-medium" x-text="editingPatient.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></div>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button @click="showEditModal = false" type="button"
                                    class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                                <button type="submit"
                                    class="px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-semibold shadow-xs cursor-pointer">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: TAMBAH IBU HAMIL CEPAT ================= -->
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAddModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Tambah Data Ibu Hamil Baru</h3>
                                <p class="text-xs text-slate-500">Input data pemeriksaan ANC langsung ke database</p>
                            </div>
                            <button @click="showAddModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="saveNewPatient()" class="mt-4 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" x-model="newPatient.nama_lengkap" required placeholder="Nama lengkap ibu"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Suami</label>
                                    <input type="text" x-model="newPatient.nama_suami" placeholder="Nama suami"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">NIK</label>
                                    <input type="text" x-model="newPatient.nik" placeholder="16 digit NIK"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">No. Telp / WhatsApp</label>
                                    <input type="text" x-model="newPatient.no_telepon" placeholder="Nomor telepon/HP"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                                    <input type="date" x-model="newPatient.tanggal_lahir"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Umur (Thn)</label>
                                    <input type="number" x-model="newPatient.umur" placeholder="25"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Gravida (G)</label>
                                    <input type="number" x-model="newPatient.gravida" value="1"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kunjungan</label>
                                    <select x-model="newPatient.kunjungan_ke"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="K1">K1</option>
                                        <option value="K2">K2</option>
                                        <option value="K3">K3</option>
                                        <option value="K4">K4</option>
                                        <option value="K5">K5</option>
                                        <option value="K6">K6</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">LiLA (cm)</label>
                                    <input type="number" step="0.1" x-model="newPatient.lila" placeholder="misal: 23.5"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kadar Hb (g/dL)</label>
                                    <input type="text" x-model="newPatient.hb" placeholder="misal: 11.5"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">HPHT</label>
                                    <input type="date" x-model="newPatient.hpht"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-rose-700 mb-1">HPL / TP (Taksiran Persalinan)</label>
                                    <input type="date" x-model="newPatient.hpl"
                                        class="w-full px-3 py-2 text-xs border border-rose-300 bg-rose-50/20 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Status RISTI</label>
                                    <select x-model="newPatient.status_risti"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="Normal">Normal</option>
                                        <option value="Risiko Tinggi">Risiko Tinggi (RISTI)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Status Anemia</label>
                                    <select x-model="newPatient.status_anemia"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                        <option value="Tidak Anemia">Tidak Anemia</option>
                                        <option value="Anemia Ringan">Anemia Ringan</option>
                                        <option value="Anemia Sedang">Anemia Sedang</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Faktor Risiko</label>
                                    <input type="text" x-model="newPatient.faktor_risiko" placeholder="misal: KEK, KRT, HT"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kabupaten / Kota</label>
                                    <input type="text" x-model="newPatient.kabupaten" placeholder="misal: Kab. Tangerang"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kelurahan / Desa</label>
                                    <input type="text" x-model="newPatient.kelurahan" placeholder="misal: Pagedangan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Calon Pendonor Darah (P4K)</label>
                                <input type="text" x-model="newPatient.calon_pendonor" placeholder="Nama calon pendonor & Gol. Darah (Opsional)"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea rows="2" x-model="newPatient.alamat_lengkap" placeholder="Alamat domisili"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500"></textarea>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button @click="showAddModal = false" type="button"
                                    class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                                <button type="submit" :disabled="isSubmittingNewPatient"
                                    class="px-5 py-2 bg-pink-600 hover:bg-pink-700 active:bg-pink-800 text-white rounded-xl font-semibold shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50">
                                    <span x-show="!isSubmittingNewPatient">Simpan Ibu Hamil</span>
                                    <span x-show="isSubmittingNewPatient" class="flex items-center gap-2">
                                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: WHATSAPP DIRECT SENDER ================= -->
            <div x-show="showWhatsAppModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showWhatsAppModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-xl bg-emerald-100 text-emerald-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Kirim Pengingat WhatsApp (wa.me)</h3>
                                    <p class="text-xs text-slate-500" x-text="targetPatient?.nama_lengkap"></p>
                                </div>
                            </div>
                            <button @click="showWhatsAppModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-3.5 text-xs">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Pilih Target Penerima:</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="waRecipient = 'ibu'; prepareWaMessage()"
                                        :class="waRecipient === 'ibu' ? 'bg-emerald-50 border-emerald-500 text-emerald-800 font-bold ring-2 ring-emerald-200' : 'bg-slate-50 border-slate-200 text-slate-700'"
                                        class="p-3 rounded-xl border text-left cursor-pointer transition-all">
                                        <div class="font-bold">Ibu Hamil</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5" x-text="targetPatient?.no_telepon || '(Belum ada no)'"></div>
                                    </button>
                                    <button type="button" @click="waRecipient = 'suami'; prepareWaMessage()"
                                        :class="waRecipient === 'suami' ? 'bg-emerald-50 border-emerald-500 text-emerald-800 font-bold ring-2 ring-emerald-200' : 'bg-slate-50 border-slate-200 text-slate-700'"
                                        class="p-3 rounded-xl border text-left cursor-pointer transition-all">
                                        <div class="font-bold">Suami SIAGA (P4K)</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5" x-text="targetPatient?.nama_suami ? 'Tn. ' + targetPatient.nama_suami : '(Nama suami belum ada)'"></div>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp Tujuan:</label>
                                <input type="text" x-model="waPhone" placeholder="Contoh: 08123456789 atau 628123456789"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 font-mono">
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Pilih Template Pesan:</label>
                                <select x-model="waTemplateType" @change="prepareWaMessage()"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <template x-if="waRecipient === 'ibu'">
                                        <optgroup label="Template Ibu Hamil">
                                            <option value="anc_checkup">Pengingat Jadwal Kontrol ANC</option>
                                            <option value="h1_alert">Peringatan Siaga HPL / Menjelang Persalinan</option>
                                            <option value="kek_nutrition">Edukasi Gizi & Kepatuhan TTD (Bumil KEK)</option>
                                        </optgroup>
                                    </template>
                                    <template x-if="waRecipient === 'suami'">
                                        <optgroup label="Template Suami SIAGA">
                                            <option value="suami_p4k">Siaga Persalinan, Transportasi & Donor Darah</option>
                                        </optgroup>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Isi Pesan (Bisa diedit):</label>
                                <textarea rows="5" x-model="waMessage"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500"></textarea>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <button @click="showWhatsAppModal = false" type="button"
                                    class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                                <button type="button" @click="sendWhatsAppMessage()" :disabled="!waPhone"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-bold shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50">
                                    <span>Buka WhatsApp Web / App ↗</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: SKRINING DUPLIKASI DATA ================= -->
            <div x-show="showDuplicateModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showDuplicateModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Skrining Pasien Ganda / Lintas Wilayah</h3>
                                <p class="text-xs text-slate-500" x-text="'Pemeriksaan: ' + (targetPatient?.nama_lengkap || '')"></p>
                            </div>
                            <button @click="showDuplicateModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div x-show="isLoadingDuplicates" class="py-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-amber-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Memindai database untuk mendeteksi NIK & nama kembar...
                            </div>

                            <div x-show="!isLoadingDuplicates && duplicateResults.length === 0" class="p-6 text-center text-xs text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="font-bold text-sm">Data Bersih & Unik</div>
                                <p class="text-[11px] text-emerald-600 mt-1">Tidak ditemukan NIK ganda atau rekaman nama identik di fasyankes maupun kelurahan lain.</p>
                            </div>

                            <div x-show="!isLoadingDuplicates && duplicateResults.length > 0" class="space-y-2.5">
                                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <span>Terdeteksi <span class="font-bold underline" x-text="duplicateResults.length"></span> potensi data ganda / lintas wilayah:</span>
                                </div>

                                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                                    <template x-for="dup in duplicateResults" :key="dup.id">
                                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs flex items-center justify-between">
                                            <div>
                                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md" x-text="dup.type"></span>
                                                <div class="font-bold text-slate-800 text-sm mt-1" x-text="dup.patient.nama_lengkap"></div>
                                                <div class="text-slate-500 text-[11px]" x-text="dup.description"></div>
                                            </div>
                                            <a :href="`/anc/dashboard?search=${encodeURIComponent(dup.patient.nik || dup.patient.nama_lengkap)}`" target="_blank"
                                                class="px-2.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-lg border border-slate-200 shadow-xs text-[11px]">
                                                Lihat Data ↗
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 text-right">
                                <button @click="showDuplicateModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: REKAM JEJAK / COHORT TIMELINE ================= -->
            <div x-show="showTimelineModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showTimelineModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Rekam Jejak Pemeriksaan Kehamilan (Cohort Timeline)</h3>
                                <p class="text-xs text-slate-500" x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '') + ' | NIK: ' + (targetPatient?.nik || '-')"></p>
                            </div>
                            <button @click="showTimelineModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="mt-4">
                            <div x-show="isLoadingTimeline" class="py-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-indigo-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Memuat garis waktu riwayat pemeriksaan pasien...
                            </div>

                            <div x-show="!isLoadingTimeline" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                <div class="relative pl-6 border-l-2 border-indigo-200 space-y-6">
                                    <template x-for="(event, idx) in timelineData" :key="event.id">
                                        <div class="relative">
                                            <!-- Dot marker -->
                                            <div :class="event.is_current ? 'bg-pink-600 ring-4 ring-pink-100' : 'bg-indigo-600 ring-4 ring-indigo-50'"
                                                class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white shadow-xs"></div>
                                            
                                            <div :class="event.is_current ? 'border-pink-300 bg-pink-50/40' : 'border-slate-200 bg-white'"
                                                class="p-4 rounded-2xl border shadow-xs">
                                                <div class="flex items-center justify-between">
                                                    <div class="font-bold text-sm text-slate-800" x-text="event.title"></div>
                                                    <span class="text-xs font-semibold text-slate-500" x-text="event.date"></span>
                                                </div>
                                                <div class="text-xs text-indigo-700 font-medium mt-0.5" x-text="event.faskes"></div>

                                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 pt-3 border-t border-slate-100 text-xs">
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Usia Hamil</span>
                                                        <span class="font-semibold text-slate-700" x-text="event.gestational_age"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">LiLA / Hb</span>
                                                        <span class="font-semibold text-slate-700" x-text="event.lila + ' / ' + event.hb"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Skor Poedji</span>
                                                        <span class="font-bold text-rose-600" x-text="event.poedji_category + ' (' + event.poedji_score + ')'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Rekomendasi</span>
                                                        <span class="font-medium text-slate-700" x-text="event.rekomendasi"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-6 pt-3 border-t border-slate-100 text-right">
                                <button @click="showTimelineModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer">Tutup Garis Waktu</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: AI TRIAGE (GOOGLE GEMINI 2.5 FLASH) ================= -->
            <div x-show="showAiModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAiModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-xl bg-purple-100 text-purple-700 font-bold text-xs">AI</span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">AI Triage Medis & Deteksi Anomali</h3>
                                    <p class="text-xs text-slate-500">Analisis cerdas rekam medis berbasis Google Gemini 2.5 Flash</p>
                                </div>
                            </div>
                            <button @click="showAiModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="mt-4">
                            <div x-show="isLoadingAi" class="py-12 text-center text-xs text-purple-700 flex flex-col items-center justify-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-purple-100 flex items-center justify-center animate-pulse">
                                    <svg class="animate-spin h-5 w-5 text-purple-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-sm">Google Gemini sedang menganalisis rekam medis...</div>
                                    <p class="text-[11px] text-slate-500 mt-1">Memeriksa konsistensi HPHT/HPL, kesesuaian LiLA, komplikasi dan rekomendasi faskes.</p>
                                </div>
                            </div>

                            <div x-show="!isLoadingAi" class="space-y-3">
                                <div class="p-3 bg-purple-50/60 border border-purple-100 rounded-2xl text-xs flex items-center justify-between">
                                    <div class="font-bold text-slate-800" x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '-')"></div>
                                    <div class="text-purple-700 font-semibold" x-text="'Usia: ' + (targetPatient?.umur || '-') + ' th | ' + (targetPatient?.kelurahan || '-')"></div>
                                </div>

                                <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl max-h-96 overflow-y-auto text-xs text-slate-700 leading-relaxed font-sans prose prose-sm max-w-none whitespace-pre-wrap"
                                    x-html="formatAiContent(aiAnalysisResult)"></div>
                            </div>

                            <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400">Model: Gemini 2.5 Flash • Sumber: Data Register KIA</span>
                                <button @click="showAiModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer">Selesai Membaca</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: DETAIL / VIEW IBU HAMIL (ANC) ================= -->
            <div x-show="showViewModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showViewModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 max-h-[90vh] flex flex-col">
                        <!-- Header Modal -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-pink-100 text-pink-700 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Detail Rekam Pelayanan Ibu Hamil (ANC)</h3>
                                    <p class="text-xs text-slate-500">Informasi lengkap data kohort, pemeriksaan klinis, lab & skrining Poedji Rochjati</p>
                                </div>
                            </div>
                            <button @click="showViewModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Content Modal -->
                        <div class="mt-4 overflow-y-auto space-y-4 pr-1 text-xs">
                            <!-- Status & RISTI Banner -->
                            <div class="p-4 rounded-2xl bg-gradient-to-r from-pink-50 via-rose-50 to-amber-50 border border-pink-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-base font-bold text-slate-900" x-text="viewingPatient?.nama_lengkap || '-'"></span>
                                        <span class="text-xs text-slate-500" x-text="viewingPatient?.nama_suami ? '(Suami: ' + viewingPatient.nama_suami + ')' : ''"></span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-pink-100 text-pink-700 border border-pink-200"
                                            x-text="'Kunjungan ' + (viewingPatient?.kunjungan_ke || '-')"></span>
                                    </div>
                                    <div class="text-slate-500 text-[11px] mt-1" x-text="'NIK: ' + (viewingPatient?.nik || '-') + ' • Telp/WA: ' + (viewingPatient?.no_telepon || '-') + ' • BPJS: ' + (viewingPatient?.no_bpjs || '-')"></div>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Poedji Rochjati Badge -->
                                    <span :class="{
                                        'bg-emerald-100 text-emerald-800 border-emerald-300': viewingPatient?.kategori_poedji_rochjati === 'KRR',
                                        'bg-amber-100 text-amber-800 border-amber-300': viewingPatient?.kategori_poedji_rochjati === 'KRT',
                                        'bg-rose-100 text-rose-800 border-rose-300': viewingPatient?.kategori_poedji_rochjati === 'KRST',
                                        'bg-slate-100 text-slate-700 border-slate-200': !viewingPatient?.kategori_poedji_rochjati
                                    }" class="px-3 py-1 rounded-xl font-bold text-xs border shadow-2xs">
                                        <span x-text="(viewingPatient?.kategori_poedji_rochjati || 'KRR') + ' (Skor: ' + (viewingPatient?.skor_poedji_rochjati ?? 2) + ')'"></span>
                                    </span>

                                    <!-- Status RISTI Badge -->
                                    <span :class="viewingPatient?.status_risti && viewingPatient.status_risti.toLowerCase().includes('tinggi') ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white'"
                                        class="px-3 py-1 rounded-xl font-bold text-xs shadow-2xs"
                                        x-text="viewingPatient?.status_risti && viewingPatient.status_risti.toLowerCase().includes('tinggi') ? 'RISTI' : 'Normal'"></span>
                                </div>
                            </div>

                            <!-- Grid Sections -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                <!-- 1. Identitas & Demografi -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Identitas Pasien & Suami
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span class="text-slate-400">Umur:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.umur ? viewingPatient.umur + ' Tahun' : '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Tgl Lahir:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.tanggal_lahir || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Pekerjaan:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.pekerjaan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Pendidikan:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.pendidikan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Golongan Darah:</span><span class="font-bold text-rose-600" x-text="viewingPatient?.golongan_darah || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">No. Rekam Medis:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.no_rekam_medis || '-'"></span></div>
                                    </div>
                                </div>

                                <!-- 2. Riwayat Kehamilan (Obstetri) -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Riwayat Obstetri & Jadwal
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span class="text-slate-400">Gravida / Para / Abortus:</span><span class="font-bold text-indigo-700" x-text="'G' + (viewingPatient?.gravida ?? '-') + ' P' + (viewingPatient?.para ?? '-') + ' A' + (viewingPatient?.abortus ?? '-')"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Usia Kehamilan:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.usia_kehamilan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">HPHT:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.hpht || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">HPL (Taksiran Persalinan):</span><span class="font-bold text-rose-700" x-text="viewingPatient?.hpl || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Tgl Kunjungan Terakhir:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.tanggal_kunjungan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Status Kehamilan:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.status_kehamilan || 'Aktif'"></span></div>
                                    </div>
                                </div>

                                <!-- 3. Domisili & Fasyankes -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        Wilayah & Fasyankes
                                    </h4>
                                    <div class="space-y-1.5 text-[11px]">
                                        <div class="flex justify-between"><span class="text-slate-400">Kabupaten:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.kabupaten || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Kecamatan:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.kecamatan || '-'"></span></div>
                                        <div class="flex justify-between"><span class="text-slate-400">Kelurahan / Desa:</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.kelurahan || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Alamat Lengkap:</span><span class="font-medium text-slate-700 block mt-0.5" x-text="viewingPatient?.alamat_lengkap || '-'"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pemeriksaan Fisik & Laboratorium -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                <!-- Pemeriksaan Fisik (10 T) -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Pemeriksaan Fisik & Antropometri
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div><span class="text-slate-400 block">Berat & Tinggi Badan</span><span class="font-semibold text-slate-700" x-text="(viewingPatient?.berat_badan ? viewingPatient.berat_badan + ' kg' : '-') + ' / ' + (viewingPatient?.tinggi_badan ? viewingPatient.tinggi_badan + ' cm' : '-')"></span></div>
                                        <div>
                                            <span class="text-slate-400 block">Lingkar Lengan Atas (LiLA)</span>
                                            <span class="font-semibold text-slate-700" x-text="viewingPatient?.lila ? viewingPatient.lila + ' cm' : '-'"></span>
                                            <template x-if="viewingPatient?.lila && parseFloat(viewingPatient.lila) < 23.5">
                                                <span class="px-1.5 py-0.2 bg-amber-100 text-amber-800 border border-amber-300 rounded text-[9px] font-black inline-block mt-0.5">KEK (< 23.5 cm)</span>
                                            </template>
                                        </div>
                                        <div><span class="text-slate-400 block">Tekanan Darah</span><span class="font-semibold text-slate-700" x-text="(viewingPatient?.tekanan_darah_sistolik && viewingPatient?.tekanan_darah_diastolik) ? viewingPatient.tekanan_darah_sistolik + '/' + viewingPatient.tekanan_darah_diastolik + ' mmHg' : '-'"></span></div>
                                        <div><span class="text-slate-400 block">Tinggi Fundus Uteri (TFU)</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.tinggi_fundus_uteri ? viewingPatient.tinggi_fundus_uteri + ' cm' : '-'"></span></div>
                                        <div><span class="text-slate-400 block">Presentasi Janin</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.presentasi_janin || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Denyut Jantung Janin (DJJ)</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.denyut_jantung_janin ? viewingPatient.denyut_jantung_janin + ' x/menit' : '-'"></span></div>
                                        <div><span class="text-slate-400 block">Status Imunisasi TT</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.status_imunisasi_tt || '-'"></span></div>
                                        <div><span class="text-slate-400 block">Tablet Tambah Darah (Fe)</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.mendapat_fe ? 'Ya (Diberikan)' : 'Belum'"></span></div>
                                    </div>
                                </div>

                                <!-- Laboratorium & Skrining Triple Eliminasi -->
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                    <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                        Pemeriksaan Laboratorium & Triple Eliminasi
                                    </h4>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <div>
                                            <span class="text-slate-400 block">Hemoglobin (Hb)</span>
                                            <span class="font-bold text-slate-800" x-text="viewingPatient?.hb ? viewingPatient.hb + ' g/dL' : '-'"></span>
                                            <span class="text-[10px] block" :class="viewingPatient?.status_anemia && viewingPatient.status_anemia.toLowerCase().includes('anemia') ? 'text-rose-600 font-bold' : 'text-slate-500'" x-text="viewingPatient?.status_anemia || ''"></span>
                                        </div>
                                        <div><span class="text-slate-400 block">Gula Darah Sewaktu (GDS)</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.gds ? viewingPatient.gds + ' mg/dL' : '-'"></span></div>
                                        <div><span class="text-slate-400 block">Protein Urine</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.protein_urine || 'Negatif (-)'"></span></div>
                                        <div><span class="text-slate-400 block">HBsAg (Hepatitis B)</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.hbsag || 'Non-Reaktif'"></span></div>
                                        <div><span class="text-slate-400 block">HIV</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.hiv_status || 'Non-Reaktif'"></span></div>
                                        <div><span class="text-slate-400 block">Sifilis</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.sifilis_status || 'Non-Reaktif'"></span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rujukan & Kesiapan Persalinan (P4K) -->
                            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 space-y-2 text-xs">
                                <h4 class="font-bold text-amber-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Perencanaan Persalinan & Pencegahan Komplikasi (P4K) & Rujukan
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-[11px]">
                                    <div><span class="text-amber-800/70 block">Rekomendasi Faskes Rujukan:</span><span class="font-bold text-slate-800" x-text="viewingPatient?.rekomendasi_faskes || 'Bidan / BPM / Puskesmas'"></span></div>
                                    <div><span class="text-amber-800/70 block">Calon Pendonor Darah:</span><span class="font-bold text-rose-700" x-text="viewingPatient?.calon_pendonor || 'Belum Tercatat'"></span></div>
                                    <div><span class="text-amber-800/70 block">Faktor Risiko / Alasan:</span><span class="font-medium text-rose-700" x-text="viewingPatient?.faktor_risiko || viewingPatient?.alasan_rujukan || 'Tidak ada komplikasi mayor'"></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Modal -->
                        <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-2">
                                <button @click="showViewModal = false; openEditModal(viewingPatient)" class="px-3.5 py-2 bg-pink-50 hover:bg-pink-100 text-pink-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                                <button @click="showViewModal = false; openWhatsAppModal(viewingPatient)" class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    Kirim WhatsApp
                                </button>
                                <button @click="showViewModal = false; openAiTriage(viewingPatient)" class="px-3.5 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                    <span class="text-[10px] font-black px-1 py-0.5 bg-purple-200 text-purple-800 rounded">AI</span>
                                    AI Triage
                                </button>
                            </div>
                            <button @click="showViewModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer transition-colors">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed bottom-5 right-5 z-50 max-w-md w-full pointer-events-auto" style="display: none;">
                <div :class="{
                    'bg-slate-900 border-slate-700 text-white': toast.type === 'success',
                    'bg-rose-900 border-rose-700 text-white': toast.type === 'error',
                    'bg-amber-900 border-amber-700 text-white': toast.type === 'warning'
                }" class="p-4 rounded-2xl shadow-2xl border flex items-start gap-3 backdrop-blur-md">
                    <div class="shrink-0 mt-0.5">
                        <template x-if="toast.type === 'success'">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </template>
                    </div>

                    <div class="flex-1">
                        <h4 class="text-sm font-bold tracking-tight" x-text="toast.title"></h4>
                        <p class="text-xs text-slate-300 mt-0.5 leading-relaxed" x-text="toast.message"></p>
                    </div>

                    <button @click="toast.show = false" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- JAVASCRIPT & REALTIME CONTROLLER -->
    <script>
        // Non-reactive storage for Chart.js and Leaflet instances to prevent Alpine Proxy recursion
        const ancCharts = {
            kelurahan: null,
            monthly: null,
            age: null,
            poedji: null,
            map: null,
            markersLayer: null
        };

        function ancDashboard() {
            return {
                selectedKabupaten: '{{ $selectedKabupaten }}',
                selectedKelurahan: '{{ $selectedKelurahan }}',
                selectedBulan: '{{ $selectedBulan }}',
                searchQuery: '',
                kelurahanList: [],
                kpi: @json($kpi),
                kelurahanChartData: @json($kelurahan_chart),
                monthlyChartData: @json($monthly_chart),
                ageChartData: @json($age_chart),
                poedjiChartData: @json($poedji_chart),
                patientsData: @json($patients),
                mapData: @json($map_data),
                imminentDeliveries: @json($imminent_deliveries ?? []),
                sirineAudio: null,
                hasTriggeredInitialAlert: false,

                // Import Modal State
                showImportModal: false,
                isDragging: false,
                isParsing: false,
                isSubmittingImport: false,
                importPreview: null,

                // Toast Notification State
                toast: {
                    show: false,
                    type: 'success',
                    title: '',
                    message: '',
                    timeout: null
                },

                notify(type, title, message) {
                    if (this.toast.timeout) clearTimeout(this.toast.timeout);
                    this.toast.type = type;
                    this.toast.title = title;
                    this.toast.message = message;
                    this.toast.show = true;
                    this.toast.timeout = setTimeout(() => {
                        this.toast.show = false;
                    }, 4000);
                },

                // Edit & Add Modal State
                showEditModal: false,
                showAddModal: false,
                isSubmittingNewPatient: false,
                editingPatient: {},
                newPatient: {
                    nama_lengkap: '',
                    nik: '',
                    umur: '',
                    gravida: 1,
                    kunjungan_ke: 'K1',
                    hb: '',
                    status_risti: 'Normal',
                    status_anemia: 'Tidak Anemia',
                    kabupaten: 'Kab. Tangerang',
                    kelurahan: '',
                    alamat_lengkap: ''
                },

                // GIS Heatmap View Mode
                mapViewMode: 'markers', // 'markers' or 'heatmap'

                // Target Patient & Innovation Modal States
                targetPatient: null,
                showWhatsAppModal: false,
                waRecipient: 'ibu',
                waPhone: '',
                waTemplateType: 'anc_checkup',
                waMessage: '',

                showDuplicateModal: false,
                isLoadingDuplicates: false,
                duplicateResults: [],

                showTimelineModal: false,
                isLoadingTimeline: false,
                timelineData: [],

                showAiModal: false,
                isLoadingAi: false,
                aiAnalysisResult: '',

                // View / Detail Modal State
                showViewModal: false,
                viewingPatient: null,

                openViewModal(patient) {
                    this.viewingPatient = JSON.parse(JSON.stringify(patient));
                    this.showViewModal = true;
                },

                openWhatsAppModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.waRecipient = 'ibu';
                    this.waTemplateType = 'anc_checkup';
                    this.showWhatsAppModal = true;
                    this.prepareWaMessage();
                },

                prepareWaMessage() {
                    if (!this.targetPatient) return;
                    const p = this.targetPatient;
                    const hplStr = p.hpl ? String(p.hpl).substring(0, 10) : '-';
                    const kelStr = p.kelurahan || 'Puskesmas';

                    if (this.waRecipient === 'ibu') {
                        this.waPhone = p.no_telepon || '';
                        if (this.waTemplateType === 'h1_alert') {
                            this.waMessage = `Halo Ibu ${p.nama_lengkap || ''},\n\nPemberitahuan dari Tim Pelayanan KIA ${kelStr} (SICEPOT):\nBerdasarkan catatan rekam medis, Hari Perkiraan Lahir (HPL) Anda diperkirakan BESOK / Segera (${hplStr}).\n\nMohon pastikan:\n1. Buku KIA & berkas BPJS/KTP sudah siap.\n2. Tas persalinan ibu & bayi siap dibawa.\n3. Suami/Keluarga siaga mendampingi ke faskes rujukan persalinan.\n\nBila mengalami kontraksi teratur atau keluar cairan/flek, segera kunjungi fasyankes terdekat. Semoga persalinan lancar & sehat selalu!`;
                        } else if (this.waTemplateType === 'kek_nutrition') {
                            this.waMessage = `Halo Ibu ${p.nama_lengkap || ''},\n\nPengingat Kesehatan dari Puskesmas ${kelStr}:\nBerdasarkan hasil pengukuran LiLA Anda (${p.lila || '-'} cm), Anda memerlukan asupan nutrisi ekstra untuk mendukung tumbuh kembang janin yang optimal.\n\nMohon rutin mengonsumsi:\n- Makanan tinggi protein (telur, ikan, ayam, tahu, tempe)\n- Makanan Tambahan (PMT) dari puskesmas\n- Tablet Tambah Darah (TTD) 1 tablet setiap malam\n\nMari bersama wujudkan kehamilan sehat bebas risiko!`;
                        } else {
                            this.waMessage = `Halo Ibu ${p.nama_lengkap || ''},\n\nSalam hangat dari Petugas Kesehatan Puskesmas ${kelStr} (SICEPOT).\nMengingatkan untuk jadwal pemeriksaan kehamilan (${p.kunjungan_ke || 'rutin'}).\nPemeriksaan berkala sangat penting untuk memantau kesehatan Ibu dan janin dalam kandungan.\n\nSilakan datang ke Puskesmas/Posyandu terdekat membawa Buku KIA. Terima kasih!`;
                        }
                    } else {
                        // Suami SIAGA
                        this.waPhone = p.no_telepon || '';
                        const donor = p.calon_pendonor ? `Calon Pendonor: ${p.calon_pendonor}` : 'Mohon siapkan 2 calon pendonor darah keluarga';
                        this.waMessage = `Halo Bpk. ${p.nama_suami || 'Suami SIAGA'},\n\nSalam dari Tim P4K Puskesmas ${kelStr} (Program Perencanaan Persalinan & Pencegahan Komplikasi):\nIstri tercinta (${p.nama_lengkap || ''}) diperkirakan melahirkan sekitar tanggal ${hplStr}.\n\nSebagai Suami SIAGA, mohon pastikan:\n- Kendaraan / transportasi siaga menuju faskes.\n- Tabungan persalinan & berkas administrasi siap.\n- ${donor}.\n\nDukungan Bapak sangat berarti bagi keselamatan ibu dan buah hati. Terima kasih!`;
                    }
                },

                sendWhatsAppMessage() {
                    let clean = String(this.waPhone || '').replace(/\D+/g, '');
                    if (clean.startsWith('0')) clean = '62' + clean.substring(1);
                    else if (clean.startsWith('8')) clean = '62' + clean;
                    else if (!clean.startsWith('62')) clean = '62' + clean;

                    const url = `https://wa.me/${clean}?text=${encodeURIComponent(this.waMessage)}`;
                    window.open(url, '_blank');
                },

                async openDuplicateModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showDuplicateModal = true;
                    this.isLoadingDuplicates = true;
                    this.duplicateResults = [];

                    try {
                        const res = await fetch(`/anc/patients/${patient.id}/duplicates`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        this.duplicateResults = data.duplicates || [];
                    } catch (e) {
                        this.notify('error', 'Gagal', 'Terjadi kesalahan saat memindai duplikasi.');
                    } finally {
                        this.isLoadingDuplicates = false;
                    }
                },

                async openTimelineModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showTimelineModal = true;
                    this.isLoadingTimeline = true;
                    this.timelineData = [];

                    try {
                        const res = await fetch(`/anc/patients/${patient.id}/timeline`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        this.timelineData = data.timeline || [];
                    } catch (e) {
                        this.notify('error', 'Gagal', 'Gagal memuat rekam jejak pasien.');
                    } finally {
                        this.isLoadingTimeline = false;
                    }
                },

                async openAiTriage(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showAiModal = true;
                    this.isLoadingAi = true;
                    this.aiAnalysisResult = '';

                    try {
                        const res = await fetch(`/anc/patients/${patient.id}/ai-triage`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.aiAnalysisResult = data.content;
                        } else {
                            this.aiAnalysisResult = 'Gagal melakukan analisis AI: ' + (data.message || 'Koneksi API bermasalah.');
                        }
                    } catch (e) {
                        this.aiAnalysisResult = 'Kesalahan saat menghubungi server: ' + e.message;
                    } finally {
                        this.isLoadingAi = false;
                    }
                },

                formatAiContent(text) {
                    if (!text) return '';
                    // Basic Markdown-like bold formatting to HTML
                    return text
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/^### (.*$)/gim, '<h4 class="font-bold text-slate-800 text-sm mt-3 mb-1">$1</h4>')
                        .replace(/^## (.*$)/gim, '<h3 class="font-black text-slate-900 text-base mt-4 mb-1">$1</h3>');
                },

                initDashboard() {
                    this.loadKelurahanList();
                    this.$nextTick(() => {
                        this.renderCharts();
                        this.initMap();
                        // Otomatis cek dan beri notifikasi peringatan sirine jika ada ibu hamil H-1 persalinan
                        setTimeout(() => {
                            this.checkAndTriggerH1Alert();
                        }, 800);
                    });
                },

                openAddModal() {
                    this.newPatient = {
                        nama_lengkap: '',
                        nama_suami: '',
                        nik: '',
                        no_telepon: '',
                        tanggal_lahir: '',
                        umur: '',
                        gravida: 1,
                        kunjungan_ke: 'K1',
                        lila: '',
                        hb: '',
                        hpht: '',
                        hpl: '',
                        status_risti: 'Normal',
                        status_anemia: 'Tidak Anemia',
                        faktor_risiko: '',
                        calon_pendonor: '',
                        kabupaten: this.selectedKabupaten || 'Kab. Tangerang',
                        kelurahan: this.selectedKelurahan || '',
                        alamat_lengkap: ''
                    };
                    this.showAddModal = true;
                },

                async loadKelurahanList() {
                    try {
                        const res = await fetch(`{{ route('anc.kelurahan.list') }}?kabupaten=${encodeURIComponent(this.selectedKabupaten || '')}`);
                        this.kelurahanList = await res.json();
                    } catch (e) {
                        console.error('Error loading kelurahan:', e);
                    }
                },

                onKabupatenChange() {
                    this.selectedKelurahan = '';
                    this.loadKelurahanList();
                    this.applyFilters();
                },

                async applyFilters(page = 1) {
                    const params = new URLSearchParams({
                        kabupaten: this.selectedKabupaten || '',
                        kelurahan: this.selectedKelurahan || '',
                        bulan: this.selectedBulan || '',
                        search: this.searchQuery || '',
                        page: page
                    });

                    try {
                        const res = await fetch(`{{ route('anc.stats.json') }}?${params.toString()}`);
                        const data = await res.json();

                        this.kpi = data.kpi;
                        this.kelurahanChartData = data.kelurahan_chart;
                        this.monthlyChartData = data.monthly_chart;
                        this.ageChartData = data.age_chart;
                        if (data.poedji_chart) {
                            this.poedjiChartData = data.poedji_chart;
                        }
                        if (data.map_data) {
                            this.mapData = data.map_data;
                        }
                        this.patientsData = data.patients;
                        if (data.imminent_deliveries) {
                            this.imminentDeliveries = data.imminent_deliveries;
                        }

                        this.updateCharts();
                        this.updateMap();
                    } catch (e) {
                        console.error('Error applying filters:', e);
                    }
                },

                resetFilters() {
                    this.selectedKabupaten = '';
                    this.selectedKelurahan = '';
                    this.selectedBulan = '';
                    this.searchQuery = '';
                    this.loadKelurahanList();
                    this.applyFilters();
                },

                changePage(page) {
                    this.applyFilters(page);
                },

                renderCharts() {
                    // 1. Kelurahan Horizontal Bar Chart
                    const ctxKel = document.getElementById('ancKelurahanChart')?.getContext('2d');
                    if (ctxKel) {
                        if (ancCharts.kelurahan) {
                            try { ancCharts.kelurahan.destroy(); } catch (e) {}
                        }
                        ancCharts.kelurahan = new Chart(ctxKel, {
                            type: 'bar',
                            data: {
                                labels: this.kelurahanChartData.labels,
                                datasets: [{
                                    label: 'Jumlah Ibu Hamil',
                                    data: this.kelurahanChartData.values,
                                    backgroundColor: 'rgba(236, 72, 153, 0.85)',
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { color: '#f1f5f9' } },
                                    y: { grid: { display: false } }
                                }
                            }
                        });
                    }

                    // 2. Age Group Doughnut Chart
                    const ctxAge = document.getElementById('ancAgeChart')?.getContext('2d');
                    if (ctxAge) {
                        if (ancCharts.age) {
                            try { ancCharts.age.destroy(); } catch (e) {}
                        }
                        ancCharts.age = new Chart(ctxAge, {
                            type: 'doughnut',
                            data: {
                                labels: this.ageChartData.labels,
                                datasets: [{
                                    data: this.ageChartData.values,
                                    backgroundColor: ['#f59e0b', '#10b981', '#f43f5e'],
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                                }
                            }
                        });
                    }

                    // 3. Monthly Trend Line Chart
                    const ctxMonth = document.getElementById('ancMonthlyChart')?.getContext('2d');
                    if (ctxMonth) {
                        if (ancCharts.monthly) {
                            try { ancCharts.monthly.destroy(); } catch (e) {}
                        }
                        ancCharts.monthly = new Chart(ctxMonth, {
                            type: 'line',
                            data: {
                                labels: this.monthlyChartData.labels,
                                datasets: [{
                                    label: 'Registrasi Baru',
                                    data: this.monthlyChartData.values,
                                    borderColor: '#ec4899',
                                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
                                    fill: true,
                                    tension: 0.35,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#ec4899',
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }

                    // 4. Poedji Rochjati Donut Chart
                    const ctxPoedji = document.getElementById('poedjiChart')?.getContext('2d');
                    if (ctxPoedji) {
                        if (ancCharts.poedji) {
                            try { ancCharts.poedji.destroy(); } catch (e) {}
                        }
                        ancCharts.poedji = new Chart(ctxPoedji, {
                            type: 'doughnut',
                            data: {
                                labels: this.poedjiChartData.labels,
                                datasets: [{
                                    data: this.poedjiChartData.values,
                                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                                }
                            }
                        });
                    }
                },

                updateCharts() {
                    if (ancCharts.kelurahan) {
                        ancCharts.kelurahan.data.labels = this.kelurahanChartData.labels;
                        ancCharts.kelurahan.data.datasets[0].data = this.kelurahanChartData.values;
                        ancCharts.kelurahan.update();
                    }
                    if (ancCharts.age) {
                        ancCharts.age.data.labels = this.ageChartData.labels;
                        ancCharts.age.data.datasets[0].data = this.ageChartData.values;
                        ancCharts.age.update();
                    }
                    if (ancCharts.monthly) {
                        ancCharts.monthly.data.labels = this.monthlyChartData.labels;
                        ancCharts.monthly.data.datasets[0].data = this.monthlyChartData.values;
                        ancCharts.monthly.update();
                    }
                    if (ancCharts.poedji && this.poedjiChartData) {
                        ancCharts.poedji.data.labels = this.poedjiChartData.labels;
                        ancCharts.poedji.data.datasets[0].data = this.poedjiChartData.values;
                        ancCharts.poedji.update();
                    }
                },

                initMap() {
                    const init = () => {
                        const mapElem = document.getElementById('ancMap');
                        if (!mapElem) return;

                        if (typeof L === 'undefined') {
                            setTimeout(init, 100);
                            return;
                        }

                        if (ancCharts.map) {
                            try { ancCharts.map.remove(); } catch (e) {}
                            ancCharts.map = null;
                        }

                        // Default center: Kab. Tangerang / Pagedangan
                        ancCharts.map = L.map('ancMap').setView([-6.2889, 106.6092], 12);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                            maxZoom: 18
                        }).addTo(ancCharts.map);

                        ancCharts.markersLayer = L.layerGroup().addTo(ancCharts.map);
                        this.updateMap();

                        [100, 300, 600, 1000].forEach(delay => {
                            setTimeout(() => {
                                if (ancCharts.map) ancCharts.map.invalidateSize();
                            }, delay);
                        });
                    };

                    init();
                },

                updateMap() {
                    if (!ancCharts.map || !ancCharts.markersLayer || typeof L === 'undefined') return;

                    ancCharts.markersLayer.clearLayers();

                    if (!this.mapData || this.mapData.length === 0) return;

                    const bounds = [];

                    this.mapData.forEach(item => {
                        if (item.lat && item.lng) {
                            bounds.push([item.lat, item.lng]);

                            const hasRisti = (item.total_risti && item.total_risti > 0);
                            
                            if (this.mapViewMode === 'heatmap') {
                                // Heatmap Halo Buffer (Simulasi gradien panas risiko wilayah)
                                const heatIntensity = hasRisti ? 0.45 : 0.2;
                                const heatColor = hasRisti ? '#f43f5e' : '#f59e0b';
                                const heatRadius = Math.max(35, item.total * 6);

                                const heatCircle = L.circle([item.lat, item.lng], {
                                    radius: heatRadius * 15,
                                    color: 'transparent',
                                    fillColor: heatColor,
                                    fillOpacity: heatIntensity
                                });
                                ancCharts.markersLayer.addLayer(heatCircle);
                            }

                            const color = hasRisti ? '#f43f5e' : '#ec4899';
                            const radius = Math.min(22, Math.max(10, item.total * 3));

                            const circle = L.circleMarker([item.lat, item.lng], {
                                color: color,
                                fillColor: color,
                                fillOpacity: hasRisti ? 0.85 : 0.65,
                                radius: radius,
                                weight: 2
                            });

                            const popupContent = `
                                <div style="font-family: inherit; font-size: 12px; min-width: 170px;">
                                    <div style="font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 4px;">${item.kelurahan}</div>
                                    <div style="color: #64748b; margin-bottom: 6px;">${item.kabupaten || 'Wilayah Puskesmas'}</div>
                                    <div style="display: flex; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 4px;">
                                        <span style="color: #475569;">Total Ibu Hamil:</span>
                                        <span style="font-weight: 700; color: #ec4899;">${item.total}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding-top: 2px;">
                                        <span style="color: #475569;">Kasus RISTI / KEK:</span>
                                        <span style="font-weight: 700; color: #f43f5e;">${item.total_risti || 0}</span>
                                    </div>
                                    ${this.mapViewMode === 'heatmap' ? `<div style="margin-top: 4px; padding: 2px 4px; background: #fff1f2; color: #be123c; border-radius: 4px; font-size: 10px; font-weight: bold; text-align: center;">Zona Prioritas Intervensi</div>` : ''}
                                </div>
                            `;

                            circle.bindPopup(popupContent);
                            ancCharts.markersLayer.addLayer(circle);
                        }
                    });

                    if (bounds.length > 0) {
                        ancCharts.map.fitBounds(bounds, { padding: [35, 35], maxZoom: 14 });
                    }
                },

                // File Upload & Preview Handler
                onFileSelected(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.processFile(file);
                    }
                    event.target.value = '';
                },

                onFileDrop(event) {
                    this.isDragging = false;
                    const files = event.dataTransfer.files;
                    if (files && files.length > 0) {
                        const file = files[0];
                        const ext = file.name.split('.').pop().toLowerCase();
                        if (!['xlsx', 'xls', 'csv', 'txt'].includes(ext)) {
                            this.notify('error', 'Format Tidak Didukung', 'Harap masukkan berkas .xlsx, .xls, atau .csv');
                            return;
                        }
                        this.processFile(file);
                    }
                },

                async processFile(file) {
                    const formData = new FormData();
                    formData.append('file', file);

                    this.isParsing = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.import.preview') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.importPreview = data.data;
                        } else {
                            this.notify('error', 'Gagal Membaca File', data.message || 'Gagal mem-parsing berkas');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Upload', 'Terjadi kesalahan saat mengunggah: ' + e.message);
                    } finally {
                        this.isParsing = false;
                    }
                },

                resetImport() {
                    this.importPreview = null;
                },

                closeImportModal() {
                    this.showImportModal = false;
                    this.importPreview = null;
                },

                async commitImport() {
                    if (!this.importPreview || !this.importPreview.temp_token) return;

                    this.isSubmittingImport = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.import.commit') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ temp_token: this.importPreview.temp_token })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.closeImportModal();
                            this.applyFilters();
                            this.loadKelurahanList();
                            this.notify('success', 'Import Berhasil', data.message);
                        } else {
                            this.notify('error', 'Gagal Import', data.message || 'Gagal mengimpor data');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Sistem', 'Terjadi kesalahan sistem saat menyimpan data import.');
                    } finally {
                        this.isSubmittingImport = false;
                    }
                },

                // Realtime Edit Patient
                openEditModal(patient) {
                    this.editingPatient = JSON.parse(JSON.stringify(patient));
                    this.showEditModal = true;
                },

                async saveEditPatient() {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`/anc/patients/${this.editingPatient.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.editingPatient)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showEditModal = false;
                            const idx = this.patientsData.data.findIndex(p => p.id === this.editingPatient.id);
                            if (idx !== -1) {
                                this.patientsData.data[idx] = data.data;
                            }
                            this.applyFilters(this.patientsData.current_page);
                            this.notify('success', 'Data Diperbarui', 'Rekam data ibu hamil berhasil diupdate secara realtime.');
                        } else {
                            this.notify('error', 'Gagal Simpan', data.message || 'Gagal memperbarui data');
                        }
                    } catch (e) {
                        this.notify('error', 'Koneksi Bermasalah', 'Kesalahan koneksi saat menyimpan perubahan.');
                    }
                },

                // Add Patient
                async saveNewPatient() {
                    if (this.isSubmittingNewPatient) return;
                    this.isSubmittingNewPatient = true;

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.patients.store') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.newPatient)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showAddModal = false;
                            this.applyFilters();
                            this.loadKelurahanList();
                            this.notify('success', 'Ibu Hamil Ditambahkan', 'Data ibu hamil berhasil disimpan ke database.');
                        } else {
                            this.notify('error', 'Gagal Menambah', data.message || 'Gagal menambah data ibu hamil');
                        }
                    } catch (e) {
                        this.notify('error', 'Gagal Menambah', 'Gagal menambah data: ' + e.message);
                    } finally {
                        this.isSubmittingNewPatient = false;
                    }
                },

                // Delete Patient
                async deletePatient(patient) {
                    if (!confirm(`Hapus rekam data ${patient.nama_lengkap}?`)) return;

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`/anc/patients/${patient.id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.applyFilters(this.patientsData.current_page);
                            this.notify('success', 'Data Dihapus', `Data ${patient.nama_lengkap} berhasil dihapus.`);
                        } else {
                            this.notify('error', 'Gagal Hapus', 'Gagal menghapus data.');
                        }
                    } catch (e) {
                        this.notify('error', 'Gagal Hapus', 'Terjadi kesalahan saat menghapus data.');
                    }
                },

                // Audio Sirine & Alert Persalinan H-1
                async playSirine() {
                    try {
                        if (!this.sirineAudio) {
                            this.sirineAudio = new Audio('/assets/sounds/sirine.mp3');
                            this.sirineAudio.loop = true;
                        }
                        this.sirineAudio.currentTime = 0;
                        await this.sirineAudio.play();
                    } catch (err) {
                        // Browser Autoplay Policy: play() rejected because user hasn't interacted with document yet
                        console.warn('Autoplay audio ditunda menunggu interaksi pengguna:', err.message);

                        // Pasang one-time listener saat pengguna pertama kali berinteraksi (klik/tekan tombol di halaman)
                        const unlockAudio = () => {
                            if (this.sirineAudio && Swal.isVisible()) {
                                this.sirineAudio.play().catch(e => console.warn('Play retry:', e));
                            }
                            window.removeEventListener('click', unlockAudio);
                            window.removeEventListener('keydown', unlockAudio);
                            window.removeEventListener('touchstart', unlockAudio);
                        };
                        window.addEventListener('click', unlockAudio, { once: true });
                        window.addEventListener('keydown', unlockAudio, { once: true });
                        window.addEventListener('touchstart', unlockAudio, { once: true });
                    }
                },

                stopSirine() {
                    if (this.sirineAudio) {
                        this.sirineAudio.pause();
                        this.sirineAudio.currentTime = 0;
                    }
                },

                testSirineAudio() {
                    this.playSirine();
                    Swal.fire({
                        title: 'Uji Coba Sirine Siaga',
                        html: `
                            <div class="text-left text-sm space-y-2 mt-2">
                                <p class="text-slate-600">Audio sirine siaga sedang berbunyi dari berkas: <br><code class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded text-xs">public/assets/sounds/sirine.mp3</code></p>
                                <p class="text-slate-500 text-xs">Sirine ini akan otomatis dibunyikan bersama pop-up SweetAlert saat sistem mendeteksi ada ibu hamil yang mendekati <strong>H-1 Hari Perkiraan Lahir (HPL)</strong>.</p>
                            </div>
                        `,
                        icon: 'warning',
                        confirmButtonText: 'Matikan Sirine',
                        confirmButtonColor: '#e11d48',
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-3xl shadow-2xl border border-rose-100',
                            confirmButton: 'rounded-xl font-bold px-6 py-2.5 shadow-md'
                        }
                    }).then(() => {
                        this.stopSirine();
                    });
                },

                isDueTomorrow(dateStr) {
                    if (!dateStr) return false;
                    const cleanDate = dateStr.substring(0, 10);
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    const tmrStr = tomorrow.toISOString().substring(0, 10);
                    return cleanDate === tmrStr;
                },

                checkAndTriggerH1Alert() {
                    if (!this.imminentDeliveries || this.imminentDeliveries.length === 0) return;
                    if (this.hasTriggeredInitialAlert) return;
                    this.hasTriggeredInitialAlert = true;

                    // Trigger alert modal
                    this.showH1ModalAlert();
                },

                showH1ModalAlert() {
                    const count = this.imminentDeliveries ? this.imminentDeliveries.length : 0;
                    if (count === 0) {
                        Swal.fire({
                            title: 'Tidak Ada Persalinan H-1',
                            text: 'Saat ini tidak ada data ibu hamil yang perkiraan lahirnya besok.',
                            icon: 'info',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#ec4899',
                            customClass: { popup: 'rounded-3xl' }
                        });
                        return;
                    }

                    // Putar audio sirine
                    this.playSirine();

                    // Generate patient rows HTML
                    let patientListHtml = '<div class="space-y-2 mt-3 max-h-60 overflow-y-auto pr-1 text-left">';
                    this.imminentDeliveries.forEach((p, idx) => {
                        const ristiBadge = p.status_risti && p.status_risti.toLowerCase().includes('tinggi')
                            ? '<span class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-bold rounded-full">RISTI</span>'
                            : '<span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-semibold rounded-full">Normal</span>';
                        
                        patientListHtml += `
                            <div class="p-3 bg-rose-50/80 border border-rose-200 rounded-2xl flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-slate-800 text-sm">${idx + 1}. ${p.nama_lengkap} (${p.umur || '-'} th)</div>
                                    <div class="text-slate-500 text-[11px] mt-0.5">NIK: ${p.nik || '-'} | Kel: ${p.kelurahan || '-'}, ${p.kabupaten || '-'}</div>
                                    <div class="text-rose-700 font-semibold text-[11px] mt-0.5">HPL: ${p.hpl ? p.hpl.substring(0, 10) : '-'} (Besok)</div>
                                </div>
                                <div class="text-right shrink-0 ml-2">
                                    ${ristiBadge}
                                </div>
                            </div>
                        `;
                    });
                    patientListHtml += '</div>';

                    Swal.fire({
                        title: `<span class="text-rose-600 flex items-center justify-center gap-2">
                            <span class="animate-ping inline-flex h-3 w-3 rounded-full bg-rose-500 opacity-75"></span>
                            PERINGATAN H-1 PERSALINAN!
                        </span>`,
                        html: `
                            <div class="text-sm text-slate-600">
                                <p class="font-bold text-slate-800">Ditemukan <span class="text-rose-600 font-extrabold text-base">${count} Ibu Hamil</span> dengan Hari Perkiraan Lahir (HPL) <u>BESOK</u>!</p>
                                <p class="text-xs text-slate-500 mt-1">Sirine siaga diaktifkan. Klik di mana saja pada layar jika browser Anda meminta interaksi suara untuk memutar sirine.</p>
                                ${patientListHtml}
                            </div>
                        `,
                        icon: 'warning',
                        confirmButtonText: 'Matikan Sirine & Siapkan Pertolongan',
                        confirmButtonColor: '#e11d48',
                        allowOutsideClick: false,
                        didOpen: () => {
                            // Coba jalankan kembali sirine saat modal SweetAlert telah terbuka
                            this.playSirine();
                        },
                        customClass: {
                            popup: 'rounded-3xl shadow-2xl border-2 border-rose-200 max-w-lg',
                            confirmButton: 'rounded-xl font-bold px-6 py-3 shadow-md'
                        }
                    }).then(() => {
                        this.stopSirine();
                    });
                }
            };
        }
    </script>
</x-app-layout>
