                <!-- VISUAL CHARTS SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Chart 1: Persebaran Ibu Hamil per Kelurahan (Bar Chart) -->
                    <div class="lg:col-span-2 ant-card ant-card-body">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="ant-section-title">Sebaran Ibu Hamil Risiko Terbanyak di
                                    Desa / Kelurahan</h3>
                                <p class="ant-text-secondary mt-1">100 Kelurahan / Desa dengan ibu hamil terdaftar
                                    tertinggi</p>
                            </div>
                        </div>
                        <div class="h-72">
                            <canvas id="ancKelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Proporsi Usia Ibu Hamil (Doughnut) -->
                    <div
                        class="ant-card ant-card-body flex flex-col justify-between">
                        <div>
                            <h3 class="ant-section-title">Kelompok Usia Ibu Hamil</h3>
                            <p class="ant-text-secondary mb-4">Klasifikasi umur kehamilan</p>
                            <div class="h-52 relative">
                                <canvas id="ancAgeChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-ant-split grid grid-cols-3 gap-2 text-center">
                            <div class="bg-[#fafafa] p-2 rounded-ant">
                                <span class="block text-[11px] text-warning-600 font-medium">
                                    < 20 th (Muda)</span>
                                        <span class="text-sm font-bold text-ant-title" x-text="kpi.umur_muda">0</span>
                            </div>
                            <div class="bg-[#fafafa] p-2 rounded-ant">
                                <span class="block text-[11px] text-success-600 font-medium">20–35 (Produktif)</span>
                                <span class="text-sm font-bold text-ant-title" x-text="kpi.umur_produktif">0</span>
                            </div>
                            <div class="bg-[#fafafa] p-2 rounded-ant">
                                <span class="block text-[11px] text-danger-600 font-medium">> 35 th (Risiko)</span>
                                <span class="text-sm font-bold text-ant-title" x-text="kpi.umur_risti">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Tren Tafsiran Persalinan / HPL (Line Chart) -->
                <div class="ant-card ant-card-body">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="ant-section-title">Tren Tafsiran Persalinan</h3>
                            <p class="ant-text-secondary mt-1">Jumlah ibu hamil dengan HPL (Hari Perkiraan Lahir) per bulan — 12 bulan ke depan</p>
                        </div>
                    </div>
                    <div class="h-60">
                        <canvas id="ancMonthlyChart"></canvas>
                    </div>
                </div>

                <!-- INNOVATION 2 & 3: SKOR POEDJI ROCHJATI & PETA GEOSPASIAL IBU HAMIL -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Poedji Rochjati Classification Card -->
                    <div
                        class="ant-card ant-card-body flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="ant-section-title">Skor Poedji Rochjati (Kategori
                                        Risiko)</h3>
                                    <p class="ant-text-secondary mt-1">Standar skrining faskes rujukan persalinan</p>
                                </div>
                                <span class="p-2 rounded-ant bg-primary-50 text-primary border border-primary-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                            <div class="h-48 relative">
                                <canvas id="poedjiChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-ant-split space-y-2 text-xs">
                            <div
                                class="flex items-center justify-between p-2 rounded-ant bg-success-50 border border-success-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-success"></span>
                                    <span class="font-semibold text-success-600">KRR (Skor 2)</span>
                                    <span class="text-[11px] text-success-600 hidden sm:inline">- Bidan /
                                        Puskesmas</span>
                                </div>
                                <span class="font-bold text-success-600"
                                    x-text="poedjiChartData?.values?.[0] || 0">0</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 rounded-ant bg-warning-50 border border-warning-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-warning"></span>
                                    <span class="font-semibold text-warning-600">KRT (Skor 6-10)</span>
                                    <span class="text-[11px] text-warning-600 hidden sm:inline">- Puskesmas
                                        PONED/RS</span>
                                </div>
                                <span class="font-bold text-warning-600"
                                    x-text="poedjiChartData?.values?.[1] || 0">0</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 rounded-ant bg-danger-50 border border-danger-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-danger"></span>
                                    <span class="font-semibold text-danger-600">KRST (Skor ≥ 12)</span>
                                    <span class="text-[11px] text-danger-600 hidden sm:inline">- RS PONEK</span>
                                </div>
                                <span class="font-bold text-danger-600"
                                    x-text="poedjiChartData?.values?.[2] || 0">0</span>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- PETA SEBARAN IBU HAMIL & RISTI — Per-Kecamatan Widget --}}
                    {{-- ============================================================ --}}
                    <div
                        class="lg:col-span-2 ant-card overflow-hidden flex flex-col">

                        {{-- ── Widget Header ── --}}
                        <div class="px-6 pt-6 pb-5 border-b border-ant-split">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="p-2.5 rounded-ant bg-primary-50 text-primary border border-primary-100 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="ant-section-title">Peta Sebaran Ibu Hamil & Deteksi
                                            Wilayah RISTI</h3>
                                        <p class="ant-text-secondary mt-1">Konsentrasi ibu hamil & deteksi dini risiko
                                            tinggi per kelurahan — fokus 1 kecamatan</p>
                                    </div>
                                </div>

                                {{-- Config toggle --}}
                                <button @click="ancMapCfgOpen = !ancMapCfgOpen" type="button"
                                    :class="ancMapCfgOpen ? 'bg-primary text-white border-primary' : 'bg-white text-ant-text border-ant-border hover:bg-ant-split'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 border text-xs font-semibold rounded-ant transition-colors cursor-pointer shrink-0 self-start">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    <span x-text="ancMapCfgOpen ? 'Tutup Konfigurasi' : 'Konfigurasi Peta'"></span>
                                </button>
                            </div>

                            {{-- ── Collapsible Config Panel ── --}}
                            <div x-show="ancMapCfgOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-5 p-5 bg-[#fafafa] rounded-ant-lg border border-ant-border space-y-5">

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {{-- Kecamatan selector --}}
                                    <div>
                                        <label
                                            class="ant-label text-[11px] font-bold uppercase tracking-wider">Fokus
                                            Kecamatan</label>
                                        <select x-model="ancMapCfg.kecamatan" @change="onAncMapKecamatanChange()"
                                            class="ant-select">
                                            <option value="">Semua Kecamatan</option>
                                            <template x-for="kec in ancMapKecamatanList" :key="kec">
                                                <option :value="kec" x-text="kec"></option>
                                            </template>
                                        </select>
                                    </div>

                                    {{-- Layer mode --}}
                                    <div>
                                        <label
                                            class="ant-label text-[11px] font-bold uppercase tracking-wider">Mode
                                            Tampilan Layer</label>
                                        <div
                                            class="inline-flex w-full rounded-ant p-0.5 bg-ant-split border border-ant-border">
                                            <button
                                                @click="ancMapCfg.viewMode = 'markers'; saveAncMapCfg(); updateMap()"
                                                type="button"
                                                :class="ancMapCfg.viewMode === 'markers' ? 'bg-white text-primary font-bold shadow-ant-card' : 'text-ant-text hover:text-ant-title'"
                                                class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Titik</button>
                                            <button
                                                @click="ancMapCfg.viewMode = 'heatmap'; saveAncMapCfg(); updateMap()"
                                                type="button"
                                                :class="ancMapCfg.viewMode === 'heatmap' ? 'bg-white text-primary font-bold shadow-ant-card' : 'text-ant-text hover:text-ant-title'"
                                                class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Heatmap</button>
                                            <button @click="ancMapCfg.viewMode = 'risti'; saveAncMapCfg(); updateMap()"
                                                type="button"
                                                :class="ancMapCfg.viewMode === 'risti' ? 'bg-danger text-white font-bold shadow-ant-card' : 'text-ant-text hover:text-ant-title'"
                                                class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">RISTI</button>
                                        </div>
                                    </div>

                                    {{-- Map height --}}
                                    <div>
                                        <label
                                            class="ant-label text-[11px] font-bold uppercase tracking-wider">Tinggi
                                            Peta</label>
                                        <div
                                            class="inline-flex w-full rounded-ant p-0.5 bg-ant-split border border-ant-border">
                                            <button @click="ancMapCfg.height = 320; saveAncMapCfg(); resizeAncMap()"
                                                type="button"
                                                :class="ancMapCfg.height === 320 ? 'bg-white text-primary font-bold shadow-ant-card' : 'text-ant-text'"
                                                class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Kecil</button>
                                            <button @click="ancMapCfg.height = 460; saveAncMapCfg(); resizeAncMap()"
                                                type="button"
                                                :class="ancMapCfg.height === 460 ? 'bg-white text-primary font-bold shadow-ant-card' : 'text-ant-text'"
                                                class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Sedang</button>
                                            <button @click="ancMapCfg.height = 600; saveAncMapCfg(); resizeAncMap()"
                                                type="button"
                                                :class="ancMapCfg.height === 600 ? 'bg-white text-primary font-bold shadow-ant-card' : 'text-ant-text'"
                                                class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Besar</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Overlay layer toggles --}}
                                <div>
                                    <label
                                        class="ant-label text-[11px] font-bold uppercase tracking-wider">Overlay
                                        Layer Deteksi RISTI</label>
                                    <div class="flex flex-wrap gap-2">
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-ant-border rounded-ant cursor-pointer text-xs select-none hover:bg-ant-split">
                                            <input type="checkbox" x-model="ancMapCfg.showKEK"
                                                @change="saveAncMapCfg(); updateMap()" class="rounded accent-warning-600">
                                            <span class="font-medium text-ant-text">Zona KEK (LiLA &lt;23.5cm)</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-ant-border rounded-ant cursor-pointer text-xs select-none hover:bg-ant-split">
                                            <input type="checkbox" x-model="ancMapCfg.showAnemia"
                                                @change="saveAncMapCfg(); updateMap()" class="rounded accent-danger-600">
                                            <span class="font-medium text-ant-text">Zona Anemia (Hb &lt;11)</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-ant-border rounded-ant cursor-pointer text-xs select-none hover:bg-ant-split">
                                            <input type="checkbox" x-model="ancMapCfg.showHiper"
                                                @change="saveAncMapCfg(); updateMap()"
                                                class="rounded accent-warning-600">
                                            <span class="font-medium text-ant-text">Zona Hipertensi</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-ant-border rounded-ant cursor-pointer text-xs select-none hover:bg-ant-split">
                                            <input type="checkbox" x-model="ancMapCfg.showImminent"
                                                @change="saveAncMapCfg(); updateMap()"
                                                class="rounded accent-success-600">
                                            <span class="font-medium text-ant-text">HPL ≤ 7 Hari</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-ant-border rounded-ant cursor-pointer text-xs select-none hover:bg-ant-split">
                                            <input type="checkbox" x-model="ancMapCfg.showFasyankes"
                                                @change="saveAncMapCfg(); updateMap()" class="rounded accent-primary-600">
                                            <span class="font-medium text-ant-text">Kluster Fasyankes</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Save / Reset --}}
                                <div class="flex items-center justify-between pt-1">
                                    <span x-show="ancMapCfgSaved" x-transition
                                        class="text-[11px] text-success-600 font-semibold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Konfigurasi tersimpan
                                    </span>
                                    <span x-show="!ancMapCfgSaved"></span>
                                    <div class="flex gap-2">
                                        <button @click="resetAncMapCfg()" type="button"
                                            class="ant-btn ant-btn-default ant-btn-sm">
                                            Reset Default
                                        </button>
                                        <button @click="saveAncMapCfg(true)" type="button"
                                            class="ant-btn ant-btn-primary ant-btn-sm flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                            </svg>
                                            Simpan Konfigurasi
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Active badge chips ── --}}
                            <div class="mt-4 space-y-3">
                                {{-- Baris 1: badge status aktif --}}
                                <div class="flex items-center flex-wrap gap-2">
                                    <span x-show="ancMapCfg.kecamatan"
                                        class="ant-tag ant-tag-blue">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Kec. <span x-text="ancMapCfg.kecamatan"></span>
                                        <button @click="ancMapCfg.kecamatan=''; onAncMapKecamatanChange()" type="button"
                                            class="ml-1 text-primary-300 hover:text-primary-700 cursor-pointer">✕</button>
                                    </span>
                                    <span
                                        class="ant-tag ant-tag-gray capitalize"
                                        x-text="{ markers:'Titik Sebaran', heatmap:'Heatmap Risiko', risti:'Deteksi RISTI' }[ancMapCfg.viewMode] || ancMapCfg.viewMode"></span>
                                    <span x-show="ancMapCfg.showKEK"
                                        class="ant-tag ant-tag-gold">KEK</span>
                                    <span x-show="ancMapCfg.showAnemia"
                                        class="ant-tag ant-tag-red">Anemia</span>
                                    <span x-show="ancMapCfg.showHiper"
                                        class="ant-tag ant-tag-gold">Hipertensi</span>
                                    <span x-show="ancMapCfg.showImminent"
                                        class="ant-tag ant-tag-green">HPL
                                        ≤7hr</span>
                                    <span x-show="ancMapCfg.showFasyankes"
                                        class="ant-tag ant-tag-blue">Fasyankes</span>
                                    <span x-show="ancMapLoading"
                                        class="inline-flex items-center gap-1 text-[11px] text-ant-secondary">
                                        <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                        </svg>
                                        Memuat data peta...
                                    </span>
                                </div>

                                {{-- Baris 2: legenda warna --}}
                                <div class="flex items-center gap-4 pt-2 border-t border-ant-split text-xs">
                                    <span
                                        class="text-[11px] font-semibold text-ant-disabled uppercase tracking-wider">Legenda</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-danger shrink-0"></span>
                                        <span class="text-ant-secondary">KRST / Kritis</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-warning shrink-0"></span>
                                        <span class="text-ant-secondary">KRT / Tinggi</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-primary-400 shrink-0"></span>
                                        <span class="text-ant-secondary">Normal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Map Container ── --}}
                        <div id="ancMap" :style="'height:' + ancMapCfg.height + 'px; width:100%; isolation:isolate;'"
                            class="map-container w-full z-0 overflow-hidden relative flex-1">
                        </div>
                    </div>
                </div>
