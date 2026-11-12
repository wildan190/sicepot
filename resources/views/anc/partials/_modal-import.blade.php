            <!-- ================= MODAL: IMPORT & PREVIEW EXCEL/CSV UNIVERSAL ================= -->
            <div x-show="showImportModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="closeImportModal()"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-4 border-b border-ant-split">
                            <div>
                                <h3 class="ant-section-title">Import Data Ibu Hamil (Universal Excel /
                                    CSV)</h3>
                                <p class="ant-text-secondary mt-0.5">Mendukung format otomatis register KIA, kohort ANC
                                    fasyankes, atau berkas custom puskesmas tanpa batasan template kaku.</p>
                            </div>
                            <button @click="closeImportModal()"
                                class="p-2 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
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
                                :class="isDragging ? 'border-primary bg-primary-50 scale-[1.01] ring-4 ring-primary-100' : 'border-primary-100 bg-primary-50/30 hover:bg-primary-50/60'"
                                class="border-2 border-dashed rounded-ant-lg p-10 text-center transition-all duration-200 cursor-pointer relative">
                                <input type="file" id="ancFileInput" @change="onFileSelected($event)"
                                    accept=".xlsx,.xls,.csv" class="hidden">
                                <label for="ancFileInput" class="cursor-pointer flex flex-col items-center">
                                    <div :class="isDragging ? 'bg-primary text-white scale-110' : 'bg-primary-50 text-primary'"
                                        class="w-16 h-16 rounded-ant-lg flex items-center justify-center mb-3.5 transition-all duration-200 shadow-ant-card">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                    </div>
                                    <span class="text-base font-semibold text-ant-title"
                                        x-text="isDragging ? 'Lepaskan berkas di sini untuk mem-parsing' : 'Tarik & Letakkan (Drag & Drop) berkas Excel / CSV di sini'"></span>
                                    <span class="text-ant-secondary mt-1">atau <span
                                            class="text-primary font-semibold underline underline-offset-2 hover:text-primary-600">klik
                                            untuk menjelajahi komputer</span></span>
                                    <span class="text-[11px] text-ant-secondary mt-2">Mendukung berkas Excel (.xlsx, .xls) &
                                        CSV</span>
                                </label>
                                <div x-show="isParsing"
                                    class="mt-4 flex items-center justify-center gap-2 text-primary text-xs font-semibold">
                                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Sedang membaca dan mem-parsing berkas ANC secara universal...
                                </div>
                            </div>

                            <!-- Step 2: Preview Area -->
                            <div x-show="importPreview" class="space-y-4">
                                <div
                                    class="ant-alert ant-alert-success flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-0.5 bg-success text-white rounded-ant-sm text-xs font-bold"
                                                x-text="importPreview?.type_label"></span>
                                            <span class="font-semibold text-success-600 text-sm"
                                                x-text="importPreview?.original_name"></span>
                                        </div>
                                        <div class="text-success-600 mt-1"
                                            x-text="'Fasyankes: ' + (importPreview?.fasyankes_name || '-') + ' | Periode: ' + (importPreview?.period || '-')">
                                        </div>
                                        <div
                                            class="text-[11px] text-success-600 mt-1 flex flex-wrap gap-1 items-center">
                                            <span class="font-semibold text-success-600">Kolom Terdeteksi:</span>
                                            <template x-for="field in (importPreview?.detected_fields || [])"
                                                :key="field">
                                                <span
                                                    class="ant-tag ant-tag-green font-mono text-[10px]"
                                                    x-text="field"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="ant-text-secondary">Total Baris Terdeteksi:</span>
                                        <div class="text-xl font-bold text-success-600"
                                            x-text="(importPreview?.total_rows || 0) + ' Baris'"></div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="ant-label text-xs font-bold uppercase tracking-wider mb-2">Cuplikan
                                        Preview 10 Baris Pertama:</h4>
                                    <div class="overflow-x-auto max-h-60 border border-ant-border rounded-ant-lg">
                                        <table class="ant-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Nama Suami</th>
                                                    <th>NIK</th>
                                                    <th>Telp</th>
                                                    <th>Umur / Tgl Lahir</th>
                                                    <th>G-P-A</th>
                                                    <th>HPL / TP</th>
                                                    <th>LiLA</th>
                                                    <th>Kelurahan</th>
                                                    <th>Risiko</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(row, idx) in (importPreview?.preview_samples || [])"
                                                    :key="idx">
                                                    <tr>
                                                        <td class="text-ant-secondary" x-text="idx + 1"></td>
                                                        <td class="font-medium text-ant-title"
                                                            x-text="row.nama_lengkap || '-'"></td>
                                                        <td class="text-ant-text" x-text="row.nama_suami || '-'">
                                                        </td>
                                                        <td class="text-ant-text" x-text="row.nik || '-'"></td>
                                                        <td class="text-ant-text" x-text="row.no_telepon || '-'">
                                                        </td>
                                                        <td class="text-ant-text"
                                                            x-text="(row.umur ? row.umur + ' th' : (row.tanggal_lahir || '-'))">
                                                        </td>
                                                        <td class="text-ant-text"
                                                            x-text="row.gravida ? 'G' + row.gravida + (row.para !== undefined ? 'P' + row.para : '') + (row.abortus !== undefined ? 'A' + row.abortus : '') : '-'">
                                                        </td>
                                                        <td class="text-ant-text" x-text="row.hpl || '-'"></td>
                                                        <td class="text-ant-text"
                                                            x-text="row.lila ? row.lila + ' cm' : '-'"></td>
                                                        <td class="text-ant-text" x-text="row.kelurahan || '-'">
                                                        </td>
                                                        <td class="text-ant-text"
                                                            x-text="row.faktor_risiko || (row.status_risti || 'Normal')">
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="mt-6 pt-4 border-t border-ant-split flex items-center justify-between">
                            <button x-show="importPreview" @click="resetImport()" type="button"
                                class="ant-btn ant-btn-text ant-btn-sm">← Ganti Berkas</button>
                            <div class="flex items-center gap-2 ml-auto">
                                <button @click="closeImportModal()" type="button"
                                    class="ant-btn ant-btn-default">Batal</button>
                                <button x-show="importPreview" :disabled="isSubmittingImport" @click="commitImport()"
                                    type="button"
                                    class="ant-btn ant-btn-success flex items-center gap-2">
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
