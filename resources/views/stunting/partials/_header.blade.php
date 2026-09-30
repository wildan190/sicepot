
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
                        <!-- Panduan Indikator & Sistem Upsert -->
                        <button @click="showGuideModal = true" type="button"
                            title="Petunjuk Indikator & Mekanisme Realtime"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-50 hover:bg-amber-100 active:bg-amber-200 text-amber-800 border border-amber-200/80 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Panduan Indikator</span>
                        </button>

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

                        @can('clear-data')
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
                        @endcan

                        @can('import-data')
                        <!-- Import Excel / CSV -->
                        <button @click="showImportModal = true" type="button" title="Import Data Excel e-PPGBM"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span>Import</span>
                        </button>
                        @endcan

                        @can('create-data')
                        <!-- Tambah Data -->
                        <button @click="openAddModal()" type="button" title="Tambah Data Balita Baru"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Tambah Data</span>
                        </button>
                        @endcan
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
                            @foreach(['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $num => $name)
                                <option value="{{ $num }}" @selected($selectedBulan === $num)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[100px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tahun</label>
                        <select x-model="selectedTahun" @change="applyFilters()"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
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

