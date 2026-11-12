            {{-- SECONDARY KPI: INTERVENSI KLINIS & LAYANAN SPESIFIK (subset dari kasus stunting) --}}
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <h3 class="text-xs font-bold text-ant-title uppercase tracking-wider">Intervensi Klinis &
                            Evaluasi Balita Stunting</h3>
                        <span class="text-[11px] text-ant-secondary font-normal">(Target 100% Skrining Kasus
                            Stunting)</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3.5">
                    {{-- Hemoglobin Test --}}
                    <div
                        class="bg-white rounded-ant-lg border border-danger-100 shadow-ant-card p-3.5 relative overflow-hidden group hover:border-danger-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-danger-700 uppercase tracking-wide">Test Hemoglobin</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-danger-400 hover:text-danger-700 hover:bg-danger-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1.5 text-danger-700"
                            x-text="stats.hemoglobinCount ?? '{{ $hemoglobinCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-ant-secondary mt-1">
                            <span>Skrining Anemia</span>
                            <span class="font-semibold text-danger-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.hemoglobinCount ?? {{ $hemoglobinCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div>

                    {{-- Mantoux Test --}}
                    <div
                        class="bg-white rounded-ant-lg border border-primary-100 shadow-ant-card p-3.5 relative overflow-hidden group hover:border-primary-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-primary-700 uppercase tracking-wide">Test Mantoux</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-primary-400 hover:text-primary-700 hover:bg-primary-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1.5 text-primary-700"
                            x-text="stats.mantouxCount ?? '{{ $mantouxCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-ant-secondary mt-1">
                            <span>Skrining TBC Anak</span>
                            <span class="font-semibold text-primary-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.mantouxCount ?? {{ $mantouxCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div>

                    {{-- Konsul Spesialis Anak --}}
                    <div
                        class="bg-white rounded-ant-lg border border-primary-100 shadow-ant-card p-3.5 relative overflow-hidden group hover:border-primary-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-primary-700 uppercase tracking-wide">Konsul Sp.A</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-primary-400 hover:text-primary-700 hover:bg-primary-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1.5 text-primary-700"
                            x-text="stats.konsulSpaCount ?? '{{ $konsulSpaCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-ant-secondary mt-1">
                            <span>Spesialis Anak</span>
                            <span class="font-semibold text-primary-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.konsulSpaCount ?? {{ $konsulSpaCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div>

                    {{-- Naik Berat Badan (N) - dari stunting --}}
                    <!-- <div
                        class="bg-white rounded-ant-lg border border-success-100 shadow-ant-card p-3.5 relative overflow-hidden group hover:border-success-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-success-700 uppercase tracking-wide">Lolos (N)</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-success-400 hover:text-success-700 hover:bg-success-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="ant-statistic-content mt-1.5 text-success-700"
                            x-text="stats.naikBBYCount ?? '{{ $naikBBYCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-ant-secondary mt-1">
                            <span>Tren Positif</span>
                            <span class="font-semibold text-success-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.naikBBYCount ?? {{ $naikBBYCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div> -->
                </div>
            </div>
