                <!-- KPI SUMMARY CARDS -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <!-- Card 1: Total Pelacakan (TB-06) -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="ant-statistic-title flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-primary inline-block"></span>
                                Total Pelacakan
                            </span>
                            <span class="ant-tag ant-tag-blue">TB-06</span>
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content"
                                x-text="kpi.total_pelacakan || kpi.total_terduga || kpi.total_all || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-primary-50 text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">Register Terduga TBC (TB-06)</span>
                    </div>

                    <!-- Card 2: Kasus Diperiksa Lab/TCM -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="ant-statistic-title flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-warning inline-block"></span>
                                Diperiksa TCM/Lab
                            </span>
                            <span class="ant-tag ant-tag-gold">TCM</span>
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-warning-600"
                                x-text="kpi.total_diperiksa_tcm || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-warning-50 text-warning-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2"
                            x-text="(kpi.total_diperiksa_tcm && kpi.total_terduga ? Math.round((kpi.total_diperiksa_tcm / kpi.total_terduga) * 100) : 0) + '% dari total terduga dilacak'"></span>
                    </div>

                    <!-- Card 3: Pasien Sedang Pengobatan (Merah / TB-03) -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="ant-statistic-title flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-danger inline-block"></span>
                                Pasien Pengobatan OAT
                            </span>
                            <span class="ant-tag ant-tag-red">TB-03</span>
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-danger"
                                x-text="kpi.total_sedang_pengobatan">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-danger-50 text-danger">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2"
                            x-text="(kpi.total_terkonfirmasi || 0) + ' Terkonfirmasi Bakteriologis/Klinis'"></span>
                    </div>

                    <!-- Card 4: Investigasi Kontak (Hijau / TB-16K) -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="ant-statistic-title flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-success inline-block"></span>
                                Investigasi Kontak
                            </span>
                            <span class="ant-tag ant-tag-green">TB-16K</span>
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-success-600" x-text="kpi.total_kontak || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-success-50 text-success-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2"
                            x-text="(kpi.total_indeks_ik || 0) + ' Kasus Indeks · ' + (kpi.total_kontak_serumah || 0) + ' Serumah'"></span>
                    </div>

                    <!-- Card 5: Pasien Sembuh -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Sembuh /
                            Lengkap</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-success-600"
                                x-text="kpi.total_sembuh + kpi.total_lengkap">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-success-50 text-success-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2"
                            x-text="kpi.total_sembuh + ' Sembuh, ' + kpi.total_lengkap + ' Lengkap'">0 Sembuh</span>
                    </div>

                    <!-- Card 6: Putus Berobat -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Lost to Follow
                            Up</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-danger" x-text="kpi.total_putus">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-danger-50 text-danger">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">Putus berobat</span>
                    </div>
                </div>
