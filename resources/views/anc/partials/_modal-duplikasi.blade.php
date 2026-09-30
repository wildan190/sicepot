            <!-- ================= MODAL: SKRINING DUPLIKASI DATA ================= -->
            <div x-show="showDuplicateModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showDuplicateModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Skrining Pasien Ganda / Lintas Wilayah
                                </h3>
                                <p class="text-xs text-slate-500"
                                    x-text="'Pemeriksaan: ' + (targetPatient?.nama_lengkap || '')"></p>
                            </div>
                            <button @click="showDuplicateModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div x-show="isLoadingDuplicates"
                                class="py-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-amber-600" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Memindai database untuk mendeteksi NIK & nama kembar...
                            </div>

                            <div x-show="!isLoadingDuplicates && duplicateResults.length === 0"
                                class="p-6 text-center text-xs text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200">
                                <div
                                    class="w-10 h-10 mx-auto rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="font-bold text-sm">Data Bersih & Unik</div>
                                <p class="text-[11px] text-emerald-600 mt-1">Tidak ditemukan NIK ganda atau rekaman nama
                                    identik di fasyankes maupun kelurahan lain.</p>
                            </div>

                            <div x-show="!isLoadingDuplicates && duplicateResults.length > 0" class="space-y-2.5">
                                <div
                                    class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <span>Terdeteksi <span class="font-bold underline"
                                            x-text="duplicateResults.length"></span> potensi data ganda / lintas
                                        wilayah:</span>
                                </div>

                                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                                    <template x-for="dup in duplicateResults" :key="dup.id">
                                        <div
                                            class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs flex items-center justify-between">
                                            <div>
                                                <span
                                                    class="px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md"
                                                    x-text="dup.type"></span>
                                                <div class="font-bold text-slate-800 text-sm mt-1"
                                                    x-text="dup.patient.nama_lengkap"></div>
                                                <div class="text-slate-500 text-[11px]" x-text="dup.description"></div>
                                            </div>
                                            <a :href="`/anc/dashboard?search=${encodeURIComponent(dup.patient.nik || dup.patient.nama_lengkap)}`"
                                                target="_blank"
                                                class="px-2.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-lg border border-slate-200 shadow-xs text-[11px]">
                                                Lihat Data ↗
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 text-right">
                                <button @click="showDuplicateModal = false"
                                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

