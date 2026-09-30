            <!-- ================= MODAL: REKAM JEJAK / TREATMENT TIMELINE ================= -->
            <div x-show="showTimelineModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showTimelineModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-slate-100">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Riwayat Perjalanan Pengobatan TBC</h3>
                                <p class="text-xs text-slate-500"
                                    x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '') + ' | NIK: ' + (targetPatient?.nik || '-')">
                                </p>
                            </div>
                            <button @click="showTimelineModal = false"
                                class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4">
                            <div x-show="isLoadingTimeline"
                                class="py-8 text-center text-xs text-slate-500 flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-indigo-600" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Memuat kronologi pengobatan dan evaluasi laboratorium...
                            </div>

                            <div x-show="!isLoadingTimeline" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                <div class="relative pl-6 border-l-2 border-indigo-200 space-y-6">
                                    <template x-for="(event, idx) in timelineData" :key="event.id">
                                        <div class="relative">
                                            <!-- Dot marker -->
                                            <div :class="event.is_current ? 'bg-indigo-600 ring-4 ring-indigo-100' : 'bg-slate-400 ring-4 ring-slate-100'"
                                                class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white shadow-xs">
                                            </div>

                                            <div :class="event.is_current ? 'border-indigo-300 bg-indigo-50/40' : 'border-slate-200 bg-white'"
                                                class="p-4 rounded-2xl border shadow-xs">
                                                <div class="flex items-center justify-between">
                                                    <div class="font-bold text-sm text-slate-800" x-text="event.title">
                                                    </div>
                                                    <span class="text-xs font-semibold text-slate-500"
                                                        x-text="event.date"></span>
                                                </div>
                                                <div class="text-xs text-indigo-700 font-medium mt-0.5"
                                                    x-text="event.faskes"></div>

                                                <div
                                                    class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 pt-3 border-t border-slate-100 text-xs">
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Tipe
                                                            Laporan</span>
                                                        <span class="font-bold text-slate-700 uppercase"
                                                            x-text="event.report_type"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Tipe /
                                                            Regimen</span>
                                                        <span class="font-semibold text-slate-700"
                                                            x-text="event.regimen"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Diagnosis
                                                            TCM</span>
                                                        <span class="font-bold text-rose-600"
                                                            x-text="event.diagnosis"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-400 block text-[10px]">Hasil
                                                            Akhir</span>
                                                        <span class="font-medium text-slate-700"
                                                            x-text="event.outcome"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-6 pt-3 border-t border-slate-100 text-right">
                                <button @click="showTimelineModal = false"
                                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold cursor-pointer">Tutup
                                    Garis Waktu</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

