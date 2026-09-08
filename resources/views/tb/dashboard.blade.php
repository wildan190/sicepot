<x-app-layout>
    <div x-data="tbDashboard()" x-init="initDashboard()">
        <!-- PAGE HEADER -->
        <div class="bg-white border-b border-slate-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-slate-800 tracking-tight flex items-center gap-2.5">
                            <span
                                class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </span>
                            Dashboard Penanggulangan TBC
                        </h2>
                        <p class="text-xs md:text-sm text-slate-500 mt-1">Sistem Informasi Pengendalian & Monitoring
                            Pasien & Terduga TBC Berbasis Wilayah</p>
                    </div>
                    <div class="flex items-center flex-wrap gap-2">
                        <!-- Ekspor Excel -->
                        <a :href="'{{ route('tb.export.excel') }}?kabupaten=' + encodeURIComponent(selectedKabupaten || '') + '&kelurahan=' + encodeURIComponent(selectedKelurahan || '') + '&report_type=' + encodeURIComponent(selectedType || '') + '&search=' + encodeURIComponent(searchQuery || '')"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer" title="Ekspor Data ke Format Excel">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Ekspor</span>
                        </a>

                        <!-- Laporan Eksekutif SPM Dinkes -->
                        <a :href="'{{ route('tb.report.executive') }}?kabupaten=' + encodeURIComponent(selectedKabupaten || '') + '&kelurahan=' + encodeURIComponent(selectedKelurahan || '') + '&report_type=' + encodeURIComponent(selectedType || '')"
                            target="_blank" title="Cetak Ringkasan Eksekutif SPM Dinkes"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Laporan SPM</span>
                        </a>

                        <!-- Import Excel / CSV -->
                        <button @click="showImportModal = true" type="button" title="Import Data Excel atau CSV"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span>Import</span>
                        </button>

                        <!-- Tambah Data -->
                        <button @click="openAddModal()" type="button" title="Tambah Data Pasien Baru"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150 cursor-pointer">
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

                <!-- FILTER BAR -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 transition-all">
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 flex-1">
                            <!-- Filter Kabupaten -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kabupaten
                                    / Kota</label>
                                <div class="relative">
                                    <select x-model="selectedKabupaten" @change="onKabupatenChange()"
                                        class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="">Semua Kabupaten / Kota</option>
                                        @foreach($kabupatenList as $kab)
                                            <option value="{{ $kab }}">{{ $kab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Kelurahan -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kelurahan
                                    / Desa</label>
                                <div class="relative">
                                    <select x-model="selectedKelurahan" @change="applyFilters()"
                                        class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="">Semua Kelurahan / Desa</option>
                                        <template x-for="kel in kelurahanList" :key="kel">
                                            <option :value="kel" x-text="kel" :selected="kel === selectedKelurahan">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Tipe Register -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kategori
                                    Register</label>
                                <select x-model="selectedType" @change="applyFilters()"
                                    class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    <option value="">Semua Data (TB-03 & TB-06)</option>
                                    <option value="tb_03">TB-03 SO (Register Pasien TBC)</option>
                                    <option value="tb_06">TB-06 (Register Terduga TBC)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search & Reset Actions -->
                        <div class="flex items-center gap-2 pt-2 lg:pt-5 border-t lg:border-t-0 border-slate-100">
                            <div class="relative flex-1 sm:w-64">
                                <input type="text" x-model="searchQuery" @keydown.enter.prevent="applyFilters()"
                                    placeholder="Cari NIK / Nama / No Reg..."
                                    class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
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
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <!-- Card 1: Total Semua -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Kasus</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-slate-900" x-text="kpi.total_all">0</span>
                            <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Terduga & Terkonfirmasi</span>
                    </div>

                    <!-- Card 2: Terduga TBC -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Terduga TBC</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-amber-700" x-text="kpi.total_terduga">0</span>
                            <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Register TB-06</span>
                    </div>

                    <!-- Card 3: Terkonfirmasi TBC -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Terkonfirmasi</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-rose-700" x-text="kpi.total_terkonfirmasi">0</span>
                            <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Positif Bakteriologis/Klinis</span>
                    </div>

                    <!-- Card 4: Pengobatan Aktif -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-indigo-700 uppercase tracking-wider">Aktif OAT</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-indigo-700"
                                x-text="kpi.total_sedang_pengobatan">0</span>
                            <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Sedang terapi OAT</span>
                    </div>

                    <!-- Card 5: Pasien Sembuh -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Sembuh /
                            Lengkap</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-emerald-700"
                                x-text="kpi.total_sembuh + kpi.total_lengkap">0</span>
                            <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2"
                            x-text="kpi.total_sembuh + ' Sembuh, ' + kpi.total_lengkap + ' Lengkap'">0 Sembuh</span>
                    </div>

                    <!-- Card 6: Putus Berobat -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Lost to Follow
                            Up</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-rose-700" x-text="kpi.total_putus">0</span>
                            <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Putus berobat</span>
                    </div>
                </div>

                <!-- VISUAL CHARTS SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart 1: Persebaran Kasus per Kelurahan (Bar Chart) -->
                    <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-base text-slate-800">Distribusi Kasus Terbanyak per Kelurahan
                                </h3>
                                <p class="text-xs text-slate-500">10 Kelurahan dengan kasus terdaftar tertinggi</p>
                            </div>
                        </div>
                        <div class="h-72">
                            <canvas id="kelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Demografi Usia & Gender (Doughnut) -->
                    <div
                        class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Demografi Gender & Usia</h3>
                            <p class="text-xs text-slate-500 mb-4">Perbandingan jenis kelamin pasien</p>
                            <div class="h-52 relative">
                                <canvas id="genderChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-slate-500 font-medium">Anak (<15)< /span>
                                        <span class="text-sm font-bold text-slate-800" x-text="kpi.total_anak">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-slate-500 font-medium">Produktif</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.total_produktif">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-slate-500 font-medium">Lansia (≥60)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.total_lansia">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Tren Bulanan (Line Chart) -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Tren Pendaftaran Kasus per Bulan</h3>
                            <p class="text-xs text-slate-500">Perkembangan jumlah kasus sepanjang periode berjalan</p>
                        </div>
                    </div>
                    <div class="h-60">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- INNOVATION: PETA GEOSPASIAL SEBARAN KASUS TBC (LEAFLET GIS) -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </span>
                            <div>
                                <h3 class="font-bold text-base text-slate-800">Peta Sebaran Kasus TBC Berbasis Geospasial (GIS)</h3>
                                <p class="text-xs text-slate-500">Visualisasi sebaran densitas kasus per kelurahan & wilayah kerja fasyankes</p>
                            </div>
                        </div>
                        <div class="flex items-center flex-wrap gap-2 text-xs">
                            <div class="inline-flex rounded-xl p-0.5 bg-slate-100 border border-slate-200">
                                <button @click="mapViewMode = 'markers'; updateMap()" type="button"
                                    :class="mapViewMode === 'markers' ? 'bg-white text-slate-800 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-2.5 py-1 rounded-lg transition-colors cursor-pointer text-[11px]">
                                    Titik Sebaran
                                </button>
                                <button @click="mapViewMode = 'geofence'; updateMap()" type="button"
                                    :class="mapViewMode === 'geofence' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-2.5 py-1 rounded-lg transition-colors cursor-pointer text-[11px] inline-flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full border border-current"></span>
                                    <span>Geofencing Kluster (500m)</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-3 text-xs ml-2">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                    <span class="text-slate-600 font-medium">≥ 10 Kasus (Hotspot)</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="text-slate-600 font-medium">4 - 9 Kasus</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                                    <span class="text-slate-600 font-medium">1 - 3 Kasus</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tbMap" style="height: 380px; width: 100%; min-height: 350px; isolation: isolate;" class="map-container w-full rounded-xl border border-slate-200 z-0 overflow-hidden shadow-inner relative"></div>
                </div>

                <!-- DATA TABLE SECTION -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
                    <div
                        class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Daftar Rekam Pasien & Terduga TBC</h3>
                            <p class="text-xs text-slate-500">Data dapat diedit secara langsung (real-time) melalui
                                tombol Edit pada setiap baris</p>
                        </div>
                        <span
                            class="text-xs font-medium px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg self-start sm:self-auto"
                            x-text="'Menampilkan halaman saat ini (' + (patientsData.data ? patientsData.data.length : 0) + ' dari ' + patientsData.total + ' total data)'"></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead
                                class="bg-slate-50/80 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200/80">
                                <tr>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3">Nama Lengkap & NIK</th>
                                    <th class="px-4 py-3">No. SITB / Terduga</th>
                                    <th class="px-4 py-3">L/P & Umur</th>
                                    <th class="px-4 py-3">Wilayah (Kab / Kel)</th>
                                    <th class="px-4 py-3">Hasil TCM / Diagnosis</th>
                                    <th class="px-4 py-3">Status / Hasil Akhir</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="p in (patientsData.data || [])" :key="p.id">
                                    <tr class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span
                                                :class="p.report_type === 'tb_03' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-amber-50 text-amber-700 border-amber-200'"
                                                class="px-2 py-0.5 rounded-md font-semibold text-[11px] border"
                                                x-text="p.report_type === 'tb_03' ? 'TB-03 SO' : 'TB-06'"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-800 text-sm"
                                                x-text="p.nama_lengkap || '-'"></div>
                                            <div class="text-slate-400 text-[11px]" x-text="'NIK: ' + (p.nik || '-')">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-slate-600">
                                            <div
                                                x-text="p.no_reg_sitb ? 'SITB: ' + p.no_reg_sitb : (p.no_reg_terduga ? 'Terduga: ' + p.no_reg_terduga : '-')">
                                            </div>
                                            <div class="text-[11px] text-slate-400"
                                                x-text="p.bulan ? 'Bulan: ' + p.bulan : ''"></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-slate-600">
                                            <span class="font-medium"
                                                x-text="(p.jenis_kelamin || '-') + ' / ' + (p.umur !== null ? p.umur + ' th' : '-')"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-slate-700" x-text="p.kelurahan || '-'"></div>
                                            <div class="text-[11px] text-slate-400" x-text="p.kabupaten || '-'"></div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-700"
                                                x-text="p.hasil_diagnosis || p.hasil_tcm || '-'"></div>
                                            <div class="text-[11px] text-slate-400" x-text="p.tipe_diagnosis || ''">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <template x-if="p.hasil_akhir_pengobatan">
                                                <span :class="{
                                                    'bg-emerald-50 text-emerald-700 border-emerald-200': p.hasil_akhir_pengobatan.includes('Sembuh') || p.hasil_akhir_pengobatan.includes('Lengkap'),
                                                    'bg-rose-50 text-rose-700 border-rose-200': p.hasil_akhir_pengobatan.includes('Putus') || p.hasil_akhir_pengobatan.includes('Meninggal') || p.hasil_akhir_pengobatan.includes('Gagal'),
                                                    'bg-slate-50 text-slate-700 border-slate-200': !p.hasil_akhir_pengobatan.includes('Sembuh') && !p.hasil_akhir_pengobatan.includes('Putus')
                                                }" class="px-2 py-1 rounded-lg text-[11px] font-semibold border"
                                                    x-text="p.hasil_akhir_pengobatan"></span>
                                            </template>
                                            <template x-if="!p.hasil_akhir_pengobatan">
                                                <span
                                                    class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[11px] font-medium"
                                                    x-text="p.report_type === 'tb_03' ? 'Dalam Pengobatan' : (p.status_pengobatan || 'Observasi')"></span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <!-- View / Detail Pasien Modal -->
                                                <button @click="openViewModal(p)" type="button"
                                                    class="p-1.5 text-sky-600 hover:text-sky-800 hover:bg-sky-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Lihat Rekam Data Lengkap Pasien">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>

                                                <!-- WhatsApp Direct Reminder -->
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
                                                    title="Skrining Data Ganda / Lintas Faskes">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Rekam Jejak Pengobatan (Treatment Timeline) -->
                                                <button @click="openTimelineModal(p)" type="button"
                                                    class="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Riwayat Perjalanan Pengobatan TBC">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>

                                                <!-- AI Triage (Google Gemini) -->
                                                <button @click="openAiTriage(p)" type="button"
                                                    class="p-1.5 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-colors cursor-pointer"
                                                    title="AI Triage & Evaluasi Pengobatan (Google Gemini)">
                                                    <span class="text-xs font-black px-1 py-0.5 bg-purple-100 text-purple-700 rounded">AI</span>
                                                </button>

                                                <!-- Edit Pasien -->
                                                <button @click="openEditModal(p)" type="button"
                                                    class="p-1.5 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Edit Data Realtime">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button @click="deletePatient(p)" type="button"
                                                    class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                    title="Hapus Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="!patientsData.data || patientsData.data.length === 0">
                                    <tr>
                                        <td colspan="8" class="text-center py-8 text-slate-400">
                                            Tidak ada data yang cocok dengan filter yang dipilih.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION CONTROLS -->
                    <div
                        class="px-5 py-3.5 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-600">
                        <span
                            x-text="'Halaman ' + (patientsData.current_page || 1) + ' dari ' + (patientsData.last_page || 1)"></span>
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

            <!-- ================= MODAL: IMPORT & PREVIEW EXCEL/CSV ================= -->
            <div x-show="showImportModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="closeImportModal()"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Import Data TBC (Universal Excel / CSV)
                                </h3>
                                <p class="text-xs text-slate-500">Mendukung format otomatis tanpa template kaku: laporan
                                    SITB TB-03, TB-06, maupun format kustom fasyankes lainnya.</p>
                            </div>
                            <button @click="closeImportModal()"
                                class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4">
                            <!-- Step 1: Upload Box with Full Drag & Drop Support -->
                            <div x-show="!importPreview" @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false" @drop.prevent="onFileDrop($event)"
                                :class="isDragging ? 'border-indigo-500 bg-indigo-100/50 scale-[1.01] ring-4 ring-indigo-200' : 'border-indigo-200 bg-indigo-50/20 hover:bg-indigo-50/40'"
                                class="border-2 border-dashed rounded-2xl p-10 text-center transition-all duration-200 cursor-pointer relative">
                                <input type="file" id="fileInput" @change="onFileSelected($event)"
                                    accept=".xlsx,.xls,.csv" class="hidden">
                                <label for="fileInput" class="cursor-pointer flex flex-col items-center">
                                    <div :class="isDragging ? 'bg-indigo-600 text-white scale-110' : 'bg-indigo-100 text-indigo-600'"
                                        class="w-16 h-16 rounded-2xl flex items-center justify-center mb-3.5 transition-all duration-200 shadow-xs">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-base font-bold text-slate-800"
                                        x-text="isDragging ? 'Lepaskan berkas di sini untuk mem-parsing' : 'Tarik & Letakkan (Drag & Drop) berkas di sini'"></span>
                                    <span class="text-xs text-slate-500 mt-1">atau <span
                                            class="text-indigo-600 font-semibold underline underline-offset-2 hover:text-indigo-800">klik
                                            untuk menjelajahi komputer</span></span>
                                    <span class="text-[11px] text-slate-400 mt-2">Mendukung berkas Excel (.xlsx, .xls) &
                                        CSV tanpa batasan format template</span>
                                </label>
                                <div x-show="isParsing"
                                    class="mt-4 flex items-center justify-center gap-2 text-indigo-600 text-xs font-semibold">
                                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Sedang membaca dan mem-parsing berkas secara universal...
                                </div>
                            </div>

                            <!-- Step 2: Preview Area -->
                            <div x-show="importPreview" class="space-y-4">
                                <div
                                    class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-0.5 bg-emerald-600 text-white rounded text-xs font-bold"
                                                x-text="importPreview?.type_label"></span>
                                            <span class="font-bold text-emerald-950 text-sm"
                                                x-text="importPreview?.original_name"></span>
                                        </div>
                                        <div class="text-xs text-emerald-700 mt-1"
                                            x-text="'Fasyankes: ' + (importPreview?.fasyankes_name || '-') + ' | Periode: ' + (importPreview?.period || '-')">
                                        </div>
                                        <div
                                            class="text-[11px] text-emerald-600 mt-1 flex flex-wrap gap-1 items-center">
                                            <span class="font-semibold text-emerald-800">Kolom Terdeteksi:</span>
                                            <template x-for="field in (importPreview?.detected_fields || [])"
                                                :key="field">
                                                <span
                                                    class="px-1.5 py-0.2 bg-emerald-100 text-emerald-800 rounded font-mono text-[10px]"
                                                    x-text="field"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-slate-500">Total Baris Terdeteksi:</span>
                                        <div class="text-xl font-black text-emerald-800"
                                            x-text="(importPreview?.total_rows || 0) + ' Baris'"></div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Cuplikan
                                        Preview 10 Baris Pertama:</h4>
                                    <div class="overflow-x-auto max-h-60 border border-slate-200 rounded-xl">
                                        <table class="w-full text-left text-xs">
                                            <thead class="bg-slate-100 text-slate-700 font-semibold sticky top-0">
                                                <tr>
                                                    <th class="p-2.5">No</th>
                                                    <th class="p-2.5">Nama Lengkap</th>
                                                    <th class="p-2.5">NIK</th>
                                                    <th class="p-2.5">L/P</th>
                                                    <th class="p-2.5">Umur</th>
                                                    <th class="p-2.5">Kabupaten</th>
                                                    <th class="p-2.5">Kelurahan</th>
                                                    <th class="p-2.5">Diagnosis</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                <template x-for="(row, idx) in (importPreview?.preview_samples || [])"
                                                    :key="idx">
                                                    <tr class="hover:bg-slate-50">
                                                        <td class="p-2.5 text-slate-500" x-text="idx + 1"></td>
                                                        <td class="p-2.5 font-medium text-slate-800"
                                                            x-text="row.nama_lengkap || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.nik || '-'"></td>
                                                        <td class="p-2.5 text-slate-600"
                                                            x-text="row.jenis_kelamin || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.umur || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.kabupaten || '-'">
                                                        </td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.kelurahan || '-'">
                                                        </td>
                                                        <td class="p-2.5 text-slate-600"
                                                            x-text="row.hasil_diagnosis || row.hasil_tcm || '-'"></td>
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
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z">
                                            </path>
                                        </svg>
                                        Menyimpan Data...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: EDIT PASIEN REALTIME ================= -->
            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showEditModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Edit Data Pasien / Terduga (Real-time)
                                </h3>
                                <p class="text-xs text-slate-500">Perubahan data akan langsung terupdate di tabel &
                                    grafik</p>
                            </div>
                            <button @click="showEditModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="saveEditPatient()" class="mt-4 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" x-model="editingPatient.nama_lengkap" required
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">NIK</label>
                                    <input type="text" x-model="editingPatient.nik"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Umur (Tahun)</label>
                                    <input type="number" x-model="editingPatient.umur"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                                    <select x-model="editingPatient.jenis_kelamin"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kabupaten / Kota</label>
                                    <input type="text" x-model="editingPatient.kabupaten"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kecamatan</label>
                                    <input type="text" x-model="editingPatient.kecamatan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kelurahan / Desa</label>
                                    <input type="text" x-model="editingPatient.kelurahan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea rows="2" x-model="editingPatient.alamat_lengkap"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Hasil Diagnosis</label>
                                    <select x-model="editingPatient.hasil_diagnosis"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="Terkonfirmasi TBC">Terkonfirmasi TBC</option>
                                        <option value="TBC SO">TBC SO</option>
                                        <option value="Bukan TBC">Bukan TBC</option>
                                        <option value="Terduga">Terduga</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Hasil Akhir
                                        Pengobatan</label>
                                    <select x-model="editingPatient.hasil_akhir_pengobatan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">-- Masih Dalam Pengobatan --</option>
                                        <option value="Sembuh">Sembuh</option>
                                        <option value="Pengobatan lengkap">Pengobatan lengkap</option>
                                        <option value="Putus berobat (lost to follow up)">Putus berobat (lost to follow
                                            up)</option>
                                        <option value="Meninggal">Meninggal</option>
                                        <option value="Gagal">Gagal</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button @click="showEditModal = false" type="button"
                                    class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                                <button type="submit"
                                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold shadow-xs cursor-pointer">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL: TAMBAH PASIEN CEPAT ================= -->
            <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showAddModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Tambah Data Pasien / Terduga Baru</h3>
                                <p class="text-xs text-slate-500">Input data pasien langsung ke sistem</p>
                            </div>
                            <button @click="showAddModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="saveNewPatient()" class="mt-4 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" x-model="newPatient.nama_lengkap" required
                                        placeholder="Nama lengkap pasien"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kategori Register</label>
                                    <select x-model="newPatient.report_type"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="tb_03">TB-03 (Pasien Positif)</option>
                                        <option value="tb_06">TB-06 (Terduga)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">NIK</label>
                                    <input type="text" x-model="newPatient.nik" placeholder="16 digit NIK"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Umur (Tahun)</label>
                                    <input type="number" x-model="newPatient.umur" placeholder="Umur"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                                    <select x-model="newPatient.jenis_kelamin"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kabupaten / Kota</label>
                                    <input type="text" x-model="newPatient.kabupaten"
                                        placeholder="misal: Kab. Tangerang"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Kelurahan / Desa</label>
                                    <input type="text" x-model="newPatient.kelurahan" placeholder="misal: Pagedangan"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                <textarea rows="2" x-model="newPatient.alamat_lengkap"
                                    placeholder="Alamat domisili atau jalan/RT/RW"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Hasil Diagnosis</label>
                                <select x-model="newPatient.hasil_diagnosis"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                    <option value="Terkonfirmasi TBC">Terkonfirmasi TBC</option>
                                    <option value="TBC SO">TBC SO</option>
                                    <option value="Bukan TBC">Bukan TBC</option>
                                    <option value="Terduga">Terduga</option>
                                </select>
                            </div>

                            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button @click="showAddModal = false" type="button"
                                    class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                                <button type="submit" :disabled="isSubmittingNewPatient"
                                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-semibold shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50">
                                    <span x-show="!isSubmittingNewPatient">Simpan Pasien</span>
                                    <span x-show="isSubmittingNewPatient" class="flex items-center gap-2">
                                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z">
                                            </path>
                                        </svg>
                                        Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

                <!-- ================= MODAL: WHATSAPP DIRECT REMINDER (wa.me) ================= -->
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
                                        <h3 class="text-base font-bold text-slate-800">Kirim Pengingat WhatsApp Langsung</h3>
                                        <p class="text-xs text-slate-500">Integrasi pesan resmi SICEPOT via WhatsApp (wa.me)</p>
                                    </div>
                                </div>
                                <button @click="showWhatsAppModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="mt-4 space-y-4 text-xs">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Pasien TBC Terpilih:</label>
                                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                        <div class="font-bold text-slate-800" x-text="targetPatient?.nama_lengkap || '-'"></div>
                                        <div class="text-[11px] text-slate-500 mt-0.5" x-text="'Kelurahan: ' + (targetPatient?.kelurahan || '-') + ' | Diagnosis: ' + (targetPatient?.hasil_diagnosis || targetPatient?.hasil_tcm || '-')"></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp Pasien / Pengawas Minum Obat (PMO):</label>
                                    <input type="text" x-model="waPhone" placeholder="Contoh: 08123456789 atau 628123456789"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 font-mono">
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Pilih Template Pesan TBC:</label>
                                    <select x-model="waTemplateType" @change="prepareWaMessage()"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                        <option value="oat_daily">Pengingat Minum Obat Harian (Kepatuhan OAT)</option>
                                        <option value="sputum_eval">Jadwal Evaluasi Dahak Akhir Bulan Ke-2/5/6</option>
                                        <option value="dropout_warning">Peringatan Mangkir Berobat / Drop Out TBC (Kritis)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Isi Pesan (Bisa disesuaikan):</label>
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
                                    <h3 class="text-base font-bold text-slate-800">Skrining Pasien TBC Ganda / Lintas Faskes</h3>
                                    <p class="text-xs text-slate-500" x-text="'Pemeriksaan: ' + (targetPatient?.nama_lengkap || '')"></p>
                                </div>
                                <button @click="showDuplicateModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="mt-4 space-y-3">
                                <div x-show="isLoadingDuplicates" class="py-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-amber-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Memindai database untuk mendeteksi NIK ganda & nama kembar lintas faskes...
                                </div>

                                <div x-show="!isLoadingDuplicates && duplicateResults.length === 0" class="p-6 text-center text-xs text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200">
                                    <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-sm">Data Pasien Unik</div>
                                    <p class="text-[11px] text-emerald-600 mt-1">Tidak ditemukan NIK ganda atau rekaman nama identik di fasyankes/kelurahan lain.</p>
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
                                                <a :href="`/tb/dashboard?search=${encodeURIComponent(dup.patient.nik || dup.patient.nama_lengkap)}`" target="_blank"
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

                <!-- ================= MODAL: REKAM JEJAK / TREATMENT TIMELINE ================= -->
                <div x-show="showTimelineModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                    <div class="min-h-screen px-4 text-center flex items-center justify-center">
                        <div @click="showTimelineModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                        <div class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Riwayat Perjalanan Pengobatan TBC</h3>
                                    <p class="text-xs text-slate-500" x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '') + ' | NIK: ' + (targetPatient?.nik || '-')"></p>
                                </div>
                                <button @click="showTimelineModal = false" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="mt-4">
                                <div x-show="isLoadingTimeline" class="py-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-indigo-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Memuat kronologi pengobatan dan evaluasi laboratorium...
                                </div>

                                <div x-show="!isLoadingTimeline" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    <div class="relative pl-6 border-l-2 border-indigo-200 space-y-6">
                                        <template x-for="(event, idx) in timelineData" :key="event.id">
                                            <div class="relative">
                                                <!-- Dot marker -->
                                                <div :class="event.is_current ? 'bg-indigo-600 ring-4 ring-indigo-100' : 'bg-slate-400 ring-4 ring-slate-100'"
                                                    class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white shadow-xs"></div>
                                                
                                                <div :class="event.is_current ? 'border-indigo-300 bg-indigo-50/40' : 'border-slate-200 bg-white'"
                                                    class="p-4 rounded-2xl border shadow-xs">
                                                    <div class="flex items-center justify-between">
                                                        <div class="font-bold text-sm text-slate-800" x-text="event.title"></div>
                                                        <span class="text-xs font-semibold text-slate-500" x-text="event.date"></span>
                                                    </div>
                                                    <div class="text-xs text-indigo-700 font-medium mt-0.5" x-text="event.faskes"></div>

                                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 pt-3 border-t border-slate-100 text-xs">
                                                        <div>
                                                            <span class="text-slate-400 block text-[10px]">Tipe Laporan</span>
                                                            <span class="font-bold text-slate-700 uppercase" x-text="event.report_type"></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-slate-400 block text-[10px]">Tipe / Regimen</span>
                                                            <span class="font-semibold text-slate-700" x-text="event.regimen"></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-slate-400 block text-[10px]">Diagnosis TCM</span>
                                                            <span class="font-bold text-rose-600" x-text="event.diagnosis"></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-slate-400 block text-[10px]">Hasil Akhir</span>
                                                            <span class="font-medium text-slate-700" x-text="event.outcome"></span>
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
                                        <h3 class="text-base font-bold text-slate-800">AI Triage TBC & Evaluasi Pengobatan</h3>
                                        <p class="text-xs text-slate-500">Analisis rekam medis TBC berbasis Google Gemini 2.5 Flash</p>
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
                                        <div class="font-bold text-sm">Google Gemini sedang menganalisis rekam medis TBC...</div>
                                        <p class="text-[11px] text-slate-500 mt-1">Mengevaluasi kesesuaian regimen OAT, risiko resistensi obat (RO), kepatuhan, dan jadwal kontrol dahak.</p>
                                    </div>
                                </div>

                                <div x-show="!isLoadingAi" class="space-y-3">
                                    <div class="p-3 bg-purple-50/60 border border-purple-100 rounded-2xl text-xs flex items-center justify-between">
                                        <div class="font-bold text-slate-800" x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '-')"></div>
                                        <div class="text-purple-700 font-semibold" x-text="'Diagnosis: ' + (targetPatient?.hasil_diagnosis || targetPatient?.hasil_tcm || '-') + ' | ' + (targetPatient?.kelurahan || '-')"></div>
                                    </div>

                                    <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl max-h-96 overflow-y-auto text-xs text-slate-700 leading-relaxed font-sans prose prose-sm max-w-none whitespace-pre-wrap"
                                        x-html="formatAiContent(aiAnalysisResult)"></div>
                                </div>

                                <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-[11px] text-slate-400">Model: Gemini 2.5 Flash • Sumber: Data Register SITB</span>
                                    <button @click="showAiModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer">Selesai Membaca</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= MODAL: DETAIL / VIEW PASIEN TBC ================= -->
                <div x-show="showViewModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                    <div class="min-h-screen px-4 text-center flex items-center justify-center">
                        <div @click="showViewModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                        <div class="inline-block w-full max-w-3xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 max-h-[90vh] flex flex-col">
                            <!-- Header Modal -->
                            <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-800">Detail Rekam Pasien TBC</h3>
                                        <p class="text-xs text-slate-500">Informasi lengkap data register SITB TB-03 / TB-06</p>
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
                                <!-- Status Banner -->
                                <div class="p-4 rounded-2xl bg-gradient-to-r from-sky-50 via-blue-50 to-indigo-50 border border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-base font-bold text-slate-900" x-text="viewingPatient?.nama_lengkap || '-'"></span>
                                            <span :class="viewingPatient?.report_type === 'tb_03' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-amber-100 text-amber-700 border-amber-200'"
                                                class="px-2 py-0.5 rounded-full text-[10px] font-bold border"
                                                x-text="viewingPatient?.report_type === 'tb_03' ? 'Register TB-03 SO' : 'Register TB-06 Terduga'"></span>
                                        </div>
                                        <div class="text-slate-500 text-[11px] mt-0.5" x-text="'NIK: ' + (viewingPatient?.nik || '-') + ' • No. BPJS: ' + (viewingPatient?.no_bpjs || '-')"></div>
                                    </div>
                                    <div>
                                        <span :class="{
                                            'bg-emerald-100 text-emerald-800 border-emerald-300': viewingPatient?.hasil_akhir_pengobatan && (viewingPatient.hasil_akhir_pengobatan.includes('Sembuh') || viewingPatient.hasil_akhir_pengobatan.includes('Lengkap')),
                                            'bg-rose-100 text-rose-800 border-rose-300': viewingPatient?.hasil_akhir_pengobatan && (viewingPatient.hasil_akhir_pengobatan.includes('Putus') || viewingPatient.hasil_akhir_pengobatan.includes('Meninggal') || viewingPatient.hasil_akhir_pengobatan.includes('Gagal')),
                                            'bg-blue-100 text-blue-800 border-blue-300': !viewingPatient?.hasil_akhir_pengobatan
                                        }" class="px-3 py-1 rounded-xl font-bold text-xs border inline-flex items-center gap-1.5 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full" :class="viewingPatient?.hasil_akhir_pengobatan && viewingPatient.hasil_akhir_pengobatan.includes('Sembuh') ? 'bg-emerald-500' : 'bg-blue-500'"></span>
                                            <span x-text="viewingPatient?.hasil_akhir_pengobatan || viewingPatient?.status_pengobatan || (viewingPatient?.report_type === 'tb_03' ? 'Dalam Pengobatan' : 'Observasi')"></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Grid Details -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Profil & Demografi -->
                                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                        <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Identitas & Demografi
                                        </h4>
                                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                                            <div><span class="text-slate-400 block">Jenis Kelamin</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.jenis_kelamin === 'L' ? 'Laki-laki' : (viewingPatient?.jenis_kelamin === 'P' ? 'Perempuan' : '-')"></span></div>
                                            <div><span class="text-slate-400 block">Umur</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.umur ? viewingPatient.umur + ' Tahun' : '-'"></span></div>
                                            <div><span class="text-slate-400 block">Pekerjaan</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.pekerjaan || '-'"></span></div>
                                            <div><span class="text-slate-400 block">No. Rekam Medis</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.no_rekam_medis || '-'"></span></div>
                                        </div>
                                    </div>

                                    <!-- Registrasi & Fasyankes -->
                                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                        <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            Registrasi SITB & Fasyankes
                                        </h4>
                                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                                            <div><span class="text-slate-400 block">No. Reg SITB</span><span class="font-semibold text-indigo-700" x-text="viewingPatient?.no_reg_sitb || '-'"></span></div>
                                            <div><span class="text-slate-400 block">No. Reg Terduga</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.no_reg_terduga || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Fasyankes</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.fasyankes_name || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Bulan / Periode</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.bulan || '-'"></span></div>
                                        </div>
                                    </div>

                                    <!-- Wilayah Pasien -->
                                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                        <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Domisili & Wilayah Kerja
                                        </h4>
                                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                                            <div><span class="text-slate-400 block">Kabupaten / Kota</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.kabupaten || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Kecamatan</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.kecamatan || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Kelurahan / Desa</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.kelurahan || '-'"></span></div>
                                            <div class="col-span-2"><span class="text-slate-400 block">Alamat Lengkap</span><span class="font-medium text-slate-700" x-text="viewingPatient?.alamat_lengkap || '-'"></span></div>
                                        </div>
                                    </div>

                                    <!-- Klinis & Laboratorium -->
                                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                        <h4 class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                            Hasil Pemeriksaan & Diagnosis
                                        </h4>
                                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                                            <div><span class="text-slate-400 block">Hasil Diagnosis</span><span class="font-bold text-slate-800" x-text="viewingPatient?.hasil_diagnosis || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Tipe Diagnosis</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.tipe_diagnosis || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Hasil TCM</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.hasil_tcm || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Mikroskopis / BTA</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.hasil_mikroskopis || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Lokasi Anatomi</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.lokasi_anatomi || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Riwayat Pengobatan</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.riwayat_pengobatan || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Status HIV</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.status_hiv || '-'"></span></div>
                                            <div><span class="text-slate-400 block">Riwayat DM</span><span class="font-semibold text-slate-700" x-text="viewingPatient?.riwayat_dm || '-'"></span></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pengobatan Info -->
                                <div class="p-3.5 bg-blue-50/50 rounded-2xl border border-blue-100 flex flex-wrap items-center justify-between gap-3 text-[11px]">
                                    <div>
                                        <span class="text-slate-400 block">Tanggal Daftar</span>
                                        <span class="font-bold text-slate-700" x-text="formatDate(viewingPatient?.tanggal_daftar)"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block">Mulai Pengobatan OAT</span>
                                        <span class="font-bold text-blue-700" x-text="formatDate(viewingPatient?.tanggal_mulai_pengobatan)"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block">Hasil Akhir Pengobatan</span>
                                        <span class="font-bold text-slate-800" x-text="viewingPatient?.hasil_akhir_pengobatan || 'Masih Terapi Aktif'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Modal -->
                            <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between shrink-0">
                                <div class="flex items-center gap-2">
                                    <button @click="showViewModal = false; openEditModal(viewingPatient)" class="px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit Data
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

                <!-- ================= TOAST NOTIFICATION ================= -->
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
                        <!-- Icon -->
                        <div class="shrink-0 mt-0.5">
                            <template x-if="toast.type === 'success'">
                                <div
                                    class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </template>
                            <template x-if="toast.type === 'error'">
                                <div
                                    class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </template>
                            <template x-if="toast.type === 'warning'">
                                <div
                                    class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                            </template>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <h4 class="text-sm font-bold tracking-tight" x-text="toast.title"></h4>
                            <p class="text-xs text-slate-300 mt-0.5 leading-relaxed" x-text="toast.message"></p>
                        </div>

                        <!-- Close button -->
                        <button @click="toast.show = false"
                            class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- JAVASCRIPT & REALTIME CONTROLLER -->
        <script>
            // Non-reactive storage for Chart.js and Leaflet instances to prevent Alpine Proxy recursion
            const tbCharts = {
                kelurahan: null,
                monthly: null,
                gender: null,
                map: null,
                markersLayer: null
            };

            function tbDashboard() {
                return {
                    selectedKabupaten: '{{ $selectedKabupaten }}',
                    selectedKelurahan: '{{ $selectedKelurahan }}',
                    selectedType: '{{ $selectedType }}',
                    searchQuery: '',
                    kelurahanList: [],
                    kpi: @json($kpi),
                    kelurahanChartData: @json($kelurahan_chart),
                    monthlyChartData: @json($monthly_chart),
                    genderChartData: @json($gender_chart),
                    ageChartData: @json($age_chart),
                    patientsData: @json($patients),
                    mapData: @json($map_data),

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
                        report_type: 'tb_03',
                        nama_lengkap: '',
                        nik: '',
                        umur: '',
                        jenis_kelamin: 'L',
                        kabupaten: 'Kab. Tangerang',
                        kelurahan: '',
                        alamat_lengkap: '',
                        hasil_diagnosis: 'Terkonfirmasi TBC'
                    },

                    // GIS Geofencing View Mode
                    mapViewMode: 'markers', // 'markers' or 'geofence'

                    // Target Patient & Innovation Modal States
                    targetPatient: null,
                    showWhatsAppModal: false,
                    waPhone: '',
                    waTemplateType: 'oat_daily',
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

                    formatDate(dateVal) {
                        if (!dateVal) return '-';
                        const raw = String(dateVal).split('T')[0];
                        const parts = raw.split('-');
                        if (parts.length === 3) {
                            const months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            const day = parseInt(parts[2], 10);
                            const monthIdx = parseInt(parts[1], 10);
                            const year = parts[0];
                            if (monthIdx >= 1 && monthIdx <= 12 && !isNaN(day)) {
                                return `${day} ${months[monthIdx]} ${year}`;
                            }
                            return raw;
                        }
                        return raw;
                    },

                    openViewModal(patient) {
                        this.viewingPatient = JSON.parse(JSON.stringify(patient));
                        this.showViewModal = true;
                    },

                    initDashboard() {
                        this.loadKelurahanList();
                        this.$nextTick(() => {
                            this.renderCharts();
                            this.initMap();
                        });
                    },

                    openAddModal() {
                        this.newPatient = {
                            report_type: 'tb_03',
                            nama_lengkap: '',
                            nik: '',
                            umur: '',
                            jenis_kelamin: 'L',
                            kabupaten: this.selectedKabupaten || 'Kab. Tangerang',
                            kelurahan: this.selectedKelurahan || '',
                            alamat_lengkap: '',
                            hasil_diagnosis: 'Terkonfirmasi TBC'
                        };
                        this.showAddModal = true;
                    },

                    async loadKelurahanList() {
                        try {
                            const res = await fetch(`{{ route('tb.kelurahan.list') }}?kabupaten=${encodeURIComponent(this.selectedKabupaten || '')}`);
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
                            report_type: this.selectedType || '',
                            search: this.searchQuery || '',
                            page: page
                        });

                        try {
                            const res = await fetch(`{{ route('tb.stats.json') }}?${params.toString()}`);
                            const data = await res.json();

                            this.kpi = data.kpi;
                            this.kelurahanChartData = data.kelurahan_chart;
                            this.monthlyChartData = data.monthly_chart;
                            this.genderChartData = data.gender_chart;
                            this.ageChartData = data.age_chart;
                            this.patientsData = data.patients;
                            if (data.map_data) {
                                this.mapData = data.map_data;
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
                        this.selectedType = '';
                        this.searchQuery = '';
                        this.loadKelurahanList();
                        this.applyFilters();
                    },

                    changePage(page) {
                        this.applyFilters(page);
                    },

                    renderCharts() {
                        // 1. Kelurahan Horizontal Bar Chart
                        const ctxKel = document.getElementById('kelurahanChart')?.getContext('2d');
                        if (ctxKel) {
                            if (tbCharts.kelurahan) {
                                try { tbCharts.kelurahan.destroy(); } catch (e) {}
                            }
                            tbCharts.kelurahan = new Chart(ctxKel, {
                                type: 'bar',
                                data: {
                                    labels: this.kelurahanChartData.labels,
                                    datasets: [{
                                        label: 'Jumlah Kasus',
                                        data: this.kelurahanChartData.values,
                                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
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

                        // 2. Gender Doughnut Chart
                        const ctxGen = document.getElementById('genderChart')?.getContext('2d');
                        if (ctxGen) {
                            if (tbCharts.gender) {
                                try { tbCharts.gender.destroy(); } catch (e) {}
                            }
                            tbCharts.gender = new Chart(ctxGen, {
                                type: 'doughnut',
                                data: {
                                    labels: this.genderChartData.labels,
                                    datasets: [{
                                        data: this.genderChartData.values,
                                        backgroundColor: ['#6366f1', '#ec4899'],
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
                        const ctxMonth = document.getElementById('monthlyChart')?.getContext('2d');
                        if (ctxMonth) {
                            if (tbCharts.monthly) {
                                try { tbCharts.monthly.destroy(); } catch (e) {}
                            }
                            tbCharts.monthly = new Chart(ctxMonth, {
                                type: 'line',
                                data: {
                                    labels: this.monthlyChartData.labels,
                                    datasets: [{
                                        label: 'Kasus Baru',
                                        data: this.monthlyChartData.values,
                                        borderColor: '#0284c7',
                                        backgroundColor: 'rgba(2, 132, 199, 0.1)',
                                        fill: true,
                                        tension: 0.35,
                                        pointRadius: 4,
                                        pointBackgroundColor: '#0284c7',
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
                    },

                    updateCharts() {
                        if (tbCharts.kelurahan) {
                            tbCharts.kelurahan.data.labels = this.kelurahanChartData.labels;
                            tbCharts.kelurahan.data.datasets[0].data = this.kelurahanChartData.values;
                            tbCharts.kelurahan.update();
                        }
                        if (tbCharts.gender) {
                            tbCharts.gender.data.labels = this.genderChartData.labels;
                            tbCharts.gender.data.datasets[0].data = this.genderChartData.values;
                            tbCharts.gender.update();
                        }
                        if (tbCharts.monthly) {
                            tbCharts.monthly.data.labels = this.monthlyChartData.labels;
                            tbCharts.monthly.data.datasets[0].data = this.monthlyChartData.values;
                            tbCharts.monthly.update();
                        }
                    },

                    initMap() {
                        const init = () => {
                            const mapElem = document.getElementById('tbMap');
                            if (!mapElem) return;

                            if (typeof L === 'undefined') {
                                setTimeout(init, 100);
                                return;
                            }

                            if (tbCharts.map) {
                                try { tbCharts.map.remove(); } catch (e) {}
                                tbCharts.map = null;
                            }

                            // Pusatkan default ke Kab. Tangerang / Pagedangan
                            tbCharts.map = L.map('tbMap').setView([-6.2889, 106.6092], 12);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                                maxZoom: 18
                            }).addTo(tbCharts.map);

                            tbCharts.markersLayer = L.layerGroup().addTo(tbCharts.map);
                            this.updateMap();

                            [100, 300, 600, 1000].forEach(delay => {
                                setTimeout(() => {
                                    if (tbCharts.map) tbCharts.map.invalidateSize();
                                }, delay);
                            });
                        };

                        init();
                    },

                    updateMap() {
                        if (!tbCharts.map || !tbCharts.markersLayer || typeof L === 'undefined') return;

                        tbCharts.markersLayer.clearLayers();

                        if (!this.mapData || this.mapData.length === 0) return;

                        const bounds = [];

                        this.mapData.forEach(item => {
                            if (item.lat && item.lng) {
                                bounds.push([item.lat, item.lng]);

                                // Tentukan warna berdasarkan jumlah kasus
                                let color = '#6366f1'; // Indigo (rendah)
                                let radius = 10;
                                if (item.total >= 10) {
                                    color = '#f43f5e'; // Rose / Hotspot
                                    radius = 18;
                                } else if (item.total >= 4) {
                                    color = '#f59e0b'; // Amber / Sedang
                                    radius = 14;
                                }

                                // Mode 2: Geofencing Radius 500m Kluster Penularan TBC
                                if (this.mapViewMode === 'geofence') {
                                    const bufferRadius = item.total >= 10 ? 800 : 500;
                                    const bufferCircle = L.circle([item.lat, item.lng], {
                                        radius: bufferRadius,
                                        color: item.total >= 10 ? '#e11d48' : '#f59e0b',
                                        fillColor: item.total >= 10 ? '#f43f5e' : '#fbbf24',
                                        fillOpacity: item.total >= 10 ? 0.25 : 0.15,
                                        weight: 1.5,
                                        dashArray: '4, 6'
                                    });
                                    tbCharts.markersLayer.addLayer(bufferCircle);
                                }

                                const circle = L.circleMarker([item.lat, item.lng], {
                                    color: color,
                                    fillColor: color,
                                    fillOpacity: 0.8,
                                    radius: radius,
                                    weight: 2
                                });

                                const popupContent = `
                                    <div style="font-family: inherit; font-size: 12px; min-width: 170px;">
                                        <div style="font-weight: 700; font-size: 13px; color: #1e293b; margin-bottom: 4px;">${item.kelurahan}</div>
                                        <div style="color: #64748b; margin-bottom: 6px;">${item.kabupaten || 'Wilayah Fasyankes'}</div>
                                        <div style="display: flex; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 4px; font-weight: 600;">
                                            <span>Total Kasus TBC:</span>
                                            <span style="color: ${color};">${item.total}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; font-size: 11px; color: #475569; margin-top: 2px;">
                                            <span>Kasus Aktif:</span>
                                            <span style="font-weight: bold; color: #e11d48;">${item.total_active || 0}</span>
                                        </div>
                                        ${this.mapViewMode === 'geofence' ? `
                                        <div style="margin-top: 6px; padding: 4px 6px; background-color: #fff1f2; border-radius: 6px; font-size: 10px; color: #be123c; font-weight: 600;">
                                            [Geofence] Zona Penularan Kontak Erat (500m)
                                        </div>` : ''}
                                    </div>
                                `;

                                circle.bindPopup(popupContent);
                                tbCharts.markersLayer.addLayer(circle);
                            }
                        });

                        if (bounds.length > 0) {
                            tbCharts.map.fitBounds(bounds, { padding: [35, 35], maxZoom: 14 });
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
                            // Validate extension
                            const ext = file.name.split('.').pop().toLowerCase();
                            if (!['xlsx', 'xls', 'csv', 'txt'].includes(ext)) {
                                alert('Format berkas tidak didukung. Harap masukkan berkas .xlsx, .xls, atau .csv');
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
                            const res = await fetch(`{{ route('tb.import.preview') }}`, {
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
                                alert(data.message || 'Gagal mem-parsing berkas');
                            }
                        } catch (e) {
                            alert('Terjadi kesalahan saat mengunggah berkas: ' + e.message);
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
                            const res = await fetch(`{{ route('tb.import.commit') }}`, {
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
                                this.notify('error', 'Gagal Import', data.message || 'Gagal mengimpor data berkas');
                            }
                        } catch (e) {
                            this.notify('error', 'Kesalahan Sistem', 'Terjadi kesalahan sistem saat menyimpan import.');
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
                            const res = await fetch(`/tb/patients/${this.editingPatient.id}`, {
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
                                // Realtime table update
                                const idx = this.patientsData.data.findIndex(p => p.id === this.editingPatient.id);
                                if (idx !== -1) {
                                    this.patientsData.data[idx] = data.data;
                                }
                                // Refresh stats & charts to reflect edit
                                this.applyFilters(this.patientsData.current_page);
                                this.notify('success', 'Data Diperbarui', 'Rekam data pasien berhasil diupdate secara realtime.');
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
                            const res = await fetch(`{{ route('tb.patients.store') }}`, {
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
                                this.notify('success', 'Pasien Ditambahkan', 'Data pasien baru berhasil disimpan ke database.');
                            } else {
                                this.notify('error', 'Gagal Menambah', data.message || 'Gagal menambah pasien');
                            }
                        } catch (e) {
                            this.notify('error', 'Gagal Menambah', 'Gagal menambah pasien: ' + e.message);
                        } finally {
                            this.isSubmittingNewPatient = false;
                        }
                    },

                    // Delete Patient
                    async deletePatient(patient) {
                        if (!confirm(`Hapus rekam data ${patient.nama_lengkap}?`)) return;

                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`/tb/patients/${patient.id}`, {
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

                    // ================= INOVASI 1: WHATSAPP DIRECT (wa.me) =================
                    openWhatsAppModal(patient) {
                        if (!patient) return;
                        this.targetPatient = JSON.parse(JSON.stringify(patient));
                        this.waTemplateType = 'oat_daily';
                        this.showWhatsAppModal = true;
                        this.prepareWaMessage();
                    },

                    prepareWaMessage() {
                        if (!this.targetPatient) return;
                        const p = this.targetPatient;
                        const kelStr = p.kelurahan || 'Puskesmas';
                        const diagnosis = p.hasil_diagnosis || p.hasil_tcm || 'TBC';

                        this.waPhone = p.no_telepon || '';

                        if (this.waTemplateType === 'sputum_eval') {
                            this.waMessage = `Halo Bpk/Ibu ${p.nama_lengkap || ''},\n\nPemberitahuan dari Tim Penanggulangan TBC Puskesmas ${kelStr} (SICEPOT):\nMengingatkan bahwa sudah waktunya untuk pemeriksaan dahak ulang (evaluasi laboratorium akhir bulan ke-2 / ke-5).\n\nPemeriksaan dahak sangat krusial untuk memastikan kuman TBC telah berkurang/hilang dan efektivitas obat berjalan baik.\n\nMohon hadir ke laboratorium puskesmas pada hari kerja membawa pot dahak. Pelayanan gratis. Mari tuntaskan pengobatan hingga sembuh!`;
                        } else if (this.waTemplateType === 'dropout_warning') {
                            this.waMessage = `PERINGATAN KESEHATAN TBC (Puskesmas ${kelStr})\n\nKepada Bpk/Ibu ${p.nama_lengkap || ''},\nBerdasarkan data SITB/SICEPOT, Anda terindikasi terlambat/belum mengambil obat TBC (OAT) sesuai jadwal.\n\nPENTING:\nPutus minum obat TBC berisiko tinggi menyebabkan resistensi kuman (TB Kebal Obat / MDR-TB) yang jauh lebih berbahaya dan memerlukan pengobatan bertahun-tahun.\n\nHarap SEGERA datang ke Puskesmas ${kelStr} hari ini atau hubungi petugas kami untuk pendampingan. Kami siap membantu Anda sampai tuntas.`;
                        } else {
                            // Default: Pengingat Minum Obat Harian
                            this.waMessage = `Halo Bpk/Ibu ${p.nama_lengkap || ''},\n\nSalam sehat dari Petugas TBC Puskesmas ${kelStr} (SICEPOT).\nMengingatkan untuk tidak lupa meminum Obat Anti Tuberkulosis (OAT) hari ini secara teratur pada jam yang sama bersama Pengawas Minum Obat (PMO).\n\nKunci kesembuhan TBC adalah kedisiplinan minum obat tanpa terlewat satu hari pun. Tetap semangat menjalani pengobatan hingga tuntas!`;
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

                    // ================= INOVASI 2: SKRINING DUPLIKASI DATA =================
                    async openDuplicateModal(patient) {
                        if (!patient) return;
                        this.targetPatient = JSON.parse(JSON.stringify(patient));
                        this.showDuplicateModal = true;
                        this.isLoadingDuplicates = true;
                        this.duplicateResults = [];

                        try {
                            const res = await fetch(`/tb/patients/${patient.id}/duplicates`, {
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

                    // ================= INOVASI 2: REKAM JEJAK / TIMELINE =================
                    async openTimelineModal(patient) {
                        if (!patient) return;
                        this.targetPatient = JSON.parse(JSON.stringify(patient));
                        this.showTimelineModal = true;
                        this.isLoadingTimeline = true;
                        this.timelineData = [];

                        try {
                            const res = await fetch(`/tb/patients/${patient.id}/timeline`, {
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

                    // ================= INOVASI 5: AI TRIAGE (GEMINI 2.5 FLASH) =================
                    async openAiTriage(patient) {
                        if (!patient) return;
                        this.targetPatient = JSON.parse(JSON.stringify(patient));
                        this.showAiModal = true;
                        this.isLoadingAi = true;
                        this.aiAnalysisResult = '';

                        try {
                            const res = await fetch(`/tb/patients/${patient.id}/ai-triage`, {
                                headers: { 'Accept': 'application/json' }
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.aiAnalysisResult = data.content;
                            } else {
                                this.aiAnalysisResult = 'Gagal melakukan analisis AI: ' + (data.message || 'Koneksi API bermasalah.');
                            }
                        } catch (e) {
                            this.aiAnalysisResult = 'Terjadi kesalahan saat menghubungi layanan Google Gemini: ' + e.message;
                        } finally {
                            this.isLoadingAi = false;
                        }
                    },

                    formatAiContent(content) {
                        if (!content) return '';
                        let formatted = content
                            .replace(/### (.*?)\n/g, '<h4 class="font-bold text-sm text-slate-800 mt-3 mb-1">$1</h4>')
                            .replace(/## (.*?)\n/g, '<h3 class="font-bold text-base text-purple-900 mt-4 mb-2 pb-1 border-b border-purple-100">$1</h3>')
                            .replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-slate-900">$1</strong>')
                            .replace(/\* (.*?)\n/g, '<div class="flex items-start gap-1.5 my-1 ml-2"><span class="text-purple-600 font-bold">•</span><span>$1</span></div>')
                            .replace(/- (.*?)\n/g, '<div class="flex items-start gap-1.5 my-1 ml-2"><span class="text-purple-600 font-bold">•</span><span>$1</span></div>');
                        return formatted;
                    }
                };
            }
        </script>
</x-app-layout>