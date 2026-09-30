            {{-- KPI CARDS: STATUS PERTUMBUHAN & STUNTING --}}
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status Stunting &
                            Pertumbuhan</h3>
                        <span class="text-[11px] text-slate-400 font-normal">(Sinkronisasi Realtime &
                            Create/Update)</span>
                    </div>
                    <button @click="showGuideModal = true" type="button"
                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Penjelasan Indikator</span>
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Total Balita (Bulan 1 s/d 12 - Tanpa Filter Bulan) --}}
                    <!-- <div
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 col-span-1 relative group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-slate-700 uppercase tracking-wide">Total Balita</p>
                                <span class="text-[10px] text-emerald-600 font-medium">(Bulan 1 - 12)</span>
                            </div>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-slate-400 hover:text-emerald-600 hover:bg-slate-100 transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-3xl font-bold text-slate-800 mt-1"
                            x-text="stats.totalBalitaAllMonths ?? '{{ $totalBalitaAllMonths }}'"></p>
                        <p class="text-xs text-slate-400 mt-1"
                            x-text="'(' + (stats.totalPengukuranAllMonths ?? '{{ $totalPengukuranAllMonths }}') + ' total rekaman)'">
                        </p>
                    </div> -->
                    {{-- Kasus Stunting Aktif (Pendek + Sangat Pendek) --}}
                    <div
                        class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl border border-red-100 shadow-xs p-4 col-span-1 relative group">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-red-500 uppercase tracking-wide">Kasus Stunting</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-red-400 hover:text-red-700 hover:bg-red-100/50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-3xl font-bold text-red-600 mt-1"
                            x-text="stats.stuntingTotal ?? '{{ $stuntingTotal }}'"></p>
                        <p class="text-xs text-red-400 mt-1">Pendek + Sangat Pendek</p>
                    </div>
                    {{-- Sangat Pendek --}}
                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl border border-red-200 shadow-xs p-4 relative group">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Sangat Pendek</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-red-500 hover:text-red-800 hover:bg-red-200/50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-3xl font-bold text-red-700 mt-1"
                            x-text="stats.sangatPendekCount ?? '{{ $sangatPendekCount }}'"></p>
                        <p class="text-xs text-red-500 mt-1">TB/U: Sangat Pendek</p>
                    </div>
                    {{-- Pendek --}}
                    <div
                        class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl border border-orange-100 shadow-xs p-4 relative group">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-orange-500 uppercase tracking-wide">Pendek</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-orange-400 hover:text-orange-700 hover:bg-orange-100/50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-3xl font-bold text-orange-600 mt-1"
                            x-text="stats.pendekCount ?? '{{ $pendekCount }}'"></p>
                        <p class="text-xs text-orange-400 mt-1">TB/U: Pendek</p>
                    </div>
                </div>
            </div>

