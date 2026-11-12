                <!-- KPI SUMMARY CARDS -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3.5">

                    <!-- Card 1: Total Ibu Hamil -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Total Ibu
                            Hamil</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content" x-text="kpi.total_all">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-primary-50 text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">Terdaftar di Fasyankes</span>
                    </div>

                    <!-- Card 2: Total Ibu Hamil Risiko -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Total Ibu Hamil
                            Risiko</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-danger" x-text="kpi.total_risiko || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-danger-50 text-danger">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">Ada Faktor Risiko</span>
                    </div>

                    <!-- Card 3: Bekas Sectio Cesaria (BSC) -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Bekas Sesar
                            (BSC)</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content" x-text="kpi.total_bsc || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-primary-50 text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">Riwayat SC / BSC</span>
                    </div>

                    <!-- Card 4: Darah Tinggi (HDK) -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Darah Tinggi
                            (HDK)</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-warning-600" x-text="kpi.total_hdk || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-warning-50 text-warning-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">Hipertensi / Pre-eklampsi</span>
                    </div>

                    <!-- Card 5: Anemia / KEK -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Anemia / KEK</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content text-warning-600" x-text="kpi.total_anemia_kek || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-warning-50 text-warning-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">Hb Rendah &amp; LiLA &lt; 23.5cm</span>
                    </div>

                    <!-- Card 6: Penyakit Penyerta -->
                    <div
                        class="bg-white p-4 rounded-ant-lg border border-ant-split shadow-ant-card flex flex-col justify-between">
                        <span class="ant-statistic-title">Lain-lain</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="ant-statistic-content" x-text="kpi.total_penyakit || 0">0</span>
                            <span class="p-1.5 rounded-ant-sm bg-ant-split text-ant-secondary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2">DM, Asma, Tiroid, dll</span>
                    </div>

                    <!-- Card 7: Peringatan Persalinan H-1 (Sirine Siaga) -->
                    <div @click="showH1ModalAlert()"
                        :class="kpi.total_h1 > 0 ? 'bg-danger-50 border-danger-200 cursor-pointer hover:bg-danger-100' : 'bg-white border-ant-split'"
                        class="p-4 rounded-ant-lg border shadow-ant-card flex flex-col justify-between transition-all">
                        <div class="flex items-center justify-between">
                            <span :class="kpi.total_h1 > 0 ? 'text-danger font-semibold' : 'text-ant-secondary'"
                                class="ant-statistic-title">Siaga H-1 Persalinan</span>
                            <template x-if="kpi.total_h1 > 0">
                                <span class="relative flex h-2 w-2">
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-danger"></span>
                                </span>
                            </template>
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span :class="kpi.total_h1 > 0 ? 'text-danger' : 'text-ant-title'"
                                class="ant-statistic-content" x-text="kpi.total_h1 || 0">0</span>
                            <span
                                :class="kpi.total_h1 > 0 ? 'bg-danger-100 text-danger' : 'bg-ant-split text-ant-secondary'"
                                class="p-1.5 rounded-ant-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-ant-secondary mt-2"
                            x-text="kpi.total_h1 > 0 ? 'HPL Hari Ini / Besok' : 'Tidak ada pasien H-1'"></span>
                    </div>

                </div>
