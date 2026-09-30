                <!-- KPI SUMMARY CARDS -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3.5">

                    <!-- Card 1: Total Ibu Hamil -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Ibu
                            Hamil</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-slate-900" x-text="kpi.total_all">0</span>
                            <span class="p-1.5 rounded-lg bg-pink-50 text-pink-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2">Terdaftar di Fasyankes</span>
                    </div>

                    <!-- Card 2: Total Ibu Hamil Risiko -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-rose-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Total Ibu Hamil
                            Risiko</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-rose-700" x-text="kpi.total_risiko || 0">0</span>
                            <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-rose-400 mt-2">Ada Faktor Risiko</span>
                    </div>

                    <!-- Card 3: Bekas Sectio Cesaria (BSC) -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-blue-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Bekas Sesar
                            (BSC)</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-blue-700" x-text="kpi.total_bsc || 0">0</span>
                            <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-blue-400 mt-2">Riwayat SC / BSC</span>
                    </div>

                    <!-- Card 4: Darah Tinggi (HDK) -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-violet-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-violet-700 uppercase tracking-wider">Darah Tinggi
                            (HDK)</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-violet-700" x-text="kpi.total_hdk || 0">0</span>
                            <span class="p-1.5 rounded-lg bg-violet-50 text-violet-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-violet-400 mt-2">Hipertensi / Pre-eklampsi</span>
                    </div>

                    <!-- Card 5: Anemia / KEK -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-amber-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Anemia / KEK</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-amber-700" x-text="kpi.total_anemia_kek || 0">0</span>
                            <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-amber-400 mt-2">Hb Rendah & LiLA &lt; 23.5cm</span>
                    </div>

                    <!-- Card 6: Penyakit Penyerta -->
                    <div
                        class="bg-white p-4 rounded-2xl shadow-xs border border-teal-200/80 flex flex-col justify-between">
                        <span class="text-xs font-semibold text-teal-700 uppercase tracking-wider">Lain-lain</span>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span class="text-2xl font-bold text-teal-700" x-text="kpi.total_penyakit || 0">0</span>
                            <span class="p-1.5 rounded-lg bg-teal-50 text-teal-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-teal-400 mt-2">DM, Asma, Tiroid, dll</span>
                    </div>

                    <!-- Card 7: Peringatan Persalinan H-30 (1 Bulan Sebelum) -->
                    <div @click="showH1ModalAlert()"
                        :class="kpi.total_h1 > 0 ? 'bg-rose-50/70 border-rose-300 ring-2 ring-rose-300 cursor-pointer hover:bg-rose-100/70' : 'bg-white border-slate-200/80'"
                        class="p-4 rounded-2xl shadow-xs border flex flex-col justify-between transition-all">
                        <div class="flex items-center justify-between">
                            <span :class="kpi.total_h1 > 0 ? 'text-rose-700 font-bold' : 'text-slate-500 font-semibold'"
                                class="text-xs uppercase tracking-wider">Persalinan 1 Bln</span>
                            <template x-if="kpi.total_h1 > 0">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                                </span>
                            </template>
                        </div>
                        <div class="mt-2 flex items-baseline justify-between">
                            <span :class="kpi.total_h1 > 0 ? 'text-rose-700' : 'text-slate-900'"
                                class="text-2xl font-bold" x-text="kpi.total_h1 || 0">0</span>
                            <span
                                :class="kpi.total_h1 > 0 ? 'bg-rose-200/60 text-rose-700' : 'bg-slate-100 text-slate-500'"
                                class="p-1.5 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-2"
                            x-text="kpi.total_h1 > 0 ? 'HPL dalam 30 hari ke depan' : 'Tidak ada dalam 30 hari'"></span>
                    </div>

                </div>

