        <!-- ================= MODAL PANDUAN INDIKATOR & UPSET ================= -->
        <div x-show="showGuideModal" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/45 backdrop-blur-xs"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="showGuideModal = false"
                class="bg-white rounded-ant-lg shadow-ant-dropdown max-w-2xl w-full border border-ant-split overflow-hidden transform transition-all my-8">
                {{-- Header --}}
                <div
                    class="px-6 py-4 bg-primary text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 bg-white/10 rounded-ant">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="font-bold text-base tracking-tight">Panduan Indikator & Sistem Real-Time</h3>
                            <p class="text-xs text-primary-50">Penjelasan kartu data dan mekanisme Create or Update
                                (Upsert)</p>
                        </div>
                    </div>
                    <button @click="showGuideModal = false"
                        class="p-1 rounded-ant text-white/80 hover:text-white hover:bg-white/10 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body content --}}
                <div class="p-6 max-h-[75vh] overflow-y-auto space-y-5 text-xs text-ant-text leading-relaxed">
                    {{-- Alert Real-time info --}}
                    <div class="ant-alert ant-alert-success">
                        <span class="p-1.5 rounded-ant bg-success-100 text-success-700 shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <div>
                            <h4 class="font-bold text-success-700 text-xs">Mekanisme Real-Time (Create or Update)</h4>
                            <p class="text-success-700 mt-0.5">
                                Semua kartu angka di atas <strong>langsung sinkron otomatis</strong> saat Anda menambah
                                data balita baru (<em>Create</em>) maupun mengedit data lama (<em>Update</em>). Sistem
                                mencocokkan data melalui <strong>NIK</strong> atau kombinasi <strong>Nama +
                                    Desa</strong> sehingga tidak terjadi data duplikat.
                            </p>
                        </div>
                    </div>

                    {{-- Seksi 1: Status Stunting --}}
                    <div>
                        <h4
                            class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-danger"></span>
                            1. Kartu Status Pertumbuhan & Stunting
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div class="p-3 bg-ant-layout border border-ant-split rounded-ant-lg">
                                <span class="font-bold text-ant-title block text-[11px]">Total Balita</span>
                                <p class="text-ant-secondary mt-0.5">Jumlah balita unik (berdasarkan NIK) yang tercatat
                                    dalam cakupan wilayah puskesmas.</p>
                            </div>
                            <div class="p-3 bg-danger-50 border border-danger-100 rounded-ant-lg">
                                <span class="font-bold text-danger-700 block text-[11px]">Kasus Stunting (Total)</span>
                                <p class="text-danger-600 mt-0.5">Akumulasi balita kategori <strong>Pendek</strong> +
                                    <strong>Sangat Pendek</strong>. Angka berkurang otomatis saat balita di-update
                                    menjadi <em>Normal</em>.
                                </p>
                            </div>
                            <div class="p-3 bg-danger-50 border border-danger-100 rounded-ant-lg">
                                <span class="font-bold text-danger-700 block text-[11px]">Sangat Pendek</span>
                                <p class="text-danger-600 mt-0.5">Balita dengan nilai Z-Score TB/U &lt; -3 SD
                                    (<em>Severely Stunted</em>).</p>
                            </div>
                            <div class="p-3 bg-warning-50 border border-warning-100 rounded-ant-lg">
                                <span class="font-bold text-warning-700 block text-[11px]">Pendek</span>
                                <p class="text-warning-600 mt-0.5">Balita dengan Z-Score TB/U antara -3 SD hingga &lt;
                                    -2 SD (<em>Stunted</em>).</p>
                            </div>
                        </div>
                    </div>

                    {{-- Seksi 2: Intervensi Klinis --}}
                    <div>
                        <h4
                            class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                            2. Kartu Intervensi & Skrining Klinis (Balita Stunting)
                        </h4>
                        <p class="text-[11px] text-ant-secondary mb-2">Persentase (%) dihitung dari: <em>(Balita stunting
                                terintervensi / Total kasus stunting) &times; 100%</em>.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <div class="p-3 bg-ant-layout border border-ant-split rounded-ant-lg">
                                <span class="font-bold text-ant-title block text-[11px]">Test Hemoglobin</span>
                                <p class="text-ant-secondary mt-0.5">Skrining laboratorium untuk mendeteksi anemia
                                    defisiensi besi pada balita stunting.</p>
                            </div>
                            <div class="p-3 bg-ant-layout border border-ant-split rounded-ant-lg">
                                <span class="font-bold text-ant-title block text-[11px]">Test Mantoux</span>
                                <p class="text-ant-secondary mt-0.5">Skrining infeksi Tuberkulosis (TBC) anak melalui uji
                                    tuberkulin.</p>
                            </div>
                            <div class="p-3 bg-ant-layout border border-ant-split rounded-ant-lg">
                                <span class="font-bold text-ant-title block text-[11px]">Konsul Sp.A</span>
                                <p class="text-ant-secondary mt-0.5">Rujukan konsultasi ke Dokter Spesialis Anak untuk
                                    identifikasi penyakit penyerta (<em>red flags</em>).</p>
                            </div>
                            <div class="p-3 bg-success-50 border border-success-100 rounded-ant-lg">
                                <span class="font-bold text-success-700 block text-[11px]">Lolos (N) / Tren
                                    Positif</span>
                                <p class="text-success-600 mt-0.5">Balita stunting yang berat badannya <strong>Naik
                                        (N)</strong> pada penimbangan terakhir, menandakan respons positif terapi gizi.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Seksi 3: Grafik Candle --}}
                    <div>
                        <h4
                            class="text-xs font-bold text-ant-title uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-success"></span>
                            3. Grafik Lilin (Candlestick Bulanan)
                        </h4>
                        <div class="p-3.5 bg-ant-layout border border-ant-split rounded-ant-lg space-y-1.5 text-[11px]">
                            <p><strong>Puncak Lilin (High):</strong> Total seluruh pengukuran balita pada bulan
                                tersebut.</p>
                            <p><strong>Dasar Lilin (Low):</strong> Jumlah kasus stunting (Pendek + Sangat Pendek).</p>
                            <p><strong>Warna Hijau:</strong> Menandakan tren terkendali (balita sehat lebih dominan
                                dibanding stunting).</p>
                            <p><strong>Warna Merah:</strong> Menandakan status waspada (kasus stunting meningkat
                                signifikan).</p>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-3.5 bg-ant-layout border-t border-ant-split flex items-center justify-end">
                    <button @click="showGuideModal = false" type="button"
                        class="ant-btn ant-btn-primary">
                        Mengerti &amp; Tutup
                    </button>
                </div>
            </div>
        </div>
