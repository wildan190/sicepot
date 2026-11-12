            {{-- KPI CARDS: STATUS PERTUMBUHAN & STUNTING --}}
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        <h3 class="text-xs font-bold text-ant-title uppercase tracking-wider">Status Stunting &
                            Pertumbuhan</h3>
                        <span class="text-[11px] text-ant-secondary font-normal">(Sinkronisasi Realtime &
                            Create/Update)</span>
                    </div>
                    <button @click="showGuideModal = true" type="button"
                        class="ant-btn ant-btn-text ant-btn-sm text-success">
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
                        class="bg-white rounded-ant-lg border border-ant-split shadow-ant-card p-4 col-span-1 relative group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="ant-statistic-title font-semibold uppercase tracking-wide">Total Balita</p>
                                <span class="text-[10px] text-success-600 font-medium">(Bulan 1 - 12)</span>
                            </div>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-ant-secondary hover:text-success-600 hover:bg-ant-split transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1"
                            x-text="stats.totalBalitaAllMonths ?? '{{ $totalBalitaAllMonths }}'"></p>
                        <p class="text-xs text-ant-secondary mt-1"
                            x-text="'(' + (stats.totalPengukuranAllMonths ?? '{{ $totalPengukuranAllMonths }}') + ' total rekaman)'">
                        </p>
                    </div> -->
                    {{-- Kasus Stunting Aktif (Pendek + Sangat Pendek) --}}
                    <div
                        class="bg-danger-50 rounded-ant-lg border border-danger-100 shadow-ant-card p-4 col-span-1 relative group">
                        <div class="flex items-center justify-between">
                            <p class="ant-statistic-title font-semibold uppercase tracking-wide text-danger">Kasus Stunting</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-danger-400 hover:text-danger-600 hover:bg-danger-100 transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1 text-danger"
                            x-text="stats.stuntingTotal ?? '{{ $stuntingTotal }}'"></p>
                        <p class="text-xs text-danger-400 mt-1">Pendek + Sangat Pendek</p>
                    </div>
                    {{-- Sangat Pendek --}}
                    <div
                        class="bg-danger-50 rounded-ant-lg border border-danger-200 shadow-ant-card p-4 relative group">
                        <div class="flex items-center justify-between">
                            <p class="ant-statistic-title font-semibold uppercase tracking-wide text-danger">Sangat Pendek</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-danger-500 hover:text-danger-700 hover:bg-danger-100 transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1 text-danger"
                            x-text="stats.sangatPendekCount ?? '{{ $sangatPendekCount }}'"></p>
                        <p class="text-xs text-danger-500 mt-1">TB/U: Sangat Pendek</p>
                    </div>
                    {{-- Pendek --}}
                    <div
                        class="bg-warning-50 rounded-ant-lg border border-warning-100 shadow-ant-card p-4 relative group">
                        <div class="flex items-center justify-between">
                            <p class="ant-statistic-title font-semibold uppercase tracking-wide text-warning-600">Pendek</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-warning-500 hover:text-warning-700 hover:bg-warning-100 transition cursor-pointer"
                                title="Klik untuk penjelasan detail indikator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1 text-warning-600"
                            x-text="stats.pendekCount ?? '{{ $pendekCount }}'"></p>
                        <p class="text-xs text-warning-500 mt-1">TB/U: Pendek</p>
                    </div>
                </div>
            </div>
