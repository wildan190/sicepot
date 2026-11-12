            <!-- ================= MODAL: CLEAR ALL DATA KONFIRMASI ================= -->
            <div x-show="showClearAllModal" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto"
                style="display: none;">
                <div class="min-h-screen px-4 text-center flex items-center justify-center">
                    <div @click="showClearAllModal = false; clearConfirmText = ''"
                        class="fixed inset-0 bg-black/45 transition-opacity"></div>
                    <div
                        class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white shadow-ant-dropdown rounded-ant-lg z-10 border border-danger-100"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">

                        <!-- Header -->
                        <div class="flex items-center justify-between pb-4 border-b border-danger-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-ant-lg bg-danger-50 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="ant-section-title">Hapus Seluruh Data ANC</h3>
                                    <p class="text-xs text-danger font-medium">Tindakan ini tidak dapat dibatalkan!</p>
                                </div>
                            </div>
                            <button @click="showClearAllModal = false; clearConfirmText = ''" type="button"
                                class="p-1.5 text-ant-secondary hover:text-ant-title rounded-ant hover:bg-ant-split transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="py-5 space-y-4">
                            <div class="p-4 rounded-ant-lg bg-danger-50 border border-danger-100">
                                <p class="text-sm text-danger-600 font-medium leading-relaxed">
                                    ⚠️ Seluruh data ibu hamil ANC akan <strong>dihapus permanen</strong> dari
                                    database. Pastikan Anda telah melakukan backup sebelum melanjutkan.
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ant-text mb-1.5">
                                    Ketik <span class="font-mono font-bold text-danger bg-danger-50 px-1.5 py-0.5 rounded-ant-sm">HAPUS SEMUA</span> untuk konfirmasi:
                                </label>
                                <input type="text" x-model="clearConfirmText"
                                    placeholder="HAPUS SEMUA"
                                    @keydown.enter="clearAllData()"
                                    class="ant-input font-mono"
                                    :class="clearConfirmText === 'HAPUS SEMUA' ? '!border-danger !bg-danger-50' : ''" />
                            </div>
                        </div>

                        <!-- Footer Buttons -->
                        <div class="flex justify-end gap-2.5 pt-3 border-t border-ant-split">
                            <button @click="showClearAllModal = false; clearConfirmText = ''" type="button"
                                class="ant-btn ant-btn-default">
                                Batal
                            </button>
                            <button @click="clearAllData()" type="button"
                                :disabled="clearConfirmText !== 'HAPUS SEMUA' || isClearingAll"
                                class="ant-btn ant-btn-dangerous">
                                <template x-if="isClearingAll">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </template>
                                <span x-text="isClearingAll ? 'Menghapus...' : 'Hapus Semua Data'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
