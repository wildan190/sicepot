                <!-- VISUAL CHARTS SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart 1: Persebaran Kasus per Kelurahan (Bar Chart) -->
                    <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-base text-slate-800">Distribusi Pelacakan Terbanyak per
                                    Kelurahan
                                </h3>
                                <p class="text-xs text-slate-500">10 Kelurahan dengan kasus terdaftar tertinggi</p>
                            </div>
                        </div>
                        <div class="h-72">
                            <canvas id="kelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Demografi Usia & Gender (Doughnut) -->
                    <div
                        class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Demografi Gender & Usia</h3>
                            <p class="text-xs text-slate-500 mb-4">Perbandingan jenis kelamin pasien</p>
                            <div class="h-52 relative">
                                <canvas id="genderChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-slate-500 font-medium">Anak (&lt;15)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.total_anak">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-slate-500 font-medium">Produktif</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.total_produktif">0</span>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-xl">
                                <span class="block text-[11px] text-slate-500 font-medium">Lansia (≥60)</span>
                                <span class="text-sm font-bold text-slate-800" x-text="kpi.total_lansia">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Tren Bulanan (Line Chart) -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-base text-slate-800">Tren Pelacakan Kasus per Bulan</h3>
                            <p class="text-xs text-slate-500">Perkembangan jumlah kasus sepanjang periode berjalan</p>
                        </div>
                    </div>
                    <div class="h-60">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- PETA GEOSPASIAL TBC — Per-Kecamatan, Configurable Widget   -->
                <!-- ============================================================ -->
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">

                    {{-- ── Widget Header ── --}}
                    <div class="px-6 pt-6 pb-5 border-b border-slate-100">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span
                                    class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="font-bold text-base text-slate-800">Peta Sebaran Kasus TBC Berbasis
                                        Geospasial (GIS)</h3>
                                    <p class="text-xs text-slate-500">Visualisasi densitas kasus per kelurahan & kluster
                                        fasyankes — fokus 1 kecamatan</p>
                                </div>
                            </div>

                            {{-- Config / Save toggle button --}}
                            <button @click="tbMapCfgOpen = !tbMapCfgOpen" type="button"
                                :class="tbMapCfgOpen ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 border text-xs font-semibold rounded-xl transition-colors cursor-pointer shrink-0 self-start">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <span x-text="tbMapCfgOpen ? 'Tutup Konfigurasi' : 'Konfigurasi Peta'"></span>
                            </button>
                        </div>

                        {{-- ── Collapsible Config Panel ── --}}
                        <div x-show="tbMapCfgOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="mt-5 p-5 bg-slate-50 rounded-xl border border-slate-200 space-y-5">

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                {{-- Kecamatan selector --}}
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        Fokus Kecamatan
                                    </label>
                                    <select x-model="tbMapCfg.kecamatan" @change="onTbMapKecamatanChange()"
                                        class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Semua Kecamatan</option>
                                        <template x-for="kec in tbMapKecamatanList" :key="kec">
                                            <option :value="kec" x-text="kec"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- Layer mode --}}
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        Mode Tampilan Layer
                                    </label>
                                    <div
                                        class="inline-flex w-full rounded-xl p-0.5 bg-slate-200 border border-slate-300">
                                        <button @click="tbMapCfg.viewMode = 'markers'; saveTbMapCfg(); updateMap()"
                                            type="button"
                                            :class="tbMapCfg.viewMode === 'markers' ? 'bg-white text-indigo-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-800'"
                                            class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Titik</button>
                                        <button @click="tbMapCfg.viewMode = 'density'; saveTbMapCfg(); updateMap()"
                                            type="button"
                                            :class="tbMapCfg.viewMode === 'density' ? 'bg-white text-indigo-700 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-800'"
                                            class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Densitas</button>
                                        <button @click="tbMapCfg.viewMode = 'geofence'; saveTbMapCfg(); updateMap()"
                                            type="button"
                                            :class="tbMapCfg.viewMode === 'geofence' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-800'"
                                            class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Geofence</button>
                                    </div>
                                </div>

                                {{-- Map height --}}
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                        Tinggi Peta
                                    </label>
                                    <div
                                        class="inline-flex w-full rounded-xl p-0.5 bg-slate-200 border border-slate-300">
                                        <button @click="tbMapCfg.height = 320; saveTbMapCfg(); resizeTbMap()"
                                            type="button"
                                            :class="tbMapCfg.height === 320 ? 'bg-white text-indigo-700 font-bold shadow-xs' : 'text-slate-600'"
                                            class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Kecil</button>
                                        <button @click="tbMapCfg.height = 460; saveTbMapCfg(); resizeTbMap()"
                                            type="button"
                                            :class="tbMapCfg.height === 460 ? 'bg-white text-indigo-700 font-bold shadow-xs' : 'text-slate-600'"
                                            class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Sedang</button>
                                        <button @click="tbMapCfg.height = 600; saveTbMapCfg(); resizeTbMap()"
                                            type="button"
                                            :class="tbMapCfg.height === 600 ? 'bg-white text-indigo-700 font-bold shadow-xs' : 'text-slate-600'"
                                            class="flex-1 px-2 py-1.5 rounded-lg transition-colors cursor-pointer text-[11px]">Besar</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Layer toggles --}}
                            <div>
                                <label
                                    class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Layer
                                    Peta</label>
                                <div class="flex flex-wrap gap-2">
                                    <label
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-rose-200 rounded-lg cursor-pointer text-xs select-none hover:bg-rose-50 transition-colors shadow-2xs">
                                        <input type="checkbox" x-model="tbMapCfg.showPenderita"
                                            @change="saveTbMapCfg(); updateMap()" class="rounded accent-rose-600">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block shadow-xs"></span>
                                        <span class="font-bold text-rose-700">Data Penderita TB (Merah)</span>
                                    </label>
                                    <label
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-emerald-200 rounded-lg cursor-pointer text-xs select-none hover:bg-emerald-50 transition-colors shadow-2xs">
                                        <input type="checkbox" x-model="tbMapCfg.showInvestigasi"
                                            @change="saveTbMapCfg(); updateMap()" class="rounded accent-emerald-600">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block shadow-xs"></span>
                                        <span class="font-bold text-emerald-700">Investigasi Kontak (Hijau)</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Save / Reset --}}
                            <div class="flex items-center justify-between pt-1">
                                <span x-show="tbMapCfgSaved" x-transition
                                    class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Konfigurasi tersimpan
                                </span>
                                <span x-show="!tbMapCfgSaved"></span>
                                <div class="flex gap-2">
                                    <button @click="resetTbMapCfg()" type="button"
                                        class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer">
                                        Reset Default
                                    </button>
                                    <button @click="saveTbMapCfg(true)" type="button"
                                        class="px-3.5 py-1.5 text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors cursor-pointer flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                        </svg>
                                        Simpan Konfigurasi
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- ── Active Layer Badges (always visible) ── --}}
                        <div class="mt-4 space-y-3">
                            {{-- Baris 1: badge status aktif --}}
                            <div class="flex items-center flex-wrap gap-2">
                                <span x-show="tbMapCfg.kecamatan"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[11px] font-semibold rounded-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Kec. <span x-text="tbMapCfg.kecamatan"></span>
                                    <button @click="tbMapCfg.kecamatan=''; onTbMapKecamatanChange()" type="button"
                                        class="ml-1 text-indigo-500 hover:text-indigo-800 cursor-pointer">✕</button>
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-600 text-[11px] font-semibold rounded-lg capitalize"
                                    x-text="{ markers:'Titik Sebaran', density:'Densitas Heatmap', geofence:'Geofence 500m' }[tbMapCfg.viewMode] || tbMapCfg.viewMode"></span>
                                <span x-show="tbMapCfg.showInvestigasi"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[11px] font-semibold rounded-lg">Investigasi
                                    Kontak</span>
                                <span x-show="tbMapLoading"
                                    class="inline-flex items-center gap-1 text-[11px] text-slate-500">
                                    <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Memuat data peta...
                                </span>
                            </div>

                            <div class="flex items-center gap-4 pt-2 border-t border-slate-100 text-xs">
                                <span
                                    class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Legenda</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"
                                        style="border:2px solid #059669;"></span>
                                    <span class="text-slate-500">Investigasi Kontak (OAT aktif)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Map Container ── --}}
                    <div id="tbMap" :style="'height:' + tbMapCfg.height + 'px; width:100%; isolation:isolate;'"
                        class="map-container w-full z-0 overflow-hidden relative">
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- DASHBOARD: PASIEN PENGOBATAN OAT (TB-03)                     -->
                <!-- ============================================================ -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Chart: Distribusi Pasien OAT per Kelurahan -->
                    <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-xs border border-rose-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-2.5 mb-1">
                                    <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                    <h3 class="font-bold text-base text-slate-800">Distribusi Pasien Pengobatan OAT per Kelurahan</h3>
                                </div>
                                <p class="text-xs text-slate-500">10 Kelurahan dengan pasien TB-03 (Register Pasien TBC) tertinggi</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-lg text-[11px] font-bold bg-rose-100 text-rose-700 border border-rose-200 shrink-0">TB-03 SO</span>
                        </div>
                        <div class="h-72">
                            <canvas id="oatKelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- OAT KPI Mini-Cards -->
                    <div class="bg-white p-5 rounded-2xl shadow-xs border border-rose-100 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2.5 mb-4">
                                <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600 border border-rose-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </span>
                                <h3 class="font-bold text-base text-slate-800">Ringkasan OAT</h3>
                            </div>

                            <!-- Progress bar: Dalam Pengobatan -->
                            <div class="space-y-3">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-slate-600">Sedang Pengobatan</span>
                                        <span class="text-xs font-bold text-rose-700" x-text="kpi.total_sedang_pengobatan || 0"></span>
                                    </div>
                                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-2 bg-rose-500 rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_sedang_pengobatan / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-400"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_sedang_pengobatan / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-slate-600">Sembuh</span>
                                        <span class="text-xs font-bold text-emerald-700" x-text="kpi.total_sembuh || 0"></span>
                                    </div>
                                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-2 bg-emerald-500 rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_sembuh / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-400"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_sembuh / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-slate-600">Pengobatan Lengkap</span>
                                        <span class="text-xs font-bold text-sky-700" x-text="kpi.total_lengkap || 0"></span>
                                    </div>
                                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-2 bg-sky-500 rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_lengkap / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-400"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_lengkap / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-slate-600">Lost to Follow Up</span>
                                        <span class="text-xs font-bold text-amber-700" x-text="kpi.total_putus || 0"></span>
                                    </div>
                                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-2 bg-amber-500 rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_putus / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-slate-400"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_putus / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Total Terkonfirmasi footer -->
                        <div class="mt-5 pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] text-slate-500 font-medium">Total Terkonfirmasi (TB-03)</p>
                                    <p class="text-2xl font-black text-rose-700" x-text="kpi.total_terkonfirmasi || 0"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[11px] text-slate-500 font-medium">Success Rate</p>
                                    <p class="text-2xl font-black text-emerald-700"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round(((kpi.total_sembuh + kpi.total_lengkap) / kpi.total_terkonfirmasi) * 100) : 0) + '%'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

