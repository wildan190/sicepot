                <!-- PERINGATAN H-1 PERSALINAN (SIAP SIAGA / SIRINE) -->
                <template x-if="imminentDeliveries && imminentDeliveries.length > 0">
                    <div
                        class="bg-gradient-to-r from-rose-500 via-red-500 to-pink-600 rounded-2xl p-4 sm:p-5 text-white shadow-lg border border-red-400 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 animate-pulse">
                        <div class="flex items-center gap-3.5">
                            <div class="p-3 bg-white/20 backdrop-blur-md rounded-2xl shrink-0">
                                <svg class="w-7 h-7 text-white animate-bounce" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full bg-white text-rose-700 text-xs font-black uppercase tracking-wider">Perhatian
                                        Khusus H-14</span>
                                    <span class="text-xs text-rose-100 font-medium"
                                        x-text="imminentDeliveries.length + ' Ibu Hamil HPL dalam 2 Minggu'"></span>
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold mt-0.5 tracking-tight">Peringatan Hari
                                    Perkiraan Lahir (HPL) Tinggal 2 Minggu!</h3>
                                <p class="text-xs text-rose-100 mt-0.5">Segera lakukan persiapan pertolongan persalinan,
                                    transportasi rujukan, dan kesiapan donor darah (P4K).</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                            <button @click="showH1ModalAlert()" type="button"
                                class="w-full md:w-auto px-4 py-2.5 bg-white hover:bg-rose-50 text-rose-700 text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <span>Lihat Daftar & Bunyikan Sirine</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- PERINGATAN H-30 PERSALINAN (SEBULAN SEBELUM HPL) -->
                <template x-if="upcomingDeliveries && upcomingDeliveries.length > 0">
                    <div
                        class="bg-gradient-to-r from-amber-400 via-orange-400 to-yellow-500 rounded-2xl p-4 sm:p-5 text-white shadow-lg border border-amber-300 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="p-3 bg-white/20 backdrop-blur-md rounded-2xl shrink-0">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-2.5 py-0.5 rounded-full bg-white text-amber-700 text-xs font-black uppercase tracking-wider">Persiapan
                                        H-30</span>
                                    <span class="text-xs text-amber-100 font-medium"
                                        x-text="upcomingDeliveries.length + ' Ibu Hamil HPL dalam 30 Hari'"></span>
                                </div>
                                <h3 class="text-base sm:text-lg font-extrabold mt-0.5 tracking-tight">Persiapan
                                    Persalinan — HPL Kurang dari 30 Hari!</h3>
                                <p class="text-xs text-amber-100 mt-0.5">Pastikan P4K sudah lengkap: calon donor darah,
                                    tabungan persalinan, transportasi, dan pendamping siap.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                            <button @click="showH30ModalAlert()" type="button"
                                class="w-full md:w-auto px-4 py-2.5 bg-white hover:bg-amber-50 text-amber-700 text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <span>Lihat Daftar Ibu Hamil</span>
                            </button>
                        </div>
                    </div>
                </template>

