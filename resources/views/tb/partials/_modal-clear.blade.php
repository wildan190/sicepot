            <!-- ================= MODAL: CLEAR DATA MASSIVE CONFIRMATION ================= -->
            <div x-show="showClearMassiveModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="if(!isClearingMassive) showClearMassiveModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                    <div
                        class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl z-10 border border-rose-100">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Kosongkan Semua Data TBC</h3>
                                    <p class="text-xs text-slate-500">Tindakan pembersihan data masal (Clear Massive)
                                    </p>
                                </div>
                            </div>
                            <button @click="if(!isClearingMassive) showClearMassiveModal = false"
                                class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4 text-xs">
                            <div class="p-4 bg-rose-50/80 border border-rose-200 rounded-2xl text-rose-800 space-y-2">
                                <div class="flex items-center gap-2 font-bold text-sm text-rose-900">
                                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor"
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
                                    class="pt-2 border-t border-rose-200/60 text-[11px] text-rose-700 flex items-center justify-between font-semibold">
                                    <span>Total rekam data saat ini:</span>
                                    <span class="px-2 py-0.5 bg-rose-200 text-rose-900 rounded-md font-bold"
                                        x-text="patientsData.total + ' Pasien'"></span>
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1.5">
                                    Ketik kata <span class="font-bold text-rose-600 tracking-wider">HAPUS</span> di
                                    bawah ini untuk mengonfirmasi:
                                </label>
                                <input type="text" x-model="clearMassiveKeyword" placeholder="Ketik HAPUS"
                                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500 font-semibold tracking-wider transition-colors">
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button @click="if(!isClearingMassive) showClearMassiveModal = false" type="button"
                                :disabled="isClearingMassive"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer disabled:opacity-50">
                                Batal
                            </button>
                            <button :disabled="clearMassiveKeyword.trim() !== 'HAPUS' || isClearingMassive"
                                @click="executeClearMassive()" type="button"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-1.5 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
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

