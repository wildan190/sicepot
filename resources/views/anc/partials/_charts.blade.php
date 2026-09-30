                <!-- VISUAL CHARTS SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart 1: Persebaran Ibu Hamil per Kelurahan (Bar Chart) -->
                    <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-base text-slate-800">Sebaran Ibu Hamil Risiko Terbanyak di
                                    Desa / Kelurahan</h3>
                                <p class="text-xs text-slate-500">100 Kelurahan / Desa dengan ibu hamil terdaftar
                                    tertinggi</p>
                            </div>
                        </div>
                        <div class="h-72">
                            <canvas id="ancKelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Proporsi Usia Ibu Hamil (Doughnut) -->
                    <div
                        class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Kelompok Usia Ibu Hamil</h3>
                            <p class="text-xs text-slate-500 mb-4">Klasifikasi umur kehamilan</p>
                            <div class="h-52 relative">
                                <canvas id="ancAgeChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-amber-600 font-medium">
                                    < 20 th (Muda)</span>
                                        <span class="text-sm font-bold text-slate-800" x-text="kpi.umur_muda">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-emerald-600 font-medium">20–35 (Produktif)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.umur_produktif">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-rose-600 font-medium">> 35 th (Risiko)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.umur_risti">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Tren Tafsiran Persalinan / HPL (Line Chart) -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Tren Tafsiran Persalinan</h3>
                            <p class="text-xs text-slate-500">Jumlah ibu hamil dengan HPL (Hari Perkiraan Lahir) per bulan — 12 bulan ke depan</p>
                        </div>
                    </div>
                    <div class="h-60">
                        <canvas id="ancMonthlyChart"></canvas>
                    </div>
                </div>

                <!-- INNOVATION 2 & 3: SKOR POEDJI ROCHJATI & PETA GEOSPASIAL IBU HAMIL -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Poedji Rochjati Classification Card -->
                    <div
                        class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-bold text-base text-slate-800">Skor Poedji Rochjati (Kategori
                                        Risiko)</h3>
                                    <p class="text-xs text-slate-500">Standar skrining faskes rujukan persalinan</p>
                                </div>
                                <span class="p-2 rounded-xl bg-pink-50 text-pink-600 border border-pink-100">
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
                        <div class="mt-4 pt-3 border-t border-slate-100 space-y-2 text-xs">
                            <div
                                class="flex items-center justify-between p-2 rounded-xl bg-emerald-50/60 border border-emerald-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span class="font-semibold text-emerald-800">KRR (Skor 2)</span>
                                    <span class="text-[11px] text-emerald-600 hidden sm:inline">- Bidan /
                                        Puskesmas</span>
                                </div>
                                <span class="font-bold text-emerald-700"
                                    x-text="poedjiChartData?.values?.[0] || 0">0</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 rounded-xl bg-amber-50/60 border border-amber-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                    <span class="font-semibold text-amber-800">KRT (Skor 6-10)</span>
                                    <span class="text-[11px] text-amber-600 hidden sm:inline">- Puskesmas
                                        PONED/RS</span>
                                </div>
                                <span class="font-bold text-amber-700"
                                    x-text="poedjiChartData?.values?.[1] || 0">0</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 rounded-xl bg-rose-50/60 border border-rose-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span class="font-semibold text-rose-800">KRST (Skor ≥ 12)</span>
                                    <span class="text-[11px] text-rose-600 hidden sm:inline">- RS PONEK</span>
                                </div>
                                <span class="font-bold text-rose-700"
                                    x-text="poedjiChartData?.values?.[2] || 0">0</span>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- PETA SEBARAN IBU HAMIL & RISTI — Per-Kecamatan Widget --}}
                    {{-- ============================================================ --}}
                    <div
                        class="lg:col-span-2 bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden flex flex-col">

                        {{-- ── Widget Header ── --}}
                        <div class="px-6 pt-6 pb-5 border-b border-slate-100">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="p-2.5 rounded-xl bg-pink-50 text-pink-600 border border-pink-100 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <h3 class="font-bold text-base text-slate-800">Peta Sebaran Ibu Hamil & Deteksi
                                            Wilayah RISTI</h3>
                                        <p class="text-xs text-slate-500">Konsentrasi ibu hamil & deteksi dini risiko
                                            tinggi per kelurahan — fokus 1 kecamatan</p>
                                    </div>
                                </div>

                                {{-- Config toggle --}}
                                <button @click="ancMapCfgOpen = !ancMapCfgOpen" type="button"
                                    :class="ancMapCfgOpen ? 'bg-pink-600 text-white border-pink-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 border text-xs font-semibold rounded-xl transition-colors cursor-pointer shrink-0 self-start">
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
                                class="mt-5 p-5 bg-slate-50 rounded-xl border border-slate-200 space-y-5">

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {{-- Kecamatan selector --}}
                                    <div>
                                        <label
                                            class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Fokus
                                            Kecamatan</label>
                                        <select x-model="ancMapCfg.kecamatan" @change="onAncMapKecamatanChange()"
                                            class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500">
                                            <option value="">Semua Kecamatan</option>
                                            <template x-for="kec in ancMapKecamatanList" :key="kec">
                                                <option :value="kec" x-text="kec"></option>
                                            </template>
                                        </select>
                                    </div>

                                    {{-- Layer mode --}}
                                    <div>
                                        <label
                                            class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Mode
                                            Tampilan Layer</label>
                                        <div
                                            class="inline-flex w-full rounded-xl p-0.5 bg-slate-200 border border-slate-300">
                                            <button
                                                @click="ancMapCfg.viewMode = 'markers'; saveAncMapCfg(); updateMap()"
                                                type="button"
                                                :class="ancMapCfg.viewMode === 'markers' ? 'bg-white text-pink-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-800'"
                                                class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Titik</button>
                                            <button
                                                @click="ancMapCfg.viewMode = 'heatmap'; saveAncMapCfg(); updateMap()"
                                                type="button"
                                                :class="ancMapCfg.viewMode === 'heatmap' ? 'bg-white text-pink-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-800'"
                                                class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Heatmap</button>
                                            <button @click="ancMapCfg.viewMode = 'risti'; saveAncMapCfg(); updateMap()"
                                                type="button"
                                                :class="ancMapCfg.viewMode === 'risti' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-800'"
                                                class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">RISTI</button>
                                        </div>
                                    </div>

                                    {{-- Map height --}}
                                    <div>
                                        <label
                                            class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tinggi
                                            Peta</label>
                                        <div
                                            class="inline-flex w-full rounded-xl p-0.5 bg-slate-200 border border-slate-300">
                                            <button @click="ancMapCfg.height = 320; saveAncMapCfg(); resizeAncMap()"
                                                type="button"
                                                :class="ancMapCfg.height === 320 ? 'bg-white text-pink-700 font-bold shadow-xs' : 'text-slate-600'"
                                                class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Kecil</button>
                                            <button @click="ancMapCfg.height = 460; saveAncMapCfg(); resizeAncMap()"
                                                type="button"
                                                :class="ancMapCfg.height === 460 ? 'bg-white text-pink-700 font-bold shadow-xs' : 'text-slate-600'"
                                                class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Sedang</button>
                                            <button @click="ancMapCfg.height = 600; saveAncMapCfg(); resizeAncMap()"
                                                type="button"
                                                :class="ancMapCfg.height === 600 ? 'bg-white text-pink-700 font-bold shadow-xs' : 'text-slate-600'"
                                                class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Besar</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Overlay layer toggles --}}
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Overlay
                                        Layer Deteksi RISTI</label>
                                    <div class="flex flex-wrap gap-2">
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg cursor-pointer text-xs select-none hover:bg-slate-50">
                                            <input type="checkbox" x-model="ancMapCfg.showKEK"
                                                @change="saveAncMapCfg(); updateMap()" class="rounded accent-amber-600">
                                            <span class="font-medium text-slate-700">Zona KEK (LiLA &lt;23.5cm)</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg cursor-pointer text-xs select-none hover:bg-slate-50">
                                            <input type="checkbox" x-model="ancMapCfg.showAnemia"
                                                @change="saveAncMapCfg(); updateMap()" class="rounded accent-rose-600">
                                            <span class="font-medium text-slate-700">Zona Anemia (Hb &lt;11)</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg cursor-pointer text-xs select-none hover:bg-slate-50">
                                            <input type="checkbox" x-model="ancMapCfg.showHiper"
                                                @change="saveAncMapCfg(); updateMap()"
                                                class="rounded accent-orange-600">
                                            <span class="font-medium text-slate-700">Zona Hipertensi</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg cursor-pointer text-xs select-none hover:bg-slate-50">
                                            <input type="checkbox" x-model="ancMapCfg.showImminent"
                                                @change="saveAncMapCfg(); updateMap()"
                                                class="rounded accent-emerald-600">
                                            <span class="font-medium text-slate-700">HPL ≤ 7 Hari</span>
                                        </label>
                                        <label
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg cursor-pointer text-xs select-none hover:bg-slate-50">
                                            <input type="checkbox" x-model="ancMapCfg.showFasyankes"
                                                @change="saveAncMapCfg(); updateMap()" class="rounded accent-sky-600">
                                            <span class="font-medium text-slate-700">Kluster Fasyankes</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Save / Reset --}}
                                <div class="flex items-center justify-between pt-1">
                                    <span x-show="ancMapCfgSaved" x-transition
                                        class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Konfigurasi tersimpan
                                    </span>
                                    <span x-show="!ancMapCfgSaved"></span>
                                    <div class="flex gap-2">
                                        <button @click="resetAncMapCfg()" type="button"
                                            class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer">
                                            Reset Default
                                        </button>
                                        <button @click="saveAncMapCfg(true)" type="button"
                                            class="px-3.5 py-1.5 text-xs bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg transition-colors cursor-pointer flex items-center gap-1.5">
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
                                        class="inline-flex items-center gap-1 px-2 py-0.5 bg-pink-100 text-pink-700 text-[11px] font-semibold rounded-lg">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Kec. <span x-text="ancMapCfg.kecamatan"></span>
                                        <button @click="ancMapCfg.kecamatan=''; onAncMapKecamatanChange()" type="button"
                                            class="ml-1 text-pink-400 hover:text-pink-800 cursor-pointer">✕</button>
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-600 text-[11px] font-semibold rounded-lg capitalize"
                                        x-text="{ markers:'Titik Sebaran', heatmap:'Heatmap Risiko', risti:'Deteksi RISTI' }[ancMapCfg.viewMode] || ancMapCfg.viewMode"></span>
                                    <span x-show="ancMapCfg.showKEK"
                                        class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[11px] font-semibold rounded-lg">KEK</span>
                                    <span x-show="ancMapCfg.showAnemia"
                                        class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[11px] font-semibold rounded-lg">Anemia</span>
                                    <span x-show="ancMapCfg.showHiper"
                                        class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[11px] font-semibold rounded-lg">Hipertensi</span>
                                    <span x-show="ancMapCfg.showImminent"
                                        class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[11px] font-semibold rounded-lg">HPL
                                        ≤7hr</span>
                                    <span x-show="ancMapCfg.showFasyankes"
                                        class="px-2 py-0.5 bg-sky-100 text-sky-700 text-[11px] font-semibold rounded-lg">Fasyankes</span>
                                    <span x-show="ancMapLoading"
                                        class="inline-flex items-center gap-1 text-[11px] text-slate-500">
                                        <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                        </svg>
                                        Memuat data peta...
                                    </span>
                                </div>

                                {{-- Baris 2: legenda warna --}}
                                <div class="flex items-center gap-4 pt-2 border-t border-slate-100 text-xs">
                                    <span
                                        class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Legenda</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-rose-500 shrink-0"></span>
                                        <span class="text-slate-500">KRST / Kritis</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0"></span>
                                        <span class="text-slate-500">KRT / Tinggi</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-pink-400 shrink-0"></span>
                                        <span class="text-slate-500">Normal</span>
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

