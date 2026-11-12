                <!-- PERINGATAN H-1 PERSALINAN (SIAP SIAGA / SIRINE) -->
                <template x-if="imminentDeliveries && imminentDeliveries.length > 0">
                    <div
                        class="bg-danger-50 rounded-ant-lg p-4 sm:p-5 border border-danger-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="p-2.5 bg-danger text-white rounded-ant shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="ant-tag ant-tag-red font-medium uppercase">Siaga 1 Persalinan (H-1)</span>
                                    <span class="text-xs text-danger-600 font-medium"
                                        x-text="imminentDeliveries.length + ' Ibu Hamil Siaga Hari Ini / Besok'"></span>
                                </div>
                                <h3 class="text-base sm:text-lg font-semibold mt-1 tracking-tight">Peringatan Hari
                                    Perkiraan Lahir (HPL) Hari Ini & Besok!</h3>
                                <p class="text-xs text-ant-text mt-0.5">Waktu persalinan sangat dekat. Segera siapkan tim penolong,
                                    tempat persalinan, transportasi rujukan, dan kesiapan donor darah.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                            <button @click="showH1ModalAlert()" type="button"
                                class="ant-btn ant-btn-dangerous w-full md:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
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
                        class="bg-warning-50 rounded-ant-lg p-4 sm:p-5 border border-warning-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="p-2.5 bg-warning text-white rounded-ant shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="ant-tag ant-tag-gold font-medium uppercase">Persiapan H-30</span>
                                    <span class="text-xs text-warning-600 font-medium"
                                        x-text="upcomingDeliveries.length + ' Ibu Hamil HPL dalam 30 Hari'"></span>
                                </div>
                                <h3 class="text-base sm:text-lg font-semibold mt-1 tracking-tight">Persiapan
                                    Persalinan — HPL Kurang dari 30 Hari!</h3>
                                <p class="text-xs text-ant-text mt-0.5">Pastikan P4K sudah lengkap: calon donor darah,
                                    tabungan persalinan, transportasi, dan pendamping siap.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                            <button @click="showH30ModalAlert()" type="button"
                                class="ant-btn ant-btn-default w-full md:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
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
