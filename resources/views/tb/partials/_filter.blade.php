        <!-- MAIN DASHBOARD CONTENT -->
        <div class="min-h-screen bg-ant-layout py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- FILTER BAR -->
                <div class="ant-card ant-card-body">
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 flex-1">
                            <!-- Filter Kabupaten -->
                            <div>
                                <label class="ant-label">Kabupaten / Kota</label>
                                <div class="relative">
                                    <select x-model="selectedKabupaten" @change="onKabupatenChange()"
                                        class="ant-select pr-8">
                                        <option value="">Semua Kabupaten / Kota</option>
                                        @foreach($kabupatenList as $kab)
                                            <option value="{{ $kab }}">{{ $kab }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Kelurahan -->
                            <div>
                                <label class="ant-label">Kelurahan / Desa</label>
                                <div class="relative">
                                    <select x-model="selectedKelurahan" @change="applyFilters()"
                                        class="ant-select pr-8">
                                        <option value="">Semua Kelurahan / Desa</option>
                                        <template x-for="kel in kelurahanList" :key="kel">
                                            <option :value="kel" x-text="kel" :selected="kel === selectedKelurahan">
                                            </option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Tipe Register -->
                            <div>
                                <label class="ant-label">Kategori Register</label>
                                <select x-model="selectedType" @change="applyFilters()"
                                    class="ant-select pr-8">
                                    <option value="">Semua Data</option>
                                    <option value="tb_03">TB-03 SO (Register Pasien TBC)</option>
                                    <option value="tb_06">TB-06 (Register Terduga TBC)</option>
                                    <option value="skrining">Hasil Skrining Warga</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search & Reset Actions -->
                        <div class="flex items-center gap-2 pt-2 lg:pt-5 border-t lg:border-t-0 border-ant-split">
                            <div class="relative flex-1 sm:w-64">
                                <input type="text" x-model="searchQuery" @keydown.enter.prevent="applyFilters()"
                                    placeholder="Cari NIK / Nama / No Reg..."
                                    class="ant-input pl-9">
                                <svg class="w-4 h-4 text-ant-secondary absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button @click="applyFilters()" type="button"
                                class="ant-btn ant-btn-primary">
                                Filter
                            </button>
                            <button @click="resetFilters()" type="button" title="Reset filter"
                                class="ant-btn ant-btn-default !px-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
