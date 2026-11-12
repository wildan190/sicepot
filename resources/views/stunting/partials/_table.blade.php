            {{-- DATA TABLE --}}
            <div class="ant-card overflow-hidden">
                <div
                    class="ant-card-head flex-col sm:flex-row sm:items-center gap-3">
                    <div>
                        <h3 class="ant-card-title">Data Balita</h3>
                        <p class="ant-text-secondary mt-1">Daftar rekaman pengukuran balita stunting</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5 text-xs text-ant-secondary">
                            <span>Per halaman:</span>
                            <select x-model="perPage" @change="changePerPage()"
                                class="ant-select w-auto">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <span
                            class="ant-tag ant-tag-green"
                            x-text="(patientsData.total || 0) + ' Total Balita'"></span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="ant-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th class="text-center">JK</th>
                                <th>Tgl Lahir</th>
                                <th>Desa</th>
                                <th>Posyandu</th>
                                <th class="text-right">BB Lahir</th>
                                <th class="text-right">Berat</th>
                                <th class="text-right">Tinggi</th>
                                <th>TB/U</th>
                                <th>BB/U</th>
                                <th>BB/TB</th>
                                <th class="text-center">Lolos</th>
                                <th class="text-center text-danger-600">Test Hb</th>
                                <th class="text-center text-primary-600">Mantoux</th>
                                <th class="text-center text-primary-600">Sp.A</th>
                                <th>Tgl Ukur</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(p, i) in (patientsData.data || [])" :key="p.id">
                                <tr class="transition-colors">
                                    <td class="text-ant-secondary"
                                        x-text="((patientsData.current_page || 1) - 1) * (patientsData.per_page || 15) + i + 1">
                                    </td>
                                    <td class="text-ant-text font-medium">
                                        <div class="font-semibold text-ant-title" x-text="p.nama || '-'"></div>
                                        <div class="text-[10px] text-ant-secondary" x-text="p.nik ? 'NIK: ' + p.nik : ''"></div>
                                        <template x-if="p.nama_ortu">
                                            <div class="text-[10px] text-ant-secondary" x-text="'Ortu: ' + p.nama_ortu"></div>
                                        </template>
                                    </td>
                                    <td class="text-center">
                                        <template x-if="p.jenis_kelamin === 'L'">
                                            <span
                                                class="ant-tag ant-tag-blue"
                                                title="Laki-laki">L</span>
                                        </template>
                                        <template x-if="p.jenis_kelamin === 'P'">
                                            <span
                                                class="ant-tag ant-tag-purple"
                                                title="Perempuan">P</span>
                                        </template>
                                        <template x-if="p.jenis_kelamin !== 'L' && p.jenis_kelamin !== 'P'">
                                            <span class="text-ant-secondary">-</span>
                                        </template>
                                    </td>
                                    <td class="text-ant-text" x-text="formatDate(p.tanggal_lahir)"></td>
                                    <td class="text-ant-text" x-text="p.desa || '-'"></td>
                                    <td class="text-ant-secondary" x-text="p.posyandu || '-'"></td>
                                    <td class="text-right text-ant-text"
                                        x-text="p.bb_lahir ? Number(p.bb_lahir).toFixed(2) + ' kg' : '-'"></td>
                                    <td class="text-right font-medium text-ant-text"
                                        x-text="p.berat ? Number(p.berat).toFixed(2) + ' kg' : '-'"></td>
                                    <td class="text-right font-medium text-ant-text"
                                        x-text="p.tinggi ? Number(p.tinggi).toFixed(1) + ' cm' : '-'"></td>
                                    <td>
                                        <template x-if="p.tbu_kategori">
                                            <span class="inline-block px-2 py-0.5 rounded-ant-sm text-[10px] font-semibold"
                                                :class="getTbuBadgeClass(p.tbu_kategori)"
                                                x-text="p.tbu_kategori"></span>
                                        </template>
                                    </td>
                                    <td>
                                        <template x-if="p.bbu_kategori">
                                            <span class="inline-block px-2 py-0.5 rounded-ant-sm text-[10px] font-semibold"
                                                :class="getBbuBadgeClass(p.bbu_kategori)"
                                                x-text="p.bbu_kategori"></span>
                                        </template>
                                    </td>
                                    <td>
                                        <template x-if="p.bbtb_kategori">
                                            <span class="inline-block px-2 py-0.5 rounded-ant-sm text-[10px] font-semibold"
                                                :class="getBbtbBadgeClass(p.bbtb_kategori)"
                                                x-text="p.bbtb_kategori"></span>
                                        </template>
                                    </td>
                                    <td class="text-center">
                                        <template x-if="p.naik_berat_badan === 'N'">
                                            <span
                                                class="inline-flex items-center justify-center w-5 h-5 rounded-ant-sm bg-success-100 text-success-700"
                                                title="Naik">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                            </span>
                                        </template>
                                        <template x-if="p.naik_berat_badan === 'T'">
                                            <span
                                                class="inline-flex items-center justify-center w-5 h-5 rounded-ant-sm bg-danger-100 text-danger-600"
                                                title="Turun">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                            </span>
                                        </template>
                                        <template x-if="p.naik_berat_badan !== 'N' && p.naik_berat_badan !== 'T'">
                                            <span class="text-ant-secondary">-</span>
                                        </template>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-ant-sm text-[10px] font-bold"
                                            :class="p.test_hemoglobin === 'Ya' ? 'bg-danger-50 text-danger-700 border border-danger-100' : 'text-ant-secondary'"
                                            x-text="p.test_hemoglobin || '-'"></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-ant-sm text-[10px] font-bold"
                                            :class="p.test_mantoux === 'Ya' ? 'bg-primary-50 text-primary-700 border border-primary-100' : 'text-ant-secondary'"
                                            x-text="p.test_mantoux || '-'"></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-ant-sm text-[10px] font-bold"
                                            :class="p.konsul_spa === 'Ya' ? 'bg-primary-50 text-primary-700 border border-primary-100' : 'text-ant-secondary'"
                                            x-text="p.konsul_spa || '-'"></span>
                                    </td>
                                    <td class="text-ant-secondary whitespace-nowrap"
                                        x-text="formatDate(p.tanggal_pengukuran)"></td>
                                    <td class="whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <!-- View Detail Balita -->
                                            <button @click="openViewModal(p)" type="button"
                                                class="p-1.5 text-primary-600 hover:text-primary-800 hover:bg-primary-50 rounded-ant transition-colors cursor-pointer"
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

                                            <!-- WhatsApp Direct Reminder -->
                                            <button @click="openWhatsAppModal(p)" type="button"
                                                class="p-1.5 text-success-600 hover:text-success-800 hover:bg-success-50 rounded-ant transition-colors cursor-pointer"
                                                title="Kirim Pesan WhatsApp (wa.me)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                    </path>
                                                </svg>
                                            </button>
                                            @can('edit-data')
                                            <!-- Edit Balita -->
                                            <button @click="openEditModal(p)" type="button"
                                                class="p-1.5 text-primary-600 hover:text-primary-900 hover:bg-primary-50 rounded-ant transition-colors cursor-pointer"
                                                title="Edit Data Balita">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            @endcan
                                            @can('delete-data')
                                            <!-- Hapus Balita -->
                                            <button @click="deletePatient(p)" type="button"
                                                class="p-1.5 text-danger hover:text-danger-700 hover:bg-danger-50 rounded-ant transition-colors cursor-pointer"
                                                title="Hapus Data Balita">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!patientsData.data || patientsData.data.length === 0">
                                <tr>
                                    <td colspan="15" class="text-center text-ant-secondary">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-ant-disabled" fill="none"
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
                    class="px-5 py-3.5 bg-ant-layout border-t border-ant-split flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-ant-text">
                    <div>
                        <span
                            x-text="'Menampilkan data ' + (patientsData.from || 0) + ' - ' + (patientsData.to || 0) + ' dari ' + (patientsData.total || 0) + ' total data balita'"></span>
                        <span class="mx-2 text-ant-disabled">|</span>
                        <span
                            x-text="'Halaman ' + (patientsData.current_page || 1) + ' dari ' + (patientsData.last_page || 1)"></span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button :disabled="(patientsData.current_page || 1) <= 1 || isTableLoading"
                            @click="changePage(1)" title="Halaman Pertama"
                            class="ant-btn ant-btn-default">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>
                        <button :disabled="(patientsData.current_page || 1) <= 1 || isTableLoading"
                            @click="changePage((patientsData.current_page || 1) - 1)"
                            class="ant-btn ant-btn-default">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Sebelumnya</span>
                        </button>

                        <div class="px-3 py-1.5 bg-success-50 text-success-700 font-bold rounded-ant border border-success-100"
                            x-text="patientsData.current_page || 1"></div>

                        <button
                            :disabled="(patientsData.current_page || 1) >= (patientsData.last_page || 1) || isTableLoading"
                            @click="changePage((patientsData.current_page || 1) + 1)"
                            class="ant-btn ant-btn-default">
                            <span>Berikutnya</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <button
                            :disabled="(patientsData.current_page || 1) >= (patientsData.last_page || 1) || isTableLoading"
                            @click="changePage(patientsData.last_page || 1)" title="Halaman Terakhir"
                            class="ant-btn ant-btn-default">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
        BULK IMPORT MODAL
        ============================================================ --}}
        <div x-show="showImportModal" x-transition.opacity
            class="fixed inset-0 z-50 bg-black/45 backdrop-blur-xs flex items-center justify-center p-4"
            style="display:none">
            <div @click.outside="if(!importLoading) showImportModal = false"
                class="bg-white rounded-ant-lg shadow-ant-dropdown w-full max-w-xl border border-ant-split overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-4 bg-success flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="p-1.5 bg-white/15 rounded-ant">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-white">Bulk Import Data Excel Stunting</h3>
                            <p class="text-[11px] text-success-50">Pilih 1 atau beberapa file Excel sekaligus</p>
                        </div>
                    </div>
                    <button @click="if(!importLoading) { showImportModal = false }"
                        class="p-1 rounded-ant text-white/80 hover:text-white hover:bg-white/10 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-4">

                    {{-- Step: Pick --}}
                    <div x-show="importStep === 'pick'" class="space-y-3.5">
                        {{-- Drop zone --}}
                        <label for="stunting-bulk-file"
                            class="flex flex-col items-center justify-center border-2 border-dashed border-ant-border rounded-ant-lg p-8 cursor-pointer hover:border-success hover:bg-success-50 transition-all group">
                            <svg class="w-10 h-10 text-ant-disabled group-hover:text-success transition-colors mb-2.5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span
                                class="text-sm font-semibold text-ant-text group-hover:text-success-700 transition-colors">
                                Klik atau seret file Excel ke sini
                            </span>
                            <span class="text-xs text-ant-secondary mt-1">Format: .xlsx / .xls — Maks. 20 file,
                                20MB/file</span>
                            <input id="stunting-bulk-file" type="file" accept=".xlsx,.xls" multiple class="hidden"
                                @change="handleBulkFileSelect($event)">
                        </label>

                        {{-- File list preview --}}
                        <div x-show="bulkFiles.length > 0" class="space-y-1.5 max-h-48 overflow-y-auto">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-semibold text-ant-text"
                                    x-text="bulkFiles.length + ' file dipilih'"></span>
                                <button
                                    @click="bulkFiles = []; document.getElementById('stunting-bulk-file').value = ''"
                                    class="text-[11px] text-danger hover:text-danger-700 font-semibold cursor-pointer transition">
                                    Hapus Semua
                                </button>
                            </div>
                            <template x-for="(f, i) in bulkFiles" :key="i">
                                <div
                                    class="flex items-center gap-2 bg-ant-layout border border-ant-border rounded-ant px-3 py-2">
                                    <svg class="w-4 h-4 text-success-600 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="flex-1 text-xs text-ant-text font-medium truncate"
                                        x-text="f.name"></span>
                                    <span class="text-[10px] text-ant-secondary"
                                        x-text="(f.size / 1024 / 1024).toFixed(1) + ' MB'"></span>
                                    <button @click="bulkFiles.splice(i, 1)"
                                        class="text-ant-secondary hover:text-danger transition cursor-pointer ml-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button @click="startBulkImport()" :disabled="bulkFiles.length === 0 || importLoading"
                            class="ant-btn ant-btn-primary ant-btn-block">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span
                                x-text="bulkFiles.length > 1 ? 'Import ' + bulkFiles.length + ' File Sekaligus' : 'Import File'"></span>
                        </button>
                    </div>

                    {{-- Step: Processing --}}
                    <div x-show="importStep === 'processing'" class="space-y-3">
                        <div class="text-center py-3">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-success-100 mb-3">
                                <svg class="w-6 h-6 text-success-600 animate-spin" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-ant-title">Memproses file...</p>
                            <p class="text-xs text-ant-secondary mt-0.5"
                                x-text="'Sedang mengimpor ' + bulkFiles.length + ' file. Mohon tunggu.'"></p>
                        </div>
                        <div class="w-full bg-ant-split rounded-full h-2 overflow-hidden">
                            <div class="bg-success h-2 rounded-full transition-all duration-500 ease-out"
                                :style="'width:' + bulkProgress + '%'"></div>
                        </div>
                        <p class="text-[11px] text-center text-ant-secondary" x-text="bulkProgressLabel"></p>
                    </div>

                    {{-- Step: Done --}}
                    <div x-show="importStep === 'done'" class="space-y-3.5">
                        {{-- Summary banner --}}
                        <div class="p-3.5 rounded-ant-lg flex items-start gap-3"
                            :class="bulkResults.some(r => !r.success) ? 'bg-warning-50 border border-warning-100' : 'bg-success-50 border border-success-100'">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                                :class="bulkResults.some(r => !r.success) ? 'bg-warning-100 text-warning-700' : 'bg-success-100 text-success-700'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-ant-title" x-text="importSuccessMsg"></p>
                                <div class="flex items-center gap-3 mt-1.5 text-[11px] font-semibold">
                                    <span class="text-success-700 flex items-center gap-1">
                                        <span x-text="bulkSummary.inserted"></span> data baru
                                    </span>
                                    <span class="text-primary-700 flex items-center gap-1">
                                        <span x-text="bulkSummary.updated"></span> diperbarui
                                    </span>
                                    <template x-if="bulkSummary.failed > 0">
                                        <span class="text-danger-600 flex items-center gap-1">
                                            <span x-text="bulkSummary.failed"></span> gagal
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Per-file result list --}}
                        <div class="max-h-48 overflow-y-auto space-y-1.5">
                            <template x-for="(r, i) in bulkResults" :key="i">
                                <div class="flex items-start gap-2.5 px-3 py-2 rounded-ant text-xs"
                                    :class="r.success ? 'bg-ant-layout border border-ant-border' : 'bg-danger-50 border border-danger-100'">
                                    <div class="w-4 h-4 rounded-full flex items-center justify-center shrink-0 mt-0.5"
                                        :class="r.success ? 'bg-success-100 text-success-700' : 'bg-danger-100 text-danger-600'">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                :d="r.success ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-ant-title truncate" x-text="r.file"></p>
                                        <p class="text-ant-secondary mt-0.5" x-text="r.message"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex gap-2">
                            <button @click="resetBulkImport()"
                                class="ant-btn ant-btn-default flex-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Import Lagi
                            </button>
                            <button @click="showImportModal = false; applyFilters(1)"
                                class="ant-btn ant-btn-primary flex-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Selesai & Refresh
                            </button>
                        </div>
                    </div>

                    {{-- Error global --}}
                    <div x-show="importError" class="ant-alert ant-alert-error">
                        <p class="text-xs text-danger-600 font-semibold" x-text="importError"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
        CLEAR MASSIVE MODAL
        ============================================================ --}}
        <div x-show="showClearMassiveModal" x-cloak
            class="fixed inset-0 z-50 bg-black/45 backdrop-blur-xs flex items-center justify-center p-4"
            style="display:none">
            <div @click.outside="if(!isClearingMassive) showClearMassiveModal = false"
                class="bg-white rounded-ant-lg shadow-ant-dropdown w-full max-w-lg p-6 space-y-4 border border-danger-100">
                <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-ant-lg bg-danger-100 text-danger-600 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-ant-title">Kosongkan Semua Data Balita Stunting</h3>
                            <p class="text-xs text-ant-secondary">Tindakan pembersihan data masal (Clear Massive)</p>
                        </div>
                    </div>
                    <button @click="if(!isClearingMassive) showClearMassiveModal = false"
                        class="p-2 text-ant-secondary hover:text-ant-text rounded-ant hover:bg-ant-split cursor-pointer transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="p-4 bg-danger-50 border border-danger-100 rounded-ant-lg text-danger-700 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-sm text-danger-700">
                            <svg class="w-4 h-4 text-danger-600 shrink-0" fill="none" stroke="currentColor"
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
                            class="pt-2 border-t border-danger-100 text-[11px] text-danger-700 flex items-center justify-between font-semibold">
                            <span>Total data balita saat ini:</span>
                            <span class="px-2 py-0.5 bg-danger-100 text-danger-700 rounded-ant-sm font-bold"
                                x-text="(stats.total ?? '{{ $total }}') + ' Data Balita'"></span>
                        </div>
                    </div>

                    <div>
                        <label class="ant-label">
                            Ketik kata <span class="font-bold text-danger-600 tracking-wider">HAPUS</span> di bawah ini
                            untuk mengonfirmasi:
                        </label>
                        <input type="text" x-model="clearMassiveKeyword" placeholder="Ketik HAPUS"
                            class="ant-input font-semibold tracking-wider">
                    </div>

                    <div x-show="clearMassiveError" class="ant-alert ant-alert-error">
                        <p class="text-xs text-danger-600" x-text="clearMassiveError"></p>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="pt-4 border-t border-ant-split flex items-center justify-end gap-2">
                    <button @click="if(!isClearingMassive) showClearMassiveModal = false" type="button"
                        :disabled="isClearingMassive"
                        class="ant-btn ant-btn-default">
                        Batal
                    </button>
                    <button :disabled="clearMassiveKeyword.trim() !== 'HAPUS' || isClearingMassive"
                        @click="executeClearMassive()" type="button"
                        class="ant-btn ant-btn-dangerous">
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
