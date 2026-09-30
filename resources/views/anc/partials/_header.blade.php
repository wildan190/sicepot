        <!-- PAGE HEADER -->
        <div class="bg-white border-b border-slate-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-slate-800 tracking-tight flex items-center gap-2.5">
                            <span class="p-2 rounded-xl bg-pink-50 text-pink-600 border border-pink-100 shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </span>
                            Sinergi gerakan aksi peduli ibu hamil di wilayah
                        </h2>
                        <p class="text-sm md:text-base text-slate-500 mt-1">Satu data bergerak bersama selamatkan ibu
                            hamil di wilayah</p>
                    </div>
                    <div class="flex items-center flex-wrap gap-2">
                        <!-- Ekspor Excel -->
                        <a :href="`{{ route('anc.export.excel') }}?kabupaten=${encodeURIComponent(selectedKabupaten)}&kelurahan=${encodeURIComponent(selectedKelurahan)}&bulan=${encodeURIComponent(selectedBulan)}&search=${encodeURIComponent(searchQuery)}`"
                            title="Unduh Rekapitulasi Data Excel"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Ekspor</span>
                        </a>

                        <!-- Laporan Eksekutif SPM Dinkes -->
                        <a :href="`{{ route('anc.report.executive') }}?kabupaten=${encodeURIComponent(selectedKabupaten)}&kelurahan=${encodeURIComponent(selectedKelurahan)}&bulan=${encodeURIComponent(selectedBulan)}`"
                            target="_blank" title="Cetak Ringkasan Eksekutif SPM Dinkes"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Laporan SPM</span>
                        </a>

                        <!-- Tombol Sirine H-1 -->
                        <button @click="testSirineAudio()" type="button" title="Uji Coba Suara Sirine Peringatan H-1"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                            <span class="relative flex h-2 w-2 shrink-0">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                            </span>
                            <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
                                </path>
                            </svg>
                            <span>Tes Sirine</span>
                        </button>

                        @can('manage-settings')
                        <!-- Tombol Uji Coba Alert Kelahiran (PieSocket WebSocket) -->
                        <button @click="triggerTestBirthAlert()" :disabled="isTestingBirthAlert" type="button"
                            title="Kirim Sinyal Uji Coba WebSocket Alert Kelahiran ke HP & Desktop"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 text-xs font-bold rounded-xl shadow-xs transition-all duration-150 cursor-pointer disabled:opacity-50">
                            <span class="text-sm leading-none">👶</span>
                            <span x-show="!isTestingBirthAlert">Tes Alert Kelahiran</span>
                            <span x-show="isTestingBirthAlert">Mengirim...</span>
                        </button>
                        @endcan

                        @can('import-data')
                        <!-- Import Excel / CSV -->
                        <button @click="showImportModal = true" type="button" title="Import Data Excel atau CSV"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <span>Import</span>
                        </button>
                        @endcan

                        @can('create-data')
                        <!-- Tambah Data -->
                        <button @click="openAddModal()" type="button" title="Tambah Data Baru"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-pink-600 hover:bg-pink-700 active:bg-pink-800 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Tambah Data</span>
                        </button>
                        @endcan

                        @can('clear-data')
                        <!-- Clear All Data -->
                        <button @click="showClearAllModal = true" type="button" title="Hapus Seluruh Data ANC"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Clear All Data</span>
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN DASHBOARD CONTENT -->
        <div class="py-8 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

