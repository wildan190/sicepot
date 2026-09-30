                <!-- FILTER BAR -->
                <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 transition-all">
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 flex-1">
                            <!-- Filter Kabupaten -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kabupaten
                                    / Kota</label>
                                <div class="relative">
                                    <select x-model="selectedKabupaten" @change="onKabupatenChange()"
                                        class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                        <option value="">Semua Kabupaten / Kota</option>
                                        @foreach($kabupatenList as $kab)
                                            <option value="{{ $kab }}">{{ $kab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Kelurahan -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Kelurahan
                                    / Desa</label>
                                <div class="relative">
                                    <select x-model="selectedKelurahan" @change="applyFilters()"
                                        class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                        <option value="">Semua Kelurahan / Desa</option>
                                        <template x-for="kel in kelurahanList" :key="kel">
                                            <option :value="kel" x-text="kel" :selected="kel === selectedKelurahan">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Bulan -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Bulan
                                    Pelaporan</label>
                                <select x-model="selectedBulan" @change="applyFilters()"
                                    class="w-full pl-3.5 pr-8 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                    <option value="">Semua Periode</option>
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search & Reset Actions -->
                        <div class="flex items-center gap-2 pt-2 lg:pt-5 border-t lg:border-t-0 border-slate-100">
                            <div class="relative flex-1 sm:w-64">
                                <input type="text" x-model="searchQuery" @keydown.enter.prevent="applyFilters()"
                                    placeholder="Cari NIK / Nama / No RM..."
                                    class="w-full pl-9 pr-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-colors">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button @click="applyFilters()" type="button"
                                class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl transition-colors cursor-pointer">
                                Filter
                            </button>
                            <button @click="resetFilters()" type="button" title="Reset filter"
                                class="p-2 text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

