<x-app-layout>
    <div x-data="stuntingDashboard()" x-init="initDashboard()">

        {{-- PAGE HEADER --}}
        <div class="bg-white border-b border-slate-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-slate-800 tracking-tight flex items-center gap-2.5">
                            <span class="p-2 rounded-xl bg-green-50 text-green-600 border border-green-100 shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-6C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" />
                                </svg>
                            </span>
                            Dashboard Pemantauan Balita Stunting
                        </h2>
                        <p class="text-sm md:text-base text-slate-500 mt-1">GERCEP PENTING — Gerakan Cepat Percepatan
                            Intervensi Stunting</p>
                    </div>
                    <div class="flex items-center flex-wrap gap-2">
                        <!-- Ekspor Excel -->
                        <a :href="'{{ route('stunting.export.excel') }}?desa=' + encodeURIComponent(selectedDesa || '') + '&bulan=' + encodeURIComponent(selectedBulan || '') + '&tahun=' + encodeURIComponent(selectedTahun || '') + '&search=' + encodeURIComponent(searchQuery || '')"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer"
                            title="Ekspor Data ke Format Excel">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Ekspor</span>
                        </a>

                        <!-- Laporan Eksekutif SPM Stunting -->
                        <a :href="'{{ route('stunting.report.executive') }}?desa=' + encodeURIComponent(selectedDesa || '') + '&bulan=' + encodeURIComponent(selectedBulan || '') + '&tahun=' + encodeURIComponent(selectedTahun || '')"
                            target="_blank" title="Cetak Ringkasan Eksekutif SPM Dinkes Stunting"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Laporan SPM</span>
                        </a>

                        <!-- Clear Data Massive -->
                        <button @click="openClearMassiveModal()" type="button"
                            title="Kosongkan Semua Data Balita Stunting"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 active:bg-rose-200 text-rose-700 border border-rose-200/80 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0 text-rose-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            <span>Clear Data</span>
                        </button>

                        <!-- Import Excel / CSV -->
                        <button @click="showImportModal = true" type="button" title="Import Data Excel e-PPGBM"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span>Import</span>
                        </button>

                        <!-- Tambah Data -->
                        <button @click="openAddModal()" type="button" title="Tambah Data Balita Baru"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Tambah Data</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- FILTER BAR --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Desa / Kelurahan</label>
                        <select x-model="selectedDesa" @change="applyFilters()"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">Semua Desa</option>
                            @foreach ($desaList as $d)
                                <option value="{{ $d }}" @selected($selectedDesa === $d)>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[130px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Bulan</label>
                        <select x-model="selectedBulan" @change="applyFilters()"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">Semua Bulan</option>
                            @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $num => $name)
                                <option value="{{ $num }}" @selected($selectedBulan === $num)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[100px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tahun</label>
                        <select x-model="selectedTahun" @change="applyFilters()"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">Semua Tahun</option>
                            @foreach ($yearList as $yr)
                                <option value="{{ $yr }}" @selected($selectedTahun == $yr)>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari Nama / NIK</label>
                        <input x-model="searchQuery" @input.debounce.400ms="applyFilters()" type="text"
                            placeholder="Nama atau NIK..."
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    </div>
                    <button @click="clearFilters()"
                        class="flex-none px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                        Reset
                    </button>
                </div>
            </div>

            {{-- KPI CARDS: STATUS PERTUMBUHAN & STUNTING --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Balita Terdaftar --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 col-span-1">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Balita</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1"
                        x-text="stats.totalBalita ?? '{{ $totalBalita }}'"></p>
                    <p class="text-xs text-slate-400 mt-1" x-text="'(' + (stats.total ?? '{{ $total }}') + ' rekaman)'">
                    </p>
                </div>
                {{-- Kasus Stunting Aktif (Pendek + Sangat Pendek) --}}
                <div
                    class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl border border-red-100 shadow-xs p-4 col-span-1">
                    <p class="text-xs font-semibold text-red-500 uppercase tracking-wide">Kasus Stunting</p>
                    <p class="text-3xl font-bold text-red-600 mt-1"
                        x-text="stats.stuntingTotal ?? '{{ $stuntingTotal }}'"></p>
                    <p class="text-xs text-red-400 mt-1">Pendek + Sangat Pendek</p>
                </div>
                {{-- Sangat Pendek --}}
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl border border-red-200 shadow-xs p-4">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Sangat Pendek</p>
                    <p class="text-3xl font-bold text-red-700 mt-1"
                        x-text="stats.sangatPendekCount ?? '{{ $sangatPendekCount }}'"></p>
                    <p class="text-xs text-red-500 mt-1">TB/U: Sangat Pendek</p>
                </div>
                {{-- Pendek --}}
                <div
                    class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl border border-orange-100 shadow-xs p-4">
                    <p class="text-xs font-semibold text-orange-500 uppercase tracking-wide">Pendek</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1"
                        x-text="stats.pendekCount ?? '{{ $pendekCount }}'"></p>
                    <p class="text-xs text-orange-400 mt-1">TB/U: Pendek</p>
                </div>
            </div>

            {{-- SECONDARY KPI: INTERVENSI KLINIS & LAYANAN SPESIFIK (subset dari kasus stunting) --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3.5">
                {{-- Hemoglobin Test --}}
                <div
                    class="bg-white rounded-2xl border border-rose-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-rose-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wide">Test Hemoglobin</p>
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    </div>
                    <p class="text-2xl font-black text-rose-800 mt-1.5"
                        x-text="stats.hemoglobinCount ?? '{{ $hemoglobinCount }}'"></p>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                        <span>Skrining Anemia</span>
                        <span class="font-semibold text-rose-600"
                            x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.hemoglobinCount ?? {{ $hemoglobinCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                    </div>
                </div>

                {{-- Mantoux Test --}}
                <div
                    class="bg-white rounded-2xl border border-sky-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-sky-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-sky-700 uppercase tracking-wide">Test Mantoux</p>
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    </div>
                    <p class="text-2xl font-black text-sky-800 mt-1.5"
                        x-text="stats.mantouxCount ?? '{{ $mantouxCount }}'"></p>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                        <span>Skrining TBC Anak</span>
                        <span class="font-semibold text-sky-600"
                            x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.mantouxCount ?? {{ $mantouxCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                    </div>
                </div>

                {{-- Konsul Spesialis Anak --}}
                <div
                    class="bg-white rounded-2xl border border-indigo-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-indigo-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-indigo-700 uppercase tracking-wide">Konsul Sp.A</p>
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    </div>
                    <p class="text-2xl font-black text-indigo-800 mt-1.5"
                        x-text="stats.konsulSpaCount ?? '{{ $konsulSpaCount }}'"></p>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                        <span>Spesialis Anak</span>
                        <span class="font-semibold text-indigo-600"
                            x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.konsulSpaCount ?? {{ $konsulSpaCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                    </div>
                </div>

                {{-- Vitamin A --}}
                <!-- <div
                    class="bg-white rounded-2xl border border-amber-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-amber-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wide">Suplementasi Vit A</p>
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    </div>
                    <p class="text-2xl font-black text-amber-800 mt-1.5" x-text="stats.vitACount ?? '{{ $vitACount }}'">
                    </p>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                        <span>Kapsul Vitamin A</span>
                        <span class="font-semibold text-amber-600"
                            x-text="((stats.total || {{ $total }}) > 0 ? Math.round(((stats.vitACount ?? {{ $vitACount }}) / (stats.total || {{ $total }})) * 100) : 0) + '%'"></span>
                    </div>
                </div> -->

                {{-- Kelas Ibu Balita --}}
                <!-- <div
                    class="bg-white rounded-2xl border border-purple-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-purple-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-purple-700 uppercase tracking-wide">Kelas Ibu Balita</p>
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    </div>
                    <p class="text-2xl font-black text-purple-800 mt-1.5"
                        x-text="stats.kelasIbuCount ?? '{{ $kelasIbuCount }}'"></p>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                        <span>Edukasi Gizi</span>
                        <span class="font-semibold text-purple-600"
                            x-text="((stats.total || {{ $total }}) > 0 ? Math.round(((stats.kelasIbuCount ?? {{ $kelasIbuCount }}) / (stats.total || {{ $total }})) * 100) : 0) + '%'"></span>
                    </div>
                </div> -->

                {{-- Naik Berat Badan (N) - dari stunting --}}
                <div
                    class="bg-white rounded-2xl border border-emerald-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-emerald-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wide">Lolos (N)</p>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>
                    <p class="text-2xl font-black text-emerald-800 mt-1.5"
                        x-text="stats.naikBBYCount ?? '{{ $naikBBYCount }}'"></p>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                        <span>Tren Positif</span>
                        <span class="font-semibold text-emerald-600"
                            x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.naikBBYCount ?? {{ $naikBBYCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                    </div>
                </div>
            </div>

            {{-- CHARTS SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Donut Chart: Status TB/U --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Status TB/U (Tinggi Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="tbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Normal</span><span
                                class="font-semibold text-slate-700">{{ $normalCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>Pendek</span><span
                                class="font-semibold text-slate-700">{{ $pendekCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>Sangat
                                Pendek</span><span class="font-semibold text-slate-700">{{ $sangatPendekCount }}</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: Status BB/U --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Status BB/U (Berat Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="bbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>Gizi
                                Baik</span><span class="font-semibold text-slate-700">{{ $bbuGiziBaikCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>Gizi
                                Kurang</span><span class="font-semibold text-slate-700">{{ $bbuKurangCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>Gizi Sangat
                                Kurang</span><span
                                class="font-semibold text-slate-700">{{ $bbuSangatKurangCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span>Risiko
                                Lebih</span><span class="font-semibold text-slate-700">{{ $bbuRisikoLebihCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-purple-400 inline-block"></span>Gizi
                                Lebih/Obesitas</span><span
                                class="font-semibold text-slate-700">{{ $bbuLebihCount + $bbuObesitasCount }}</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: Jenis Kelamin --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Sebaran Jenis Kelamin</h3>
                    <div class="relative flex justify-center">
                        <canvas id="genderChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>Laki-laki</span><span
                                class="font-semibold text-slate-700">{{ $lakiLaki }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-pink-400 inline-block"></span>Perempuan</span><span
                                class="font-semibold text-slate-700">{{ $perempuan }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Monthly Trend Chart (Full Width) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700">Tren Kasus Stunting Bulanan</h3>
                        <p class="text-xs text-slate-400">Perkembangan total pengukuran dan kasus balita stunting per bulan</p>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            {{-- Per-Desa Bar Chart (Full Width) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700">Distribusi Stunting per Desa/Kelurahan</h3>
                        <p class="text-xs text-slate-400">Sebaran kategori TB/U per wilayah desa/kelurahan</p>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="desaChart"></canvas>
                </div>
            </div>

            {{-- DATA TABLE --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between px-5 py-4 border-b border-slate-100 gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700">Data Balita</h3>
                        <p class="text-xs text-slate-400">Daftar rekaman pengukuran balita stunting</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span>Per halaman:</span>
                            <select x-model="perPage" @change="changePerPage()"
                                class="text-xs border border-slate-200 rounded-lg px-2.5 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 font-medium cursor-pointer">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <span
                            class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg"
                            x-text="(patientsData.total || 0) + ' Total Balita'"></span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">No</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Nama</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">JK</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Tgl Lahir</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Desa</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Posyandu</th>
                                <th class="px-3 py-3 text-right text-slate-500 font-semibold">BB Lahir</th>
                                <th class="px-3 py-3 text-right text-slate-500 font-semibold">Berat</th>
                                <th class="px-3 py-3 text-right text-slate-500 font-semibold">Tinggi</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">TB/U</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">BB/U</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">BB/TB</th>
                                <th class="px-3 py-3 text-center text-slate-500 font-semibold">Lolos</th>
                                <th class="px-3 py-3 text-center text-rose-600 font-semibold">Test Hb</th>
                                <th class="px-3 py-3 text-center text-sky-600 font-semibold">Mantoux</th>
                                <th class="px-3 py-3 text-center text-indigo-600 font-semibold">Sp.A</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Tgl Ukur</th>
                                <th class="px-3 py-3 text-center text-slate-500 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="(p, i) in (patientsData.data || [])" :key="p.id">
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-3 py-2.5 text-slate-400"
                                        x-text="((patientsData.current_page || 1) - 1) * (patientsData.per_page || 15) + i + 1">
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-800" x-text="p.nama"></td>
                                    <td class="px-3 py-2.5 text-slate-600 font-medium"
                                        x-text="p.jenis_kelamin === 'L' ? '♂' : (p.jenis_kelamin === 'P' ? '♀' : '-')">
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-600" x-text="formatDate(p.tanggal_lahir)"></td>
                                    <td class="px-3 py-2.5 text-slate-600" x-text="p.desa || '-'"></td>
                                    <td class="px-3 py-2.5 text-slate-500" x-text="p.posyandu || '-'"></td>
                                    <td class="px-3 py-2.5 text-right text-slate-600"
                                        x-text="p.bb_lahir ? Number(p.bb_lahir).toFixed(2) + ' kg' : '-'"></td>
                                    <td class="px-3 py-2.5 text-right font-medium text-slate-700"
                                        x-text="p.berat ? Number(p.berat).toFixed(2) + ' kg' : '-'"></td>
                                    <td class="px-3 py-2.5 text-right font-medium text-slate-700"
                                        x-text="p.tinggi ? Number(p.tinggi).toFixed(1) + ' cm' : '-'"></td>
                                    <td class="px-3 py-2.5">
                                        <template x-if="p.tbu_kategori">
                                            <span
                                                class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                                :class="getTbuBadgeClass(p.tbu_kategori)"
                                                x-text="p.tbu_kategori"></span>
                                        </template>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <template x-if="p.bbu_kategori">
                                            <span
                                                class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                                :class="getBbuBadgeClass(p.bbu_kategori)"
                                                x-text="p.bbu_kategori"></span>
                                        </template>
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <template x-if="p.bbtb_kategori">
                                            <span
                                                class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                                :class="getBbtbBadgeClass(p.bbtb_kategori)"
                                                x-text="p.bbtb_kategori"></span>
                                        </template>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <template x-if="p.naik_berat_badan === 'N'">
                                            <span
                                                class="inline-block w-5 h-5 rounded-full bg-green-100 text-green-600 text-center leading-5 font-bold"
                                                title="Naik">↑</span>
                                        </template>
                                        <template x-if="p.naik_berat_badan === 'T'">
                                            <span
                                                class="inline-block w-5 h-5 rounded-full bg-red-100 text-red-600 text-center leading-5 font-bold"
                                                title="Turun">↓</span>
                                        </template>
                                        <template x-if="p.naik_berat_badan !== 'N' && p.naik_berat_badan !== 'T'">
                                            <span class="text-slate-400">-</span>
                                        </template>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold"
                                            :class="p.test_hemoglobin === 'Ya' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'text-slate-400'"
                                            x-text="p.test_hemoglobin || '-'"></span>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold"
                                            :class="p.test_mantoux === 'Ya' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'text-slate-400'"
                                            x-text="p.test_mantoux || '-'"></span>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold"
                                            :class="p.konsul_spa === 'Ya' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'text-slate-400'"
                                            x-text="p.konsul_spa || '-'"></span>
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-500 whitespace-nowrap"
                                        x-text="formatDate(p.tanggal_pengukuran)"></td>
                                    <td class="px-3 py-2.5 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <!-- View Detail Balita -->
                                            <button @click="openViewModal(p)" type="button"
                                                class="p-1.5 text-sky-600 hover:text-sky-800 hover:bg-sky-50 rounded-lg transition-colors cursor-pointer"
                                                title="Lihat Rekam Lengkap Balita">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <!-- Edit Balita -->
                                            <button @click="openEditModal(p)" type="button"
                                                class="p-1.5 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                                title="Edit Data Balita">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <!-- Hapus Balita -->
                                            <button @click="deletePatient(p)" type="button"
                                                class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                                title="Hapus Data Balita">
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
                                    <td colspan="15" class="px-5 py-10 text-center text-slate-400">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Tidak ada data yang cocok dengan filter yang dipilih.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION CONTROLS -->
                <div
                    class="px-5 py-3.5 bg-slate-50 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
                    <div>
                        <span
                            x-text="'Menampilkan data ' + (patientsData.from || 0) + ' - ' + (patientsData.to || 0) + ' dari ' + (patientsData.total || 0) + ' total data balita'"></span>
                        <span class="mx-2 text-slate-300">|</span>
                        <span
                            x-text="'Halaman ' + (patientsData.current_page || 1) + ' dari ' + (patientsData.last_page || 1)"></span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button :disabled="(patientsData.current_page || 1) <= 1 || isTableLoading"
                            @click="changePage(1)" title="Halaman Pertama"
                            class="px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors cursor-pointer font-medium">
                            «
                        </button>
                        <button :disabled="(patientsData.current_page || 1) <= 1 || isTableLoading"
                            @click="changePage((patientsData.current_page || 1) - 1)"
                            class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors cursor-pointer font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Sebelumnya</span>
                        </button>

                        <div class="px-3 py-1.5 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200"
                            x-text="patientsData.current_page || 1"></div>

                        <button
                            :disabled="(patientsData.current_page || 1) >= (patientsData.last_page || 1) || isTableLoading"
                            @click="changePage((patientsData.current_page || 1) + 1)"
                            class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors cursor-pointer font-medium flex items-center gap-1">
                            <span>Berikutnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <button
                            :disabled="(patientsData.current_page || 1) >= (patientsData.last_page || 1) || isTableLoading"
                            @click="changePage(patientsData.last_page || 1)" title="Halaman Terakhir"
                            class="px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors cursor-pointer font-medium">
                            »
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
        IMPORT MODAL
        ============================================================ --}}
        <div x-show="showImportModal" x-transition.opacity
            class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" style="display:none">
            <div @click.outside="showImportModal = false"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800">Import Data Excel Stunting</h3>
                    <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 transition"><svg
                            class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
                <p class="text-xs text-slate-500">Upload file Excel format BALITA STUNTING (*.xlsx). Data akan
                    di-preview sebelum disimpan.</p>

                {{-- File Picker --}}
                <div x-show="importStep === 'pick'" class="space-y-3">
                    <label
                        class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-8 cursor-pointer hover:border-green-400 transition-colors group">
                        <svg class="w-8 h-8 text-slate-300 group-hover:text-green-400 transition-colors mb-2"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span class="text-sm text-slate-500 group-hover:text-green-600 transition-colors"
                            x-text="importFileName || 'Pilih file Excel (.xlsx)'"></span>
                        <input id="stunting-import-file" type="file" accept=".xlsx,.xls" class="hidden"
                            @change="handleFileSelect($event)">
                    </label>
                    <button @click="previewImport()" :disabled="!importFile || importLoading"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!importLoading">Preview Data</span>
                        <span x-show="importLoading">Membaca file...</span>
                    </button>
                </div>

                {{-- Preview --}}
                <div x-show="importStep === 'preview'" class="space-y-3">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-3">
                        <p class="text-sm font-semibold text-green-700">✓ File berhasil dibaca</p>
                        <p class="text-xs text-green-600 mt-0.5">Total <strong
                                x-text="importPreview.total_rows"></strong> data ditemukan</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 max-h-48 overflow-y-auto text-xs space-y-1">
                        <template x-for="(row, idx) in (importPreview.preview_samples || [])" :key="idx">
                            <div class="flex gap-2 text-slate-600">
                                <span class="font-semibold" x-text="idx+1 + '.'"></span>
                                <span x-text="row.nama"></span>
                                <span class="text-slate-400" x-text="row.desa"></span>
                                <span class="ml-auto text-xs px-1.5 py-0.5 rounded-full"
                                    :class="['Pendek','Sangat Pendek'].includes(row.tbu_kategori) ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'"
                                    x-text="row.tbu_kategori || '-'"></span>
                            </div>
                        </template>
                    </div>
                    <div class="flex gap-2">
                        <button @click="importStep = 'pick'; importFile = null; importFileName = ''"
                            class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">←
                            Ganti File</button>
                        <button @click="commitImport()" :disabled="importLoading"
                            class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition disabled:opacity-50">
                            <span x-show="!importLoading">Simpan Semua</span>
                            <span x-show="importLoading">Menyimpan...</span>
                        </button>
                    </div>
                </div>

                {{-- Success --}}
                <div x-show="importStep === 'done'" class="text-center space-y-3 py-4">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-800" x-text="importSuccessMsg"></p>
                    <button @click="showImportModal = false; location.reload()"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition">
                        Tutup & Refresh
                    </button>
                </div>

                {{-- Error --}}
                <div x-show="importError" class="bg-red-50 border border-red-200 rounded-xl p-3">
                    <p class="text-xs text-red-600" x-text="importError"></p>
                </div>
            </div>
        </div>

        {{-- ============================================================
        CLEAR MASSIVE MODAL
        ============================================================ --}}
        <div x-show="showClearMassiveModal" x-cloak
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4"
            style="display:none">
            <div @click.outside="if(!isClearingMassive) showClearMassiveModal = false"
                class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 space-y-4 border border-rose-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Kosongkan Semua Data Balita Stunting</h3>
                            <p class="text-xs text-slate-500">Tindakan pembersihan data masal (Clear Massive)</p>
                        </div>
                    </div>
                    <button @click="if(!isClearingMassive) showClearMassiveModal = false"
                        class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="p-4 bg-rose-50/80 border border-rose-200 rounded-2xl text-rose-800 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-sm text-rose-900">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span>Peringatan Keamanan Kritis!</span>
                        </div>
                        <p class="leading-relaxed">
                            Anda akan menghapus <strong>seluruh data rekam balita stunting</strong> dari database.
                            Tindakan ini bersifat <strong>permanen</strong> dan data yang telah dihapus tidak dapat
                            dikembalikan lagi.
                        </p>
                        <div
                            class="pt-2 border-t border-rose-200/60 text-[11px] text-rose-700 flex items-center justify-between font-semibold">
                            <span>Total data balita saat ini:</span>
                            <span class="px-2 py-0.5 bg-rose-200 text-rose-900 rounded-md font-bold"
                                x-text="(stats.total ?? '{{ $total }}') + ' Data Balita'"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">
                            Ketik kata <span class="font-bold text-rose-600 tracking-wider">HAPUS</span> di bawah ini
                            untuk mengonfirmasi:
                        </label>
                        <input type="text" x-model="clearMassiveKeyword" placeholder="Ketik HAPUS"
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 font-semibold tracking-wider transition-colors">
                    </div>

                    <div x-show="clearMassiveError" class="bg-red-50 border border-red-200 rounded-xl p-3">
                        <p class="text-xs text-red-600" x-text="clearMassiveError"></p>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button @click="if(!isClearingMassive) showClearMassiveModal = false" type="button"
                        :disabled="isClearingMassive"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer disabled:opacity-50">
                        Batal
                    </button>
                    <button :disabled="clearMassiveKeyword.trim() !== 'HAPUS' || isClearingMassive"
                        @click="executeClearMassive()" type="button"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                        <svg x-show="!isClearingMassive" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        <span x-show="!isClearingMassive">Ya, Kosongkan Semua Data</span>
                        <span x-show="isClearingMassive" class="flex items-center gap-2">
                            <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                    fill="none"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>Sedang Menghapus...</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= MODAL: EDIT BALITA REALTIME ================= -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showEditModal = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div
                    class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Edit Rekam Data Balita</h3>
                            <p class="text-xs text-slate-500">Perubahan data akan langsung terupdate di tabel dan grafik
                            </p>
                        </div>
                        <button @click="showEditModal = false"
                            class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="saveEditPatient()"
                        class="mt-4 space-y-3.5 text-xs max-h-[75vh] overflow-y-auto pr-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap Balita *</label>
                                <input type="text" x-model="editingPatient.nama" required
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">NIK</label>
                                <input type="text" x-model="editingPatient.nik" maxlength="20"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nama Orang Tua</label>
                                <input type="text" x-model="editingPatient.nama_ortu"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                                <select x-model="editingPatient.jenis_kelamin"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="L">Laki-laki (L)</option>
                                    <option value="P">Perempuan (P)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                                <input type="date" x-model="editingPatient.tanggal_lahir"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Usia Saat Ukur (Bulan)</label>
                                <input type="number" x-model="editingPatient.usia_saat_ukur" min="0" max="72"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Desa / Kelurahan</label>
                                <input type="text" x-model="editingPatient.desa"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Posyandu</label>
                                <input type="text" x-model="editingPatient.posyandu"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Puskesmas</label>
                                <input type="text" x-model="editingPatient.puskesmas"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">BB Saat Ini (kg)</label>
                                <input type="number" step="0.01" x-model="editingPatient.berat"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tinggi Saat Ini (cm)</label>
                                <input type="number" step="0.1" x-model="editingPatient.tinggi"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">LiLA (cm)</label>
                                <input type="number" step="0.1" x-model="editingPatient.lila"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Lolos</label>
                                <select x-model="editingPatient.naik_berat_badan"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="">-</option>
                                    <option value="N">Naik (N)</option>
                                    <option value="T">Turun / Tidak (T)</option>
                                    <option value="Y">Pertama Kali (Y)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori TB/U</label>
                                <select x-model="editingPatient.tbu_kategori"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="Normal">Normal</option>
                                    <option value="Pendek">Pendek</option>
                                    <option value="Sangat Pendek">Sangat Pendek</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori BB/U</label>
                                <select x-model="editingPatient.bbu_kategori"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="Gizi Baik">Gizi Baik / Normal</option>
                                    <option value="Kurang">Gizi Kurang</option>
                                    <option value="Sangat Kurang">Gizi Sangat Kurang</option>
                                    <option value="Risiko Lebih">Risiko Lebih</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori BB/TB</label>
                                <select x-model="editingPatient.bbtb_kategori"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                    <option value="Gizi Baik">Gizi Baik</option>
                                    <option value="Gizi Kurang">Gizi Kurang</option>
                                    <option value="Gizi Buruk">Gizi Buruk</option>
                                    <option value="Berisiko Gizi Lebih">Berisiko Gizi Lebih</option>
                                    <option value="Gizi Lebih">Gizi Lebih</option>
                                    <option value="Obesitas">Obesitas</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button @click="showEditModal = false" type="button"
                                class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-xl font-semibold cursor-pointer">Batal</button>
                            <button type="submit"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-xs cursor-pointer">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= MODAL: TAMBAH BALITA (MANUAL / AI) ================= -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showAddModal = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div
                    class="inline-block w-full max-w-2xl text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 overflow-hidden">
                    {{-- Header --}}
                    <div class="px-6 pt-5 pb-4 border-b border-slate-100 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Tambah Data Balita Stunting</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Isi formulir manual atau gunakan bantuan AI e-PPGBM
                            </p>
                        </div>
                        <button @click="showAddModal = false"
                            class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Tab switcher --}}
                    <div class="flex border-b border-slate-100 bg-slate-50">
                        <button type="button" @click="addModalTab = 'manual'" :class="addModalTab === 'manual'
                                ? 'border-b-2 border-emerald-500 text-emerald-700 font-semibold bg-white'
                                : 'text-slate-500 hover:text-slate-700'"
                            class="flex-1 py-3 text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Formulir Manual
                        </button>
                        <button type="button" @click="addModalTab = 'ai'" :class="addModalTab === 'ai'
                                ? 'border-b-2 border-emerald-500 text-emerald-700 font-semibold bg-white'
                                : 'text-slate-500 hover:text-slate-700'"
                            class="flex-1 py-3 text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Bantu AI (Gemini)
                        </button>
                    </div>

                    <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                        {{-- ======= TAB: MANUAL FORM ======= --}}
                        <div x-show="addModalTab === 'manual'" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Nama Lengkap
                                        Balita <span class="text-rose-500">*</span></label>
                                    <input type="text" x-model="newPatient.nama" placeholder="Nama balita"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">NIK
                                        Balita</label>
                                    <input type="text" x-model="newPatient.nik" placeholder="16 digit NIK"
                                        maxlength="20"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Nama Orang
                                        Tua</label>
                                    <input type="text" x-model="newPatient.nama_ortu" placeholder="Nama ayah / ibu"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jenis
                                        Kelamin</label>
                                    <select x-model="newPatient.jenis_kelamin"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                        <option value="">— Pilih —</option>
                                        <option value="L">Laki-laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Tanggal
                                        Lahir</label>
                                    <input type="date" x-model="newPatient.tanggal_lahir"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Desa /
                                        Kelurahan</label>
                                    <input type="text" x-model="newPatient.desa" placeholder="Nama desa/kelurahan"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Posyandu</label>
                                    <input type="text" x-model="newPatient.posyandu" placeholder="Nama posyandu"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                            </div>

                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest pt-2">Pengukuran
                                Terkini</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Berat Badan
                                        (kg)</label>
                                    <input type="number" step="0.01" x-model="newPatient.berat"
                                        placeholder="Contoh: 9.5"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Tinggi Badan
                                        (cm)</label>
                                    <input type="number" step="0.1" x-model="newPatient.tinggi"
                                        placeholder="Contoh: 75.2"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">LiLA (cm)</label>
                                    <input type="number" step="0.1" x-model="newPatient.lila" placeholder="Contoh: 12.5"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kategori
                                        TB/U</label>
                                    <select x-model="newPatient.tbu_kategori"
                                        class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition-all">
                                        <option value="">— Pilih —</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Pendek">Pendek</option>
                                        <option value="Sangat Pendek">Sangat Pendek</option>
                                        <option value="Tinggi">Tinggi</option>
                                    </select>
                                </div>
                            </div>

                            <div x-show="aiAddError"
                                class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                x-text="aiAddError"></div>
                        </div>

                        {{-- ======= TAB: AI PROMPT ======= --}}
                        <div x-show="addModalTab === 'ai'" class="space-y-3">
                            <div
                                class="p-3.5 bg-emerald-50 border border-emerald-100 rounded-xl text-xs text-emerald-800 leading-relaxed">
                                Tulis deskripsi data balita secara bebas. Contoh:<br>
                                <span class="mt-1.5 block text-emerald-600 italic">"Balita bernama Muhammad Rizki,
                                    laki-laki lahir 12 Maret 2024, anak dari Bpk Ridwan. Warga Desa Cijantra Posyandu
                                    Melati. Berat badan saat ini 7.2 kg, tinggi 68 cm, LiLA 11.5 cm, status TB/U sangat
                                    pendek, BB/U kurang."</span>
                            </div>

                            {{-- Step indicator --}}
                            <div class="flex items-center gap-2 text-[11px]">
                                <span
                                    :class="aiAddStep === 'prompt' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-600'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">1</span>
                                <span
                                    :class="aiAddStep === 'prompt' ? 'font-semibold text-slate-700' : 'text-slate-400'">Tulis
                                    Deskripsi</span>
                                <span class="text-slate-300 mx-1">—</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-600'"
                                    class="w-5 h-5 rounded-full flex items-center justify-center font-bold shrink-0">2</span>
                                <span
                                    :class="aiAddStep === 'preview' ? 'font-semibold text-slate-700' : 'text-slate-400'">Periksa
                                    &amp; Simpan</span>
                            </div>

                            {{-- Step 1: Prompt input --}}
                            <div x-show="aiAddStep === 'prompt'" class="space-y-3">
                                <div>
                                    <textarea x-model="aiAddPrompt" rows="5"
                                        placeholder="Tulis deskripsi data balita stunting di sini..."
                                        class="w-full px-3.5 py-3 text-xs border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 resize-none leading-relaxed"></textarea>
                                    <p class="text-[11px] text-slate-400 mt-1"
                                        x-text="aiAddPrompt.length + ' karakter'"></p>
                                </div>
                                <div x-show="aiAddError"
                                    class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                    x-text="aiAddError"></div>
                            </div>

                            {{-- Step 2: Preview parsed fields --}}
                            <div x-show="aiAddStep === 'preview'" class="space-y-3">
                                <div
                                    class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-medium">
                                    AI berhasil mengekstrak data balita. Periksa hasil di bawah sebelum menyimpan.
                                </div>
                                <div
                                    class="max-h-72 overflow-y-auto border border-slate-200 rounded-xl divide-y divide-slate-100">
                                    <template
                                        x-for="[key, val] in Object.entries(newPatient).filter(([k,v]) => v !== null && v !== '' && v !== undefined)"
                                        :key="key">
                                        <div class="flex items-start gap-2 px-3.5 py-2.5 hover:bg-slate-50">
                                            <span class="text-[11px] font-semibold text-slate-500 w-44 shrink-0 pt-0.5"
                                                x-text="key.replace(/_/g,' ')"></span>
                                            <input type="text" :value="val"
                                                @change="newPatient[key] = $event.target.value"
                                                class="flex-1 text-xs text-slate-800 bg-transparent border-0 border-b border-slate-200 focus:border-emerald-500 focus:ring-0 px-0 py-0.5">
                                        </div>
                                    </template>
                                </div>
                                <div x-show="aiAddError"
                                    class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700"
                                    x-text="aiAddError"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer actions --}}
                    <div class="px-6 pb-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div>
                            <button x-show="addModalTab === 'ai' && aiAddStep === 'preview'"
                                @click="aiAddStep = 'prompt'; aiAddError = ''" type="button"
                                class="text-xs font-semibold text-slate-500 hover:text-slate-700 cursor-pointer">
                                Kembali ke Deskripsi
                            </button>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <button @click="showAddModal = false" type="button"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button x-show="addModalTab === 'manual'" @click="saveNewPatient()" type="button"
                                :disabled="isSubmittingNewPatient || !newPatient.nama"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                <span x-show="!isSubmittingNewPatient">Simpan Data</span>
                                <span x-show="isSubmittingNewPatient" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                            <button x-show="addModalTab === 'ai' && aiAddStep === 'prompt'"
                                @click="runStuntingAiParse()" type="button"
                                :disabled="aiAddLoading || aiAddPrompt.trim().length < 10"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                <span x-show="!aiAddLoading">Proses dengan AI</span>
                                <span x-show="aiAddLoading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                            <button x-show="addModalTab === 'ai' && aiAddStep === 'preview'" @click="saveNewPatient()"
                                type="button" :disabled="isSubmittingNewPatient"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2 cursor-pointer disabled:opacity-50 transition-colors">
                                <span x-show="!isSubmittingNewPatient">Simpan ke Database</span>
                                <span x-show="isSubmittingNewPatient" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= MODAL: DETAIL / VIEW BALITA ================= -->
        <div x-show="showViewModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-8">
                <div @click="showViewModal = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                <div
                    class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100 flex flex-col max-h-[90vh]">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Detail Rekam Balita</h3>
                                <p class="text-xs text-slate-500">Informasi lengkap pemantauan pertumbuhan e-PPGBM</p>
                            </div>
                        </div>
                        <button @click="showViewModal = false"
                            class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 overflow-y-auto space-y-4 pr-1 text-xs">
                        <div
                            class="p-4 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-green-50 border border-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-bold text-slate-900"
                                        x-text="viewingPatient?.nama || '-'"></span>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold border bg-emerald-100 text-emerald-800 border-emerald-200"
                                        x-text="viewingPatient?.jenis_kelamin === 'L' ? 'Laki-laki ♂' : 'Perempuan ♀'"></span>
                                </div>
                                <div class="text-slate-500 text-[11px] mt-0.5"
                                    x-text="'NIK: ' + (viewingPatient?.nik || '-') + ' • Ortu: ' + (viewingPatient?.nama_ortu || '-')">
                                </div>
                            </div>
                            <div>
                                <span
                                    class="px-3 py-1 rounded-xl font-bold text-xs border inline-flex items-center gap-1.5 shadow-2xs"
                                    :class="getTbuBadgeClass(viewingPatient?.tbu_kategori)">
                                    <span x-text="'Status TB/U: ' + (viewingPatient?.tbu_kategori || '-')"></span>
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                <h4
                                    class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                    Identitas & Wilayah
                                </h4>
                                <div class="grid grid-cols-2 gap-2 text-[11px]">
                                    <div><span class="text-slate-400 block">Tgl Lahir</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="formatDate(viewingPatient?.tanggal_lahir)"></span></div>
                                    <div><span class="text-slate-400 block">Usia Ukur</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="(viewingPatient?.usia_saat_ukur || '-') + ' Bulan'"></span></div>
                                    <div><span class="text-slate-400 block">Desa</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="viewingPatient?.desa || '-'"></span></div>
                                    <div><span class="text-slate-400 block">Posyandu</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="viewingPatient?.posyandu || '-'"></span></div>
                                    <div class="col-span-2"><span class="text-slate-400 block">Alamat</span><span
                                            class="font-medium text-slate-700"
                                            x-text="viewingPatient?.alamat || '-'"></span></div>
                                </div>
                            </div>

                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2.5">
                                <h4
                                    class="font-bold text-slate-800 flex items-center gap-2 border-b border-slate-200 pb-1.5 text-xs">
                                    Hasil Pengukuran Antropometri
                                </h4>
                                <div class="grid grid-cols-2 gap-2 text-[11px]">
                                    <div><span class="text-slate-400 block">Berat Badan</span><span
                                            class="font-bold text-slate-800"
                                            x-text="viewingPatient?.berat ? Number(viewingPatient.berat).toFixed(2) + ' kg' : '-'"></span>
                                    </div>
                                    <div><span class="text-slate-400 block">Tinggi Badan</span><span
                                            class="font-bold text-slate-800"
                                            x-text="viewingPatient?.tinggi ? Number(viewingPatient.tinggi).toFixed(1) + ' cm' : '-'"></span>
                                    </div>
                                    <div><span class="text-slate-400 block">LiLA</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="viewingPatient?.lila ? Number(viewingPatient.lila).toFixed(1) + ' cm' : '-'"></span>
                                    </div>
                                    <div><span class="text-slate-400 block">Tgl Pengukuran</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="formatDate(viewingPatient?.tanggal_pengukuran)"></span></div>
                                    <div><span class="text-slate-400 block">Kategori BB/U</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="viewingPatient?.bbu_kategori || '-'"></span></div>
                                    <div><span class="text-slate-400 block">Kategori BB/TB</span><span
                                            class="font-semibold text-slate-700"
                                            x-text="viewingPatient?.bbtb_kategori || '-'"></span></div>
                                </div>
                            </div>

                            <div
                                class="col-span-1 md:col-span-2 p-3.5 bg-gradient-to-br from-rose-50/50 via-sky-50/50 to-indigo-50/50 rounded-2xl border border-indigo-100 space-y-2.5">
                                <h4
                                    class="font-bold text-slate-800 flex items-center gap-2 border-b border-indigo-100 pb-1.5 text-xs">
                                    Skrining Klinis &amp; Layanan Intervensi Spesifik
                                </h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-[11px]">
                                    <div class="p-2 bg-white/80 rounded-xl border border-rose-100">
                                        <span class="text-rose-600 block font-semibold">Test Hemoglobin</span>
                                        <span class="font-bold text-slate-800"
                                            x-text="viewingPatient?.test_hemoglobin || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white/80 rounded-xl border border-sky-100">
                                        <span class="text-sky-600 block font-semibold">Test Mantoux</span>
                                        <span class="font-bold text-slate-800"
                                            x-text="viewingPatient?.test_mantoux || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white/80 rounded-xl border border-indigo-100">
                                        <span class="text-indigo-600 block font-semibold">Konsul Dokter Sp.A</span>
                                        <span class="font-bold text-slate-800"
                                            x-text="viewingPatient?.konsul_spa || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white/80 rounded-xl border border-amber-100">
                                        <span class="text-amber-600 block font-semibold">Suplementasi Vit A</span>
                                        <span class="font-bold text-slate-800"
                                            x-text="viewingPatient?.jml_vit_a ? viewingPatient.jml_vit_a + ' Kapsul' : '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white/80 rounded-xl border border-purple-100">
                                        <span class="text-purple-600 block font-semibold">Kelas Ibu Balita</span>
                                        <span class="font-bold text-slate-800"
                                            x-text="viewingPatient?.kelas_ibu || '-'"></span>
                                    </div>
                                    <div class="p-2 bg-white/80 rounded-xl border border-emerald-100">
                                        <span class="text-emerald-600 block font-semibold">Naik Berat Badan</span>
                                        <span class="font-bold text-slate-800"
                                            x-text="viewingPatient?.naik_berat_badan === 'N' ? 'Naik (N)' : (viewingPatient?.naik_berat_badan === 'T' ? 'Turun (T)' : (viewingPatient?.naik_berat_badan || '-'))"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between shrink-0">
                        <button @click="showViewModal = false; openEditModal(viewingPatient)"
                            class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold cursor-pointer flex items-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Data
                        </button>
                        <button @click="showViewModal = false"
                            class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer transition-colors">Tutup</button>
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
            class="fixed bottom-5 right-5 z-[99999] max-w-md w-full pointer-events-auto" style="display: none;">
            <div :class="{
                'bg-slate-900 border-slate-700 text-white': toast.type === 'success',
                'bg-rose-900 border-rose-700 text-white': toast.type === 'error',
                'bg-amber-900 border-amber-700 text-white': toast.type === 'warning'
            }" class="p-4 rounded-2xl shadow-2xl border flex items-start gap-3 backdrop-blur-md">
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
                        <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </template>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold tracking-tight" x-text="toast.title"></h4>
                    <p class="text-xs text-slate-300 mt-0.5 leading-relaxed" x-text="toast.message"></p>
                </div>
                <button @click="toast.show = false"
                    class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            function stuntingDashboard() {
                return {
                    selectedDesa: '{{ $selectedDesa }}',
                    selectedBulan: '{{ $selectedBulan }}',
                    selectedTahun: '{{ $selectedTahun }}',
                    searchQuery: '',
                    stats: {},

                    // Import modal
                    showImportModal: false,
                    importStep: 'pick',
                    importFile: null,
                    importFileName: '',
                    importLoading: false,
                    importPreview: {},
                    importTempToken: '',
                    importSuccessMsg: '',
                    importError: '',

                    // Clear massive modal
                    showClearMassiveModal: false,
                    clearMassiveKeyword: '',
                    isClearingMassive: false,
                    clearMassiveError: '',

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

                    // Add & Edit & View Modal State
                    showAddModal: false,
                    addModalTab: 'manual', // 'manual' | 'ai'
                    isSubmittingNewPatient: false,
                    newPatient: {},
                    aiAddStep: 'prompt',   // 'prompt' | 'preview'
                    aiAddPrompt: '',
                    aiAddLoading: false,
                    aiAddError: '',

                    showEditModal: false,
                    isSubmittingEditPatient: false,
                    editingPatient: {},

                    showViewModal: false,
                    viewingPatient: null,

                    // Paginated patient data
                    patientsData: @json($patients),
                    perPage: 15,
                    isTableLoading: false,

                    // Charts
                    tbuChart: null,
                    bbuChart: null,
                    genderChart: null,
                    desaChart: null,
                    monthlyChart: null,

                    initDashboard() {
                        this.renderCharts();
                    },

                    formatDate(val) {
                        if (!val) return '-';
                        const s = String(val).split('T')[0].split('-');
                        if (s.length === 3) return s[2] + '/' + s[1] + '/' + s[0];
                        return val;
                    },

                    getTbuBadgeClass(val) {
                        if (val === 'Sangat Pendek') return 'bg-red-100 text-red-700 border border-red-200';
                        if (val === 'Pendek') return 'bg-amber-100 text-amber-700 border border-amber-200';
                        if (val === 'Normal') return 'bg-green-100 text-green-700 border border-green-200';
                        if (val === 'Tinggi') return 'bg-blue-100 text-blue-700 border border-blue-200';
                        return 'bg-slate-100 text-slate-600 border border-slate-200';
                    },

                    getBbuBadgeClass(val) {
                        if (['Sangat Kurang'].includes(val)) return 'bg-red-100 text-red-700 border border-red-200';
                        if (['Kurang'].includes(val)) return 'bg-amber-100 text-amber-700 border border-amber-200';
                        if (['Normal', 'Gizi Baik'].includes(val)) return 'bg-green-100 text-green-700 border border-green-200';
                        if (['Risiko Lebih', 'Risiko Gizi Lebih'].includes(val)) return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                        if (['Lebih', 'Gizi Lebih', 'Obesitas'].includes(val)) return 'bg-purple-100 text-purple-700 border border-purple-200';
                        return 'bg-slate-100 text-slate-600 border border-slate-200';
                    },

                    getBbtbBadgeClass(val) {
                        if (['Gizi Kurang', 'Kurang'].includes(val)) return 'bg-amber-100 text-amber-700 border border-amber-200';
                        if (['Gizi Baik', 'Normal'].includes(val)) return 'bg-green-100 text-green-700 border border-green-200';
                        if (['Risiko Gizi Lebih'].includes(val)) return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                        if (['Gizi Lebih', 'Obesitas'].includes(val)) return 'bg-purple-100 text-purple-700 border border-purple-200';
                        return 'bg-slate-100 text-slate-600 border border-slate-200';
                    },

                    applyFilters(page = 1) {
                        this.isTableLoading = true;
                        const params = new URLSearchParams({
                            desa: this.selectedDesa,
                            bulan: this.selectedBulan,
                            tahun: this.selectedTahun,
                            search: this.searchQuery,
                            page: page,
                            per_page: this.perPage,
                        });
                        fetch(`{{ route('stunting.stats.json') }}?${params}`)
                            .then(r => r.json())
                            .then(data => {
                                this.stats = data;
                                if (data.patients) {
                                    this.patientsData = data.patients;
                                }
                                this.updateCharts(data);
                            })
                            .finally(() => {
                                this.isTableLoading = false;
                            });
                    },

                    changePage(page) {
                        if (page < 1 || (this.patientsData && page > this.patientsData.last_page)) return;
                        this.applyFilters(page);
                    },

                    changePerPage() {
                        this.applyFilters(1);
                    },

                    clearFilters() {
                        this.selectedDesa = '';
                        this.selectedBulan = '';
                        this.selectedTahun = '';
                        this.searchQuery = '';
                        this.applyFilters(1);
                    },

                    renderCharts() {
                        // TB/U Donut
                        this.tbuChart = new Chart(document.getElementById('tbuChart'), {
                            type: 'doughnut',
                            data: {
                                labels: ['Normal', 'Pendek', 'Sangat Pendek'],
                                datasets: [{
                                    data: [{{ $normalCount }}, {{ $pendekCount }}, {{ $sangatPendekCount }}],
                                    backgroundColor: ['#22c55e', '#fbbf24', '#ef4444'],
                                    borderWidth: 2,
                                    borderColor: '#fff',
                                }]
                            },
                            options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
                        });

                        // BB/U Donut
                        this.bbuChart = new Chart(document.getElementById('bbuChart'), {
                            type: 'doughnut',
                            data: {
                                labels: ['Gizi Baik', 'Gizi Kurang', 'Sangat Kurang', 'Risiko Lebih', 'Gizi Lebih/Obesitas'],
                                datasets: [{
                                    data: [{{ $bbuGiziBaikCount }}, {{ $bbuKurangCount }}, {{ $bbuSangatKurangCount }}, {{ $bbuRisikoLebihCount }}, {{ $bbuLebihCount + $bbuObesitasCount }}],
                                    backgroundColor: ['#10b981', '#fbbf24', '#ef4444', '#facc15', '#a78bfa'],
                                    borderWidth: 2,
                                    borderColor: '#fff',
                                }]
                            },
                            options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
                        });

                        // Gender Donut
                        this.genderChart = new Chart(document.getElementById('genderChart'), {
                            type: 'doughnut',
                            data: {
                                labels: ['Laki-laki', 'Perempuan'],
                                datasets: [{
                                    data: [{{ $lakiLaki }}, {{ $perempuan }}],
                                    backgroundColor: ['#3b82f6', '#f472b6'],
                                    borderWidth: 2,
                                    borderColor: '#fff',
                                }]
                            },
                            options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
                        });

                        // Per-desa bar chart
                        const desaData = @json($byDesa);
                        this.desaChart = new Chart(document.getElementById('desaChart'), {
                            type: 'bar',
                            data: {
                                labels: desaData.map(d => d.desa || '(kosong)'),
                                datasets: [
                                    {
                                        label: 'Normal',
                                        data: desaData.map(d => d.normal),
                                        backgroundColor: '#22c55e',
                                        borderRadius: 4,
                                    },
                                    {
                                        label: 'Pendek',
                                        data: desaData.map(d => d.pendek),
                                        backgroundColor: '#fbbf24',
                                        borderRadius: 4,
                                    },
                                    {
                                        label: 'Sangat Pendek',
                                        data: desaData.map(d => d.sangat_pendek),
                                        backgroundColor: '#ef4444',
                                        borderRadius: 4,
                                    },
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: { stacked: true, grid: { display: false } },
                                    y: { stacked: true, ticks: { stepSize: 1 } }
                                },
                                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
                            }
                        });

                        // Monthly Trend Line/Bar Chart
                        const monthlyData = @json($monthlyTrend ?? []);
                        const monthLabels = monthlyData.map(m => {
                            if (!m.bulan_label) return '-';
                            const parts = m.bulan_label.split('-');
                            if (parts.length === 2) {
                                const d = new Date(parts[0], parseInt(parts[1]) - 1, 1);
                                return d.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
                            }
                            return m.bulan_label;
                        });

                        const ctxMonthly = document.getElementById('monthlyChart');
                        if (ctxMonthly) {
                            this.monthlyChart = new Chart(ctxMonthly, {
                                type: 'line',
                                data: {
                                    labels: monthLabels,
                                    datasets: [
                                        {
                                            label: 'Total Pengukuran',
                                            data: monthlyData.map(m => m.total),
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            borderWidth: 2,
                                            tension: 0.3,
                                            fill: true,
                                            pointBackgroundColor: '#3b82f6',
                                            pointRadius: 4,
                                        },
                                        {
                                            label: 'Kasus Stunting',
                                            data: monthlyData.map(m => m.stunting),
                                            borderColor: '#ef4444',
                                            backgroundColor: 'rgba(239, 68, 68, 0.15)',
                                            borderWidth: 2,
                                            tension: 0.3,
                                            fill: true,
                                            pointBackgroundColor: '#ef4444',
                                            pointRadius: 4,
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: {
                                        mode: 'index',
                                        intersect: false,
                                    },
                                    scales: {
                                        x: { grid: { display: false } },
                                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                                    },
                                    plugins: {
                                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                                    }
                                }
                            });
                        }
                    },

                    updateCharts(data) {
                        if (this.tbuChart) {
                            this.tbuChart.data.datasets[0].data = [
                                data.normalCount, data.pendekCount, data.sangatPendekCount
                            ];
                            this.tbuChart.update();
                        }
                        if (this.bbuChart) {
                            this.bbuChart.data.datasets[0].data = [
                                data.bbuGiziBaikCount, data.bbuKurangCount, data.bbuSangatKurangCount,
                                data.bbuRisikoLebihCount, data.bbuLebihCount + data.bbuObesitasCount
                            ];
                            this.bbuChart.update();
                        }
                        if (this.genderChart) {
                            this.genderChart.data.datasets[0].data = [data.lakiLaki, data.perempuan];
                            this.genderChart.update();
                        }
                        if (this.desaChart && data.byDesa) {
                            this.desaChart.data.labels = data.byDesa.map(d => d.desa || '(kosong)');
                            this.desaChart.data.datasets[0].data = data.byDesa.map(d => d.normal);
                            this.desaChart.data.datasets[1].data = data.byDesa.map(d => d.pendek);
                            this.desaChart.data.datasets[2].data = data.byDesa.map(d => d.sangat_pendek);
                            this.desaChart.update();
                        }
                        if (this.monthlyChart && data.monthlyTrend) {
                            const newMonthLabels = data.monthlyTrend.map(m => {
                                if (!m.bulan_label) return '-';
                                const parts = m.bulan_label.split('-');
                                if (parts.length === 2) {
                                    const d = new Date(parts[0], parseInt(parts[1]) - 1, 1);
                                    return d.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
                                }
                                return m.bulan_label;
                            });
                            this.monthlyChart.data.labels = newMonthLabels;
                            this.monthlyChart.data.datasets[0].data = data.monthlyTrend.map(m => m.total);
                            this.monthlyChart.data.datasets[1].data = data.monthlyTrend.map(m => m.stunting);
                            this.monthlyChart.update();
                        }
                    },

                    // ── Import helpers ────────────────────────────────────────────────
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        this.importFile = file;
                        this.importFileName = file.name;
                        this.importError = '';
                    },

                    async previewImport() {
                        if (!this.importFile) return;
                        this.importLoading = true;
                        this.importError = '';
                        const fd = new FormData();
                        fd.append('file', this.importFile);
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        try {
                            const res = await fetch('{{ route("stunting.import.preview") }}', { method: 'POST', body: fd });
                            const json = await res.json();
                            if (json.success) {
                                this.importPreview = json.data;
                                this.importTempToken = json.data.temp_token;
                                this.importStep = 'preview';
                            } else {
                                this.importError = json.message || 'Gagal membaca file.';
                            }
                        } catch (e) {
                            this.importError = 'Terjadi kesalahan: ' + e.message;
                        } finally {
                            this.importLoading = false;
                        }
                    },

                    async commitImport() {
                        this.importLoading = true;
                        this.importError = '';
                        const fd = new FormData();
                        fd.append('temp_token', this.importTempToken);
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        try {
                            const res = await fetch('{{ route("stunting.import.commit") }}', { method: 'POST', body: fd });
                            const json = await res.json();
                            if (json.success) {
                                this.importSuccessMsg = json.message;
                                this.importStep = 'done';
                            } else {
                                this.importError = json.message || 'Gagal menyimpan data.';
                            }
                        } catch (e) {
                            this.importError = 'Terjadi kesalahan: ' + e.message;
                        } finally {
                            this.importLoading = false;
                        }
                    },

                    // ── Clear Massive helpers ──────────────────────────────────────────
                    openClearMassiveModal() {
                        this.clearMassiveKeyword = '';
                        this.clearMassiveError = '';
                        this.showClearMassiveModal = true;
                    },

                    async executeClearMassive() {
                        if (this.clearMassiveKeyword.trim() !== 'HAPUS' || this.isClearingMassive) return;
                        this.isClearingMassive = true;
                        this.clearMassiveError = '';
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').content;
                            const res = await fetch('{{ route("stunting.clear-massive") }}', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.showClearMassiveModal = false;
                                location.reload();
                            } else {
                                this.clearMassiveError = data.message || 'Gagal mengosongkan data.';
                            }
                        } catch (e) {
                            this.clearMassiveError = 'Terjadi kesalahan sistem: ' + e.message;
                        } finally {
                            this.isClearingMassive = false;
                        }
                    },

                    // ── Patient CRUD & Modal Helpers ───────────────────────────────────
                    openAddModal() {
                        this.addModalTab = 'manual';
                        this.aiAddStep = 'prompt';
                        this.aiAddPrompt = '';
                        this.aiAddError = '';
                        this.newPatient = {
                            nama: '',
                            nik: '',
                            jenis_kelamin: '',
                            tanggal_lahir: '',
                            nama_ortu: '',
                            desa: this.selectedDesa || '',
                            posyandu: '',
                            puskesmas: '',
                            berat: '',
                            tinggi: '',
                            lila: '',
                            tbu_kategori: 'Normal'
                        };
                        this.showAddModal = true;
                    },

                    async runStuntingAiParse() {
                        if (this.aiAddLoading || this.aiAddPrompt.trim().length < 10) return;
                        this.aiAddLoading = true;
                        this.aiAddError = '';
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`{{ route('stunting.ai.parse') }}`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                body: JSON.stringify({ prompt: this.aiAddPrompt })
                            });
                            const result = await res.json();
                            if (result.success && result.data) {
                                const clean = {};
                                Object.entries(result.data).forEach(([k, v]) => {
                                    if (v !== null && v !== '') clean[k] = v;
                                });
                                this.newPatient = clean;
                                this.aiAddStep = 'preview';
                            } else {
                                this.aiAddError = result.message || 'AI tidak dapat membaca format data balita ini.';
                            }
                        } catch (e) {
                            this.aiAddError = 'Terjadi kesalahan saat memproses dengan AI: ' + e.message;
                        } finally {
                            this.aiAddLoading = false;
                        }
                    },

                    async saveNewPatient() {
                        if (this.isSubmittingNewPatient) return;
                        this.isSubmittingNewPatient = true;
                        this.aiAddError = '';
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`{{ route('stunting.patients.store') }}`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                body: JSON.stringify(this.newPatient)
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.showAddModal = false;
                                this.applyFilters(1);
                                this.notify('success', 'Balita Ditambahkan', 'Data balita baru berhasil tersimpan.');
                            } else {
                                this.aiAddError = data.message || 'Gagal menyimpan data.';
                            }
                        } catch (e) {
                            this.aiAddError = 'Gagal menyimpan: ' + e.message;
                        } finally {
                            this.isSubmittingNewPatient = false;
                        }
                    },

                    openEditModal(p) {
                        this.editingPatient = JSON.parse(JSON.stringify(p));
                        // format dates for HTML input
                        if (this.editingPatient.tanggal_lahir) {
                            this.editingPatient.tanggal_lahir = String(this.editingPatient.tanggal_lahir).split('T')[0];
                        }
                        if (this.editingPatient.tanggal_pengukuran) {
                            this.editingPatient.tanggal_pengukuran = String(this.editingPatient.tanggal_pengukuran).split('T')[0];
                        }
                        this.showEditModal = true;
                    },

                    async saveEditPatient() {
                        if (this.isSubmittingEditPatient || !this.editingPatient.id) return;
                        this.isSubmittingEditPatient = true;
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`/stunting/patients/${this.editingPatient.id}`, {
                                method: 'PUT',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                body: JSON.stringify(this.editingPatient)
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.showEditModal = false;
                                this.applyFilters(this.patientsData.current_page || 1);
                                this.notify('success', 'Data Diperbarui', 'Rekam balita berhasil diperbarui secara realtime.');
                            } else {
                                this.notify('error', 'Gagal Simpan', data.message || 'Gagal memperbarui data.');
                            }
                        } catch (e) {
                            this.notify('error', 'Koneksi Bermasalah', 'Kesalahan sistem: ' + e.message);
                        } finally {
                            this.isSubmittingEditPatient = false;
                        }
                    },

                    openViewModal(p) {
                        this.viewingPatient = p;
                        this.showViewModal = true;
                    },

                    async deletePatient(patient) {
                        if (!confirm(`Hapus rekam data balita "${patient.nama}"?`)) return;

                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`/stunting/patients/${patient.id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.applyFilters(this.patientsData.current_page || 1);
                                this.notify('success', 'Data Dihapus', `Data balita "${patient.nama}" berhasil dihapus.`);
                            } else {
                                this.notify('error', 'Gagal Menghapus', data.message || 'Gagal menghapus data.');
                            }
                        } catch (e) {
                            this.notify('error', 'Kesalahan', 'Gagal menghapus: ' + e.message);
                        }
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>