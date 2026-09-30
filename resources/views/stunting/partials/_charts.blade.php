            {{-- CHARTS SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Donut Chart: Status TB/U --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Status TB/U (Tinggi Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="tbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Normal</span><span
                                class="font-semibold text-slate-700">{{ $normalCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>Pendek</span><span
                                class="font-semibold text-slate-700">{{ $pendekCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>Sangat
                                Pendek</span><span class="font-semibold text-slate-700">{{ $sangatPendekCount }}</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: Status BB/U --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Status BB/U (Berat Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="bbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>Gizi
                                Baik</span><span class="font-semibold text-slate-700">{{ $bbuGiziBaikCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>Gizi
                                Kurang</span><span class="font-semibold text-slate-700">{{ $bbuKurangCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>Gizi Sangat
                                Kurang</span><span
                                class="font-semibold text-slate-700">{{ $bbuSangatKurangCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span>Risiko
                                Lebih</span><span class="font-semibold text-slate-700">{{ $bbuRisikoLebihCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-purple-400 inline-block"></span>Gizi
                                Lebih/Obesitas</span><span
                                class="font-semibold text-slate-700">{{ $bbuLebihCount + $bbuObesitasCount }}</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: Jenis Kelamin --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Sebaran Jenis Kelamin</h3>
                    <div class="relative flex justify-center">
                        <canvas id="genderChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>Laki-laki</span><span
                                class="font-semibold text-slate-700">{{ $lakiLaki }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-pink-400 inline-block"></span>Perempuan</span><span
                                class="font-semibold text-slate-700">{{ $perempuan }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Monthly Trend Chart (Full Width) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-700">Tren Kasus Stunting Bulanan</h3>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Candlestick / OHLC
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Dinamika volume total pengukuran (High) hingga sebaran
                            kasus stunting (Low/Close) per bulan</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-500">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-emerald-500 inline-block border border-emerald-600"></span>
                            <span>Tren Terkendali (Stunting Rendah)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-rose-500 inline-block border border-rose-600"></span>
                            <span>Waspada (Stunting Tinggi)</span>
                        </div>
                    </div>
                </div>
                <div class="relative h-80">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            {{-- Per-Desa Bar Chart (Full Width) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700">Distribusi Stunting per Desa/Kelurahan</h3>
                        <p class="text-xs text-slate-400">Sebaran kategori TB/U per wilayah desa/kelurahan</p>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="desaChart"></canvas>
                </div>
            </div>

