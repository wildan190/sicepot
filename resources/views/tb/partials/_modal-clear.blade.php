            <!-- ================= MODAL: CLEAR DATA MASSIVE CONFIRMATION ================= -->
            <div x-show="showClearMassiveModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="if(!isClearingMassive) showClearMassiveModal = false"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-ant-split">
                        <div class="flex items-center justify-between pb-4 border-b border-ant-split">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-ant-lg bg-danger-50 text-danger flex items-center justify-center font-bold shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="ant-section-title">Kosongkan Semua Data TBC</h3>
                                    <p class="text-xs text-ant-secondary">Tindakan pembersihan data masal (Clear Massive)
                                    </p>
                                </div>
                            </div>
                            <button @click="if(!isClearingMassive) showClearMassiveModal = false"
                                class="p-2 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4 text-xs">
                            <div class="p-4 bg-danger-50 border border-danger-100 rounded-ant-lg text-ant-text space-y-2">
                                <div class="flex items-center gap-2 font-bold text-sm text-danger-700">
                                    <svg class="w-4 h-4 text-danger shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <span>Peringatan Keamanan Kritis!</span>
                                </div>
                                <p class="leading-relaxed">
                                    Anda akan menghapus <strong>seluruh data rekam pasien & terduga TBC</strong> secara
                                    masal dari database.
                                    Tindakan ini bersifat <strong>permanen</strong> dan data yang telah dihapus tidak
                                    dapat dikembalikan lagi.
                                </p>
                                <div
                                    class="pt-2 border-t border-danger-100 text-[11px] text-danger-600 flex items-center justify-between font-semibold">
                                    <span>Total rekam data saat ini:</span>
                                    <span class="px-2 py-0.5 bg-danger-100 text-danger-700 rounded-ant-sm font-bold"
                                        x-text="patientsData.total + ' Pasien'"></span>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-ant-text mb-1.5">
                                    Ketik kata <span class="font-bold text-danger tracking-wider">HAPUS</span> di
                                    bawah ini untuk mengonfirmasi:
                                </label>
                                <input type="text" x-model="clearMassiveKeyword" placeholder="Ketik HAPUS"
                                    class="ant-input font-semibold tracking-wider">
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="mt-6 pt-4 border-t border-ant-split flex items-center justify-end gap-2">
                            <button @click="if(!isClearingMassive) showClearMassiveModal = false" type="button"
                                :disabled="isClearingMassive"
                                class="ant-btn ant-btn-default">
                                Batal
                            </button>
                            <button :disabled="clearMassiveKeyword.trim() !== 'HAPUS' || isClearingMassive"
                                @click="executeClearMassive()" type="button"
                                class="ant-btn ant-btn-dangerous">
                                <svg x-show="!isClearingMassive" class="w-3.5 h-3.5 shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span x-show="!isClearingMassive">Ya, Kosongkan Semua Data</span>
                                <span x-show="isClearingMassive" class="flex items-center gap-2">
                                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    Menghapus Semua Data...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
