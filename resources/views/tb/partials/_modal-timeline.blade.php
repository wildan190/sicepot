            <!-- ================= MODAL: REKAM JEJAK / TREATMENT TIMELINE ================= -->
            <div x-show="showTimelineModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showTimelineModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                            <div>
                                <h3 class="ant-section-title">Riwayat Perjalanan Pengobatan TBC</h3>
                                <p class="text-xs text-ant-secondary"
                                    x-text="'Pasien: ' + (targetPatient?.nama_lengkap || '') + ' | NIK: ' + (targetPatient?.nik || '-')">
                                </p>
                            </div>
                            <button @click="showTimelineModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4">
                            <div x-show="isLoadingTimeline"
                                class="py-8 text-center text-xs text-ant-secondary flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-primary" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Memuat kronologi pengobatan dan evaluasi laboratorium...
                            </div>

                            <div x-show="!isLoadingTimeline" class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                <div class="relative pl-6 border-l-2 border-primary-100 space-y-6">
                                    <template x-for="(event, idx) in timelineData" :key="event.id">
                                        <div class="relative">
                                            <!-- Dot marker -->
                                            <div :class="event.is_current ? 'bg-primary ring-4 ring-primary-50' : 'bg-ant-disabled ring-4 ring-ant-split'"
                                                class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white shadow-ant-card">
                                            </div>

                                            <div :class="event.is_current ? 'border-primary-200 bg-primary-50/40' : 'border-ant-border bg-white'"
                                                class="p-4 rounded-ant-lg border shadow-ant-card">
                                                <div class="flex items-center justify-between">
                                                    <div class="font-bold text-sm text-ant-title" x-text="event.title">
                                                    </div>
                                                    <span class="text-xs font-semibold text-ant-secondary"
                                                        x-text="event.date"></span>
                                                </div>
                                                <div class="text-xs text-primary-600 font-medium mt-0.5"
                                                    x-text="event.faskes"></div>

                                                <div
                                                    class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-3 pt-3 border-t border-ant-split text-xs">
                                                    <div>
                                                        <span class="text-ant-secondary block text-[10px]">Tipe
                                                            Laporan</span>
                                                        <span class="font-bold text-ant-text uppercase"
                                                            x-text="event.report_type"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-ant-secondary block text-[10px]">Tipe /
                                                            Regimen</span>
                                                        <span class="font-semibold text-ant-text"
                                                            x-text="event.regimen"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-ant-secondary block text-[10px]">Diagnosis
                                                            TCM</span>
                                                        <span class="font-bold text-danger"
                                                            x-text="event.diagnosis"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-ant-secondary block text-[10px]">Hasil
                                                            Akhir</span>
                                                        <span class="font-medium text-ant-text"
                                                            x-text="event.outcome"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-6 pt-3 border-t border-ant-split text-right">
                                <button @click="showTimelineModal = false"
                                    class="ant-btn ant-btn-default">Tutup
                                    Garis Waktu</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
