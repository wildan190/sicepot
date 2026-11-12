            {{-- CHARTS SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Donut Chart: Status TB/U --}}
                <div class="bg-white rounded-ant-lg border border-ant-split shadow-ant-card p-5">
                    <h3 class="text-sm font-semibold text-ant-title mb-4">Status TB/U (Tinggi Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="tbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-success inline-block"></span>Normal</span><span
                                class="font-semibold text-ant-title">{{ $normalCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-warning inline-block"></span>Pendek</span><span
                                class="font-semibold text-ant-title">{{ $pendekCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-danger inline-block"></span>Sangat
                                Pendek</span><span class="font-semibold text-ant-title">{{ $sangatPendekCount }}</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: Status BB/U --}}
                <div class="bg-white rounded-ant-lg border border-ant-split shadow-ant-card p-5">
                    <h3 class="text-sm font-semibold text-ant-title mb-4">Status BB/U (Berat Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="bbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-success inline-block"></span>Gizi
                                Baik</span><span class="font-semibold text-ant-title">{{ $bbuGiziBaikCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-warning inline-block"></span>Gizi
                                Kurang</span><span class="font-semibold text-ant-title">{{ $bbuKurangCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-danger inline-block"></span>Gizi Sangat
                                Kurang</span><span
                                class="font-semibold text-ant-title">{{ $bbuSangatKurangCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-primary-400 inline-block"></span>Risiko
                                Lebih</span><span class="font-semibold text-ant-title">{{ $bbuRisikoLebihCount }}</span>
                        </div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-[#722ed1] inline-block"></span>Gizi
                                Lebih/Obesitas</span><span
                                class="font-semibold text-ant-title">{{ $bbuLebihCount + $bbuObesitasCount }}</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: Jenis Kelamin --}}
                <div class="bg-white rounded-ant-lg border border-ant-split shadow-ant-card p-5">
                    <h3 class="text-sm font-semibold text-ant-title mb-4">Sebaran Jenis Kelamin</h3>
                    <div class="relative flex justify-center">
                        <canvas id="genderChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-primary inline-block"></span>Laki-laki</span><span
                                class="font-semibold text-ant-title">{{ $lakiLaki }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded-full bg-[#eb2f96] inline-block"></span>Perempuan</span><span
                                class="font-semibold text-ant-title">{{ $perempuan }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Monthly Trend Chart (Full Width) --}}
            <div class="bg-white rounded-ant-lg border border-ant-split shadow-ant-card p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-semibold text-ant-title">Tren Kasus Stunting Bulanan</h3>
                            <span
                                class="ant-tag ant-tag-green">
                                <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                Candlestick / OHLC
                            </span>
                        </div>
                        <p class="text-xs text-ant-secondary mt-0.5">Dinamika volume total pengukuran (High) hingga sebaran
                            kasus stunting (Low/Close) per bulan</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-ant-secondary">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-success inline-block border border-success-600"></span>
                            <span>Tren Terkendali (Stunting Rendah)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-danger inline-block border border-danger-600"></span>
                            <span>Waspada (Stunting Tinggi)</span>
                        </div>
                    </div>
                </div>
                <div class="relative h-80">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            {{-- Per-Desa Bar Chart (Full Width) --}}
            <div class="bg-white rounded-ant-lg border border-ant-split shadow-ant-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-ant-title">Distribusi Stunting per Desa/Kelurahan</h3>
                        <p class="text-xs text-ant-secondary">Sebaran kategori TB/U per wilayah desa/kelurahan</p>
                    </div>
                </div>
                <div class="relative h-72">
                    <canvas id="desaChart"></canvas>
                </div>
            </div>
