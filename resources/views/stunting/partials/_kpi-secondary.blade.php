            {{-- SECONDARY KPI: INTERVENSI KLINIS & LAYANAN SPESIFIK (subset dari kasus stunting) --}}
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Intervensi Klinis &
                            Evaluasi Balita Stunting</h3>
                        <span class="text-[11px] text-slate-400 font-normal">(Target 100% Skrining Kasus
                            Stunting)</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3.5">
                    {{-- Hemoglobin Test --}}
                    <div
                        class="bg-white rounded-2xl border border-rose-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-rose-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wide">Test Hemoglobin</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-rose-400 hover:text-rose-700 hover:bg-rose-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-2xl font-black text-rose-800 mt-1.5"
                            x-text="stats.hemoglobinCount ?? '{{ $hemoglobinCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                            <span>Skrining Anemia</span>
                            <span class="font-semibold text-rose-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.hemoglobinCount ?? {{ $hemoglobinCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div>

                    {{-- Mantoux Test --}}
                    <div
                        class="bg-white rounded-2xl border border-sky-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-sky-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-sky-700 uppercase tracking-wide">Test Mantoux</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-sky-400 hover:text-sky-700 hover:bg-sky-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-2xl font-black text-sky-800 mt-1.5"
                            x-text="stats.mantouxCount ?? '{{ $mantouxCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                            <span>Skrining TBC Anak</span>
                            <span class="font-semibold text-sky-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.mantouxCount ?? {{ $mantouxCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div>

                    {{-- Konsul Spesialis Anak --}}
                    <div
                        class="bg-white rounded-2xl border border-indigo-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-indigo-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-indigo-700 uppercase tracking-wide">Konsul Sp.A</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-indigo-400 hover:text-indigo-700 hover:bg-indigo-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-2xl font-black text-indigo-800 mt-1.5"
                            x-text="stats.konsulSpaCount ?? '{{ $konsulSpaCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                            <span>Spesialis Anak</span>
                            <span class="font-semibold text-indigo-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.konsulSpaCount ?? {{ $konsulSpaCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div>

                    {{-- Naik Berat Badan (N) - dari stunting --}}
                    <!-- <div
                        class="bg-white rounded-2xl border border-emerald-100/90 shadow-xs p-3.5 relative overflow-hidden group hover:border-emerald-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wide">Lolos (N)</p>
                            <button type="button" @click="showGuideModal = true"
                                class="p-1 rounded-full text-emerald-400 hover:text-emerald-700 hover:bg-emerald-50 transition cursor-pointer"
                                title="Klik untuk penjelasan detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-2xl font-black text-emerald-800 mt-1.5"
                            x-text="stats.naikBBYCount ?? '{{ $naikBBYCount }}'"></p>
                        <div class="flex items-center justify-between text-[10px] text-slate-500 mt-1">
                            <span>Tren Positif</span>
                            <span class="font-semibold text-emerald-600"
                                x-text="((stats.stuntingTotal || {{ $stuntingTotal }}) > 0 ? Math.round(((stats.naikBBYCount ?? {{ $naikBBYCount }}) / (stats.stuntingTotal || {{ $stuntingTotal }})) * 100) : 0) + '%'"></span>
                        </div>
                    </div> -->
                </div>
            </div>

