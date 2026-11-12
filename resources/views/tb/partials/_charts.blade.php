                <!-- VISUAL CHARTS SECTION -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Chart 1: Persebaran Kasus per Kelurahan (Bar Chart) -->
                    <div class="lg:col-span-2 ant-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="ant-section-title">Distribusi Pelacakan Terbanyak per
                                    Kelurahan
                                </h3>
                                <p class="text-xs text-ant-secondary">10 Kelurahan dengan kasus terdaftar tertinggi</p>
                            </div>
                        </div>
                        <div class="h-72">
                            <canvas id="kelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- Chart 2: Demografi Usia & Gender (Doughnut) -->
                    <div
                        class="ant-card p-5 flex flex-col justify-between">
                        <div>
                            <h3 class="ant-section-title">Demografi Gender & Usia</h3>
                            <p class="text-xs text-ant-secondary mb-4">Perbandingan jenis kelamin pasien</p>
                            <div class="h-52 relative">
                                <canvas id="genderChart"></canvas>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-ant-split grid grid-cols-3 gap-2 text-center">
                            <div class="bg-[#fafafa] p-2 rounded-ant">
                                <span class="block text-[11px] text-ant-secondary font-medium">Anak (&lt;15)</span>
                                <span class="text-sm font-bold text-ant-title" x-text="kpi.total_anak">0</span>
                            </div>
                            <div class="bg-[#fafafa] p-2 rounded-ant">
                                <span class="block text-[11px] text-ant-secondary font-medium">Produktif</span>
                                <span class="text-sm font-bold text-ant-title" x-text="kpi.total_produktif">0</span>
                            </div>
                            <div class="bg-[#fafafa] p-2 rounded-ant">
                                <span class="block text-[11px] text-ant-secondary font-medium">Lansia (≥60)</span>
                                <span class="text-sm font-bold text-ant-title" x-text="kpi.total_lansia">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 3: Tren Bulanan (Line Chart) -->
                <div class="ant-card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="ant-section-title">Tren Pelacakan Kasus per Bulan</h3>
                            <p class="text-xs text-ant-secondary">Perkembangan jumlah kasus sepanjang periode berjalan</p>
                        </div>
                    </div>
                    <div class="h-60">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- PETA GEOSPASIAL TBC — Per-Kecamatan, Configurable Widget   -->
                <!-- ============================================================ -->
                <div class="ant-card overflow-hidden">

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
                                    <h3 class="ant-section-title">Peta Sebaran Kasus TBC Berbasis
                                        Geospasial (GIS)</h3>
                                    <p class="text-xs text-ant-secondary">Visualisasi densitas kasus per kelurahan & kluster
                                        fasyankes — fokus 1 kecamatan</p>
                                </div>
                            </div>

                            {{-- Config / Save toggle button --}}
                            <button @click="tbMapCfgOpen = !tbMapCfgOpen" type="button"
                                :class="tbMapCfgOpen ? 'bg-primary text-white border-primary' : 'bg-white text-ant-text border-ant-border hover:bg-[#fafafa]'"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 border text-xs font-semibold rounded-ant transition-colors cursor-pointer shrink-0 self-start">
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
                            class="mt-5 p-5 bg-[#fafafa] rounded-ant border border-ant-split space-y-5">

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                {{-- Kecamatan selector --}}
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-ant-secondary uppercase tracking-wider mb-1.5">
                                        Fokus Kecamatan
                                    </label>
                                    <select x-model="tbMapCfg.kecamatan" @change="onTbMapKecamatanChange()"
                                        class="ant-select">
                                        <option value="">Semua Kecamatan</option>
                                        <template x-for="kec in tbMapKecamatanList" :key="kec">
                                            <option :value="kec" x-text="kec"></option>
                                        </template>
                                    </select>
                                </div>

                                {{-- Layer mode --}}
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-ant-secondary uppercase tracking-wider mb-1.5">
                                        Mode Tampilan Layer
                                    </label>
                                    <div
                                        class="inline-flex w-full rounded-ant p-0.5 bg-ant-split border border-ant-border">
                                        <button @click="tbMapCfg.viewMode = 'markers'; saveTbMapCfg(); updateMap()"
                                            type="button"
                                            :class="tbMapCfg.viewMode === 'markers' ? 'bg-white text-primary font-bold shadow-xs' : 'text-ant-text hover:text-ant-title'"
                                            class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Titik</button>
                                        <button @click="tbMapCfg.viewMode = 'density'; saveTbMapCfg(); updateMap()"
                                            type="button"
                                            :class="tbMapCfg.viewMode === 'density' ? 'bg-white text-primary font-bold shadow-xs' : 'text-ant-text hover:text-ant-title'"
                                            class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Densitas</button>
                                        <button @click="tbMapCfg.viewMode = 'geofence'; saveTbMapCfg(); updateMap()"
                                            type="button"
                                            :class="tbMapCfg.viewMode === 'geofence' ? 'bg-danger text-white font-bold shadow-xs' : 'text-ant-text hover:text-ant-title'"
                                            class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Geofence</button>
                                    </div>
                                </div>

                                {{-- Map height --}}
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-ant-secondary uppercase tracking-wider mb-1.5">
                                        Tinggi Peta
                                    </label>
                                    <div
                                        class="inline-flex w-full rounded-ant p-0.5 bg-ant-split border border-ant-border">
                                        <button @click="tbMapCfg.height = 320; saveTbMapCfg(); resizeTbMap()"
                                            type="button"
                                            :class="tbMapCfg.height === 320 ? 'bg-white text-primary font-bold shadow-xs' : 'text-ant-text'"
                                            class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Kecil</button>
                                        <button @click="tbMapCfg.height = 460; saveTbMapCfg(); resizeTbMap()"
                                            type="button"
                                            :class="tbMapCfg.height === 460 ? 'bg-white text-primary font-bold shadow-xs' : 'text-ant-text'"
                                            class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Sedang</button>
                                        <button @click="tbMapCfg.height = 600; saveTbMapCfg(); resizeTbMap()"
                                            type="button"
                                            :class="tbMapCfg.height === 600 ? 'bg-white text-primary font-bold shadow-xs' : 'text-ant-text'"
                                            class="flex-1 px-2 py-1.5 rounded-ant transition-colors cursor-pointer text-[11px]">Besar</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Layer toggles --}}
                            <div>
                                <label
                                    class="block text-[11px] font-bold text-ant-secondary uppercase tracking-wider mb-2">Layer
                                    Peta</label>
                                <div class="flex flex-wrap gap-2">
                                    <label
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-danger-100 rounded-ant cursor-pointer text-xs select-none hover:bg-danger-50 transition-colors shadow-2xs">
                                        <input type="checkbox" x-model="tbMapCfg.showPenderita"
                                            @change="saveTbMapCfg(); updateMap()" class="rounded accent-danger">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full bg-danger inline-block shadow-xs"></span>
                                        <span class="font-bold text-danger-600">Data Penderita TB (Merah)</span>
                                    </label>
                                    <label
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-success-100 rounded-ant cursor-pointer text-xs select-none hover:bg-success-50 transition-colors shadow-2xs">
                                        <input type="checkbox" x-model="tbMapCfg.showInvestigasi"
                                            @change="saveTbMapCfg(); updateMap()" class="rounded accent-success">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full bg-success inline-block shadow-xs"></span>
                                        <span class="font-bold text-success-600">Investigasi Kontak (Hijau)</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Save / Reset --}}
                            <div class="flex items-center justify-between pt-1">
                                <span x-show="tbMapCfgSaved" x-transition
                                    class="text-[11px] text-success-600 font-semibold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Konfigurasi tersimpan
                                </span>
                                <span x-show="!tbMapCfgSaved"></span>
                                <div class="flex gap-2">
                                    <button @click="resetTbMapCfg()" type="button"
                                        class="ant-btn ant-btn-default">
                                        Reset Default
                                    </button>
                                    <button @click="saveTbMapCfg(true)" type="button"
                                        class="ant-btn ant-btn-primary">
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
                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary-50 text-primary-600 text-[11px] font-semibold rounded-ant">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Kec. <span x-text="tbMapCfg.kecamatan"></span>
                                    <button @click="tbMapCfg.kecamatan=''; onTbMapKecamatanChange()" type="button"
                                        class="ml-1 text-primary hover:text-primary-600 cursor-pointer">✕</button>
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-ant-split text-ant-text text-[11px] font-semibold rounded-ant capitalize"
                                    x-text="{ markers:'Titik Sebaran', density:'Densitas Heatmap', geofence:'Geofence 500m' }[tbMapCfg.viewMode] || tbMapCfg.viewMode"></span>
                                <span x-show="tbMapCfg.showInvestigasi"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-success-50 text-success-600 text-[11px] font-semibold rounded-ant">Investigasi
                                    Kontak</span>
                                <span x-show="tbMapLoading"
                                    class="inline-flex items-center gap-1 text-[11px] text-ant-secondary">
                                    <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                    </svg>
                                    Memuat data peta...
                                </span>
                            </div>

                            <div class="flex items-center gap-4 pt-2 border-t border-ant-split text-xs">
                                <span
                                    class="text-[11px] font-semibold text-ant-secondary uppercase tracking-wider">Legenda</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-success shrink-0"
                                        style="border:2px solid #059669;"></span>
                                    <span class="text-ant-secondary">Investigasi Kontak (OAT aktif)</span>
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
                    <div class="lg:col-span-2 ant-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-2.5 mb-1">
                                    <span class="p-1.5 rounded-ant bg-danger-50 text-danger border border-danger-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                    <h3 class="ant-section-title">Distribusi Pasien Pengobatan OAT per Kelurahan</h3>
                                </div>
                                <p class="text-xs text-ant-secondary">10 Kelurahan dengan pasien TB-03 (Register Pasien TBC) tertinggi</p>
                            </div>
                            <span class="ant-tag ant-tag-red font-bold shrink-0">TB-03 SO</span>
                        </div>
                        <div class="h-72">
                            <canvas id="oatKelurahanChart"></canvas>
                        </div>
                    </div>

                    <!-- OAT KPI Mini-Cards -->
                    <div class="ant-card p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2.5 mb-4">
                                <span class="p-1.5 rounded-ant bg-danger-50 text-danger border border-danger-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </span>
                                <h3 class="ant-section-title">Ringkasan OAT</h3>
                            </div>

                            <!-- Progress bar: Dalam Pengobatan -->
                            <div class="space-y-3">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-ant-text">Sedang Pengobatan</span>
                                        <span class="text-xs font-bold text-danger-600" x-text="kpi.total_sedang_pengobatan || 0"></span>
                                    </div>
                                    <div class="h-2 bg-ant-split rounded-full overflow-hidden">
                                        <div class="h-2 bg-danger rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_sedang_pengobatan / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-ant-secondary"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_sedang_pengobatan / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-ant-text">Sembuh</span>
                                        <span class="text-xs font-bold text-success-600" x-text="kpi.total_sembuh || 0"></span>
                                    </div>
                                    <div class="h-2 bg-ant-split rounded-full overflow-hidden">
                                        <div class="h-2 bg-success rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_sembuh / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-ant-secondary"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_sembuh / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-ant-text">Pengobatan Lengkap</span>
                                        <span class="text-xs font-bold text-primary-600" x-text="kpi.total_lengkap || 0"></span>
                                    </div>
                                    <div class="h-2 bg-ant-split rounded-full overflow-hidden">
                                        <div class="h-2 bg-primary rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_lengkap / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-ant-secondary"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_lengkap / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-ant-text">Lost to Follow Up</span>
                                        <span class="text-xs font-bold text-warning-600" x-text="kpi.total_putus || 0"></span>
                                    </div>
                                    <div class="h-2 bg-ant-split rounded-full overflow-hidden">
                                        <div class="h-2 bg-warning rounded-full transition-all duration-500"
                                            :style="'width:' + ((kpi.total_terkonfirmasi > 0 ? Math.min(100, Math.round((kpi.total_putus / kpi.total_terkonfirmasi) * 100)) : 0)) + '%'"></div>
                                    </div>
                                    <span class="text-[10px] text-ant-secondary"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round((kpi.total_putus / kpi.total_terkonfirmasi) * 100) : 0) + '% dari total terkonfirmasi'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Total Terkonfirmasi footer -->
                        <div class="mt-5 pt-4 border-t border-ant-split">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[11px] text-ant-secondary font-medium">Total Terkonfirmasi (TB-03)</p>
                                    <p class="text-2xl font-black text-danger-600" x-text="kpi.total_terkonfirmasi || 0"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[11px] text-ant-secondary font-medium">Success Rate</p>
                                    <p class="text-2xl font-black text-success-600"
                                        x-text="(kpi.total_terkonfirmasi > 0 ? Math.round(((kpi.total_sembuh + kpi.total_lengkap) / kpi.total_terkonfirmasi) * 100) : 0) + '%'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
