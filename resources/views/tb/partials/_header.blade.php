        <!-- PAGE HEADER -->
        <div class="border-b border-ant-split bg-white">
            <div class="max-w-7xl mx-auto px-4 py-5 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="ant-page-title flex items-center gap-2.5">
                            <span
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-ant bg-primary-50 text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </span>
                            Dashboard penemuan kasus dan pemantauan pengobatan TBC
                        </h2>
                        <p class="ant-page-subtitle">Pemanfaatan Executive Dashboard "Satu Data
                            Kesehatan Wilayah" Berbasis Visual Geospasial dan Indikator Risiko untuk Kebijakan Lintas
                            Sektor</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Ekspor Excel -->
                        <a :href="'{{ route('tb.export.excel') }}?kabupaten=' + encodeURIComponent(selectedKabupaten || '') + '&kelurahan=' + encodeURIComponent(selectedKelurahan || '') + '&report_type=' + encodeURIComponent(selectedType || '') + '&search=' + encodeURIComponent(searchQuery || '')"
                            class="ant-btn ant-btn-default" title="Ekspor Data ke Format Excel">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Ekspor</span>
                        </a>

                        <!-- Laporan Eksekutif SPM Dinkes -->
                        <a :href="'{{ route('tb.report.executive') }}?kabupaten=' + encodeURIComponent(selectedKabupaten || '') + '&kelurahan=' + encodeURIComponent(selectedKelurahan || '') + '&report_type=' + encodeURIComponent(selectedType || '')"
                            target="_blank" title="Cetak Ringkasan Eksekutif SPM Dinkes"
                            class="ant-btn ant-btn-default">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Laporan SPM</span>
                        </a>

                        @can('clear-data')
                        <!-- Clear Data Massive -->
                        <button @click="openClearMassiveModal()" type="button" title="Kosongkan Semua Data Pasien TBC"
                            class="ant-btn ant-btn-dangerous">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            <span>Clear Data</span>
                        </button>
                        @endcan

                        @can('import-data')
                        <!-- Import Excel / CSV -->
                        <button @click="showImportModal = true" type="button" title="Import Data Excel atau CSV"
                            class="ant-btn ant-btn-default">
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
                        <button @click="openAddModal()" type="button" title="Tambah Data Pasien Baru"
                            class="ant-btn ant-btn-primary">
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
