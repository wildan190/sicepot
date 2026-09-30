            <!-- ================= MODAL: IMPORT & PREVIEW EXCEL/CSV UNIVERSAL ================= -->
            <div x-show="showImportModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="closeImportModal()"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-4xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Import Data Ibu Hamil (Universal Excel /
                                    CSV)</h3>
                                <p class="text-xs text-slate-500">Mendukung format otomatis register KIA, kohort ANC
                                    fasyankes, atau berkas custom puskesmas tanpa batasan template kaku.</p>
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
                                :class="isDragging ? 'border-pink-500 bg-pink-100/50 scale-[1.01] ring-4 ring-pink-200' : 'border-pink-200 bg-pink-50/20 hover:bg-pink-50/40'"
                                class="border-2 border-dashed rounded-2xl p-10 text-center transition-all duration-200 cursor-pointer relative">
                                <input type="file" id="ancFileInput" @change="onFileSelected($event)"
                                    accept=".xlsx,.xls,.csv" class="hidden">
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
                                    <span class="text-xs text-slate-500 mt-1">atau <span
                                            class="text-pink-600 font-semibold underline underline-offset-2 hover:text-pink-800">klik
                                            untuk menjelajahi komputer</span></span>
                                    <span class="text-[11px] text-slate-400 mt-2">Mendukung berkas Excel (.xlsx, .xls) &
                                        CSV</span>
                                </label>
                                <div x-show="isParsing"
                                    class="mt-4 flex items-center justify-center gap-2 text-pink-600 text-xs font-semibold">
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
                                                <template x-for="(row, idx) in (importPreview?.preview_samples || [])"
                                                    :key="idx">
                                                    <tr class="hover:bg-slate-50">
                                                        <td class="p-2.5 text-slate-500" x-text="idx + 1"></td>
                                                        <td class="p-2.5 font-medium text-slate-800"
                                                            x-text="row.nama_lengkap || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.nama_suami || '-'">
                                                        </td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.nik || '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.no_telepon || '-'">
                                                        </td>
                                                        <td class="p-2.5 text-slate-600"
                                                            x-text="(row.umur ? row.umur + ' th' : (row.tanggal_lahir || '-'))">
                                                        </td>
                                                        <td class="p-2.5 text-slate-600"
                                                            x-text="row.gravida ? 'G' + row.gravida + (row.para !== undefined ? 'P' + row.para : '') + (row.abortus !== undefined ? 'A' + row.abortus : '') : '-'">
                                                        </td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.hpl || '-'"></td>
                                                        <td class="p-2.5 text-slate-600"
                                                            x-text="row.lila ? row.lila + ' cm' : '-'"></td>
                                                        <td class="p-2.5 text-slate-600" x-text="row.kelurahan || '-'">
                                                        </td>
                                                        <td class="p-2.5 text-slate-600"
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

