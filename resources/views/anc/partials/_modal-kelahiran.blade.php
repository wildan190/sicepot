            <!-- ================= MODAL: CATAT KELAHIRAN (REALTIME PIESOCKET ALERT) ================= -->

            <div x-show="showRecordBirthModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showRecordBirthModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-3 border-b border-ant-split">
                            <div class="flex items-center gap-2.5">
                                <span class="p-2 rounded-ant bg-warning-50 text-warning-600 text-lg">👶</span>
                                <div>
                                    <h3 class="ant-section-title">Catat Kelahiran Pasien</h3>
                                    <p class="ant-text-secondary mt-0.5" x-text="targetBirthPatient?.nama_lengkap"></p>
                                </div>
                            </div>
                            <button @click="showRecordBirthModal = false"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="submitRecordBirth()" class="mt-4 space-y-3 text-xs">
                            <div class="p-3 bg-warning-50 rounded-ant border border-warning-100 text-warning-600">
                                <p class="font-semibold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-warning animate-pulse"></span>
                                    Sistem akan otomatis menyiarkan sinyal WebSocket PieSocket!
                                </p>
                                <p class="text-[11px] text-warning-600 mt-0.5">
                                    Alert sirine & notifikasi kelahiran akan seketika berbunyi di browser HP
                                    nakes/petugas yang sedang aktif.
                                </p>
                            </div>

                            <div>
                                <label class="ant-label">Tanggal Bersalin *</label>
                                <input type="date" x-model="birthForm.tanggal_bersalin" required
                                    class="ant-input font-medium">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="ant-label">Tempat Bersalin</label>
                                    <input type="text" x-model="birthForm.tempat_bersalin"
                                        placeholder="Puskesmas PONED / BPM"
                                        class="ant-input">
                                </div>
                                <div>
                                    <label class="ant-label">Penolong Persalinan</label>
                                    <input type="text" x-model="birthForm.penolong_persalinan"
                                        placeholder="Bidan / Dokter SpOG"
                                        class="ant-input">
                                </div>
                            </div>


                            <div>
                                <label class="ant-label">Komplikasi / Catatan Persalinan
                                    (Opsional)</label>
                                <textarea rows="2" x-model="birthForm.komplikasi_persalinan"
                                    placeholder="Catatan komplikasi robekan perineum, perdarahan, dsb."
                                    class="ant-input !h-auto py-2 resize-none"></textarea>
                            </div>

                            <div class="mt-5 pt-3 border-t border-ant-split flex items-center justify-end gap-2">
                                <button @click="showRecordBirthModal = false" type="button"
                                    class="ant-btn ant-btn-default">Batal</button>
                                <button type="submit" :disabled="isSubmittingBirth"
                                    class="ant-btn ant-btn-success flex items-center gap-2">
                                    <span x-show="!isSubmittingBirth">Simpan & Siarkan Alert Realtime</span>
                                    <span x-show="isSubmittingBirth" class="flex items-center gap-2">
                                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z">
                                            </path>
                                        </svg>
                                        Menyiarkan Alert...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed bottom-5 right-5 z-50 max-w-md w-full pointer-events-auto" style="display: none;">
                <div :class="{
                    'bg-ant-title border-ant-border text-white': toast.type === 'success',
                    'bg-danger-700 border-danger-600 text-white': toast.type === 'error',
                    'bg-warning-700 border-warning-600 text-white': toast.type === 'warning'
                }" class="p-4 rounded-ant-lg shadow-ant-dropdown border flex items-start gap-3 backdrop-blur-md">
                    <div class="shrink-0 mt-0.5">
                        <template x-if="toast.type === 'success'">
                            <div
                                class="w-8 h-8 rounded-ant bg-success-500/20 text-success-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <div
                                class="w-8 h-8 rounded-ant bg-danger-500/20 text-danger-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <div
                                class="w-8 h-8 rounded-ant bg-warning-500/20 text-warning-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                        </template>
                    </div>

                    <div class="flex-1">
                        <h4 class="text-sm font-bold tracking-tight" x-text="toast.title"></h4>
                        <p class="text-xs text-ant-disabled mt-0.5 leading-relaxed" x-text="toast.message"></p>
                    </div>

                    <button @click="toast.show = false"
                        class="text-ant-disabled hover:text-white p-1 rounded-ant hover:bg-white/10 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
