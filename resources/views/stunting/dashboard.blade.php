<x-app-layout>
    <div x-data="stuntingDashboard()" x-init="initDashboard()">

        {{-- PAGE HEADER --}}
        <div class="bg-white border-b border-slate-200/80 shadow-xs">
            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-2xl text-slate-800 tracking-tight flex items-center gap-2.5">
                            <span class="p-2 rounded-xl bg-green-50 text-green-600 border border-green-100 shadow-xs">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-6C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" />
                                </svg>
                            </span>
                            Dashboard Pemantauan Balita Stunting
                        </h2>
                        <p class="text-sm md:text-base text-slate-500 mt-1">GERCEP PENTING — Gerakan Cepat Percepatan
                            Intervensi Stunting</p>
                    </div>
                    <div class="flex items-center flex-wrap gap-2">
                        {{-- Import Excel --}}
                        <button @click="showImportModal = true" type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span>Import Excel</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- FILTER BAR --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Desa / Kelurahan</label>
                        <select x-model="selectedDesa" @change="applyFilters()"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">Semua Desa</option>
                            @foreach ($desaList as $d)
                                <option value="{{ $d }}" @selected($selectedDesa === $d)>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[130px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Bulan</label>
                        <select x-model="selectedBulan" @change="applyFilters()"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">Semua Bulan</option>
                            @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $name)
                                <option value="{{ $num }}" @selected($selectedBulan === $num)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[100px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tahun</label>
                        <select x-model="selectedTahun" @change="applyFilters()"
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                            <option value="">Semua Tahun</option>
                            @foreach ($yearList as $yr)
                                <option value="{{ $yr }}" @selected($selectedTahun == $yr)>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari Nama / NIK</label>
                        <input x-model="searchQuery" @input.debounce.400ms="applyFilters()" type="text"
                            placeholder="Nama atau NIK..."
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    </div>
                    <button @click="clearFilters()"
                        class="flex-none px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                        Reset
                    </button>
                </div>
            </div>

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                {{-- Total Balita --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 col-span-1">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Balita</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1" x-text="stats.total ?? '{{ $total }}'"></p>
                    <p class="text-xs text-slate-400 mt-1">Seluruh data</p>
                </div>
                {{-- Stunting (Pendek + Sangat Pendek) --}}
                <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl border border-red-100 shadow-xs p-4 col-span-1">
                    <p class="text-xs font-semibold text-red-500 uppercase tracking-wide">Stunting</p>
                    <p class="text-3xl font-bold text-red-600 mt-1" x-text="stats.stuntingTotal ?? '{{ $stuntingTotal }}'"></p>
                    <p class="text-xs text-red-400 mt-1">Pendek + Sangat Pendek</p>
                </div>
                {{-- Pendek --}}
                <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl border border-orange-100 shadow-xs p-4">
                    <p class="text-xs font-semibold text-orange-500 uppercase tracking-wide">Pendek</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1" x-text="stats.pendekCount ?? '{{ $pendekCount }}'"></p>
                    <p class="text-xs text-orange-400 mt-1">TB/U: Pendek</p>
                </div>
                {{-- Sangat Pendek --}}
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl border border-red-200 shadow-xs p-4">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Sangat Pendek</p>
                    <p class="text-3xl font-bold text-red-700 mt-1" x-text="stats.sangatPendekCount ?? '{{ $sangatPendekCount }}'"></p>
                    <p class="text-xs text-red-500 mt-1">TB/U: Sangat Pendek</p>
                </div>
                {{-- Normal --}}
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100 shadow-xs p-4">
                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">Normal</p>
                    <p class="text-3xl font-bold text-green-700 mt-1" x-text="stats.normalCount ?? '{{ $normalCount }}'"></p>
                    <p class="text-xs text-green-500 mt-1">TB/U: Normal</p>
                </div>
            </div>

            {{-- SECONDARY KPI --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Gizi Baik (BB/U)</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1" x-text="stats.bbuGiziBaikCount ?? '{{ $bbuGiziBaikCount }}'"></p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Gizi Kurang (BB/U)</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1" x-text="(stats.bbuKurangCount ?? {{ $bbuKurangCount }}) + (stats.bbuSangatKurangCount ?? {{ $bbuSangatKurangCount }})"></p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Naik Berat Badan</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1" x-text="stats.naikBBYCount ?? '{{ $naikBBYCount }}'"></p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Tidak Naik BB</p>
                    <p class="text-2xl font-bold text-rose-600 mt-1" x-text="stats.naikBBTCount ?? '{{ $naikBBTCount }}'"></p>
                </div>
            </div>

            {{-- CHARTS SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Donut Chart: Status TB/U --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Status TB/U (Tinggi Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="tbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>Normal</span><span class="font-semibold text-slate-700">{{ $normalCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>Pendek</span><span class="font-semibold text-slate-700">{{ $pendekCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>Sangat Pendek</span><span class="font-semibold text-slate-700">{{ $sangatPendekCount }}</span></div>
                    </div>
                </div>

                {{-- Donut Chart: Status BB/U --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Status BB/U (Berat Badan/Umur)</h3>
                    <div class="relative flex justify-center">
                        <canvas id="bbuChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>Gizi Baik</span><span class="font-semibold text-slate-700">{{ $bbuGiziBaikCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>Gizi Kurang</span><span class="font-semibold text-slate-700">{{ $bbuKurangCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>Gizi Sangat Kurang</span><span class="font-semibold text-slate-700">{{ $bbuSangatKurangCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span>Risiko Lebih</span><span class="font-semibold text-slate-700">{{ $bbuRisikoLebihCount }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-purple-400 inline-block"></span>Gizi Lebih/Obesitas</span><span class="font-semibold text-slate-700">{{ $bbuLebihCount + $bbuObesitasCount }}</span></div>
                    </div>
                </div>

                {{-- Donut Chart: Jenis Kelamin --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Sebaran Jenis Kelamin</h3>
                    <div class="relative flex justify-center">
                        <canvas id="genderChart" height="200"></canvas>
                    </div>
                    <div class="mt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>Laki-laki</span><span class="font-semibold text-slate-700">{{ $lakiLaki }}</span></div>
                        <div class="flex justify-between"><span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-pink-400 inline-block"></span>Perempuan</span><span class="font-semibold text-slate-700">{{ $perempuan }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Per-Desa Bar Chart --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <h3 class="text-sm font-bold text-slate-700 mb-4">Distribusi Stunting per Desa/Kelurahan</h3>
                <canvas id="desaChart" height="90"></canvas>
            </div>

            {{-- DATA TABLE --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-700">Data Balita</h3>
                    <span class="text-xs text-slate-400">Menampilkan maks. 200 data terbaru</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">No</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Nama</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">JK</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Tgl Lahir</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Desa</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Posyandu</th>
                                <th class="px-3 py-3 text-right text-slate-500 font-semibold">BB Lahir</th>
                                <th class="px-3 py-3 text-right text-slate-500 font-semibold">Berat</th>
                                <th class="px-3 py-3 text-right text-slate-500 font-semibold">Tinggi</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">TB/U</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">BB/U</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">BB/TB</th>
                                <th class="px-3 py-3 text-center text-slate-500 font-semibold">Naik BB</th>
                                <th class="px-3 py-3 text-left text-slate-500 font-semibold">Tgl Ukur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($patients as $i => $p)
                                @php
                                    $tbuBadge = match($p->tbu_kategori) {
                                        'Sangat Pendek' => 'bg-red-100 text-red-700 border border-red-200',
                                        'Pendek'        => 'bg-amber-100 text-amber-700 border border-amber-200',
                                        'Normal'        => 'bg-green-100 text-green-700 border border-green-200',
                                        'Tinggi'        => 'bg-blue-100 text-blue-700 border border-blue-200',
                                        default         => 'bg-slate-100 text-slate-500',
                                    };
                                    $bbuBadge = match(true) {
                                        in_array($p->bbu_kategori, ['Sangat Kurang']) => 'bg-red-100 text-red-700 border border-red-200',
                                        in_array($p->bbu_kategori, ['Kurang'])        => 'bg-amber-100 text-amber-700 border border-amber-200',
                                        in_array($p->bbu_kategori, ['Normal', 'Gizi Baik']) => 'bg-green-100 text-green-700 border border-green-200',
                                        in_array($p->bbu_kategori, ['Risiko Lebih', 'Risiko Gizi Lebih']) => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                        in_array($p->bbu_kategori, ['Lebih', 'Gizi Lebih', 'Obesitas'])   => 'bg-purple-100 text-purple-700 border border-purple-200',
                                        default => 'bg-slate-100 text-slate-500',
                                    };
                                    $bbtbBadge = match(true) {
                                        in_array($p->bbtb_kategori, ['Gizi Kurang', 'Kurang']) => 'bg-amber-100 text-amber-700 border border-amber-200',
                                        in_array($p->bbtb_kategori, ['Gizi Baik', 'Normal'])   => 'bg-green-100 text-green-700 border border-green-200',
                                        in_array($p->bbtb_kategori, ['Risiko Gizi Lebih'])     => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                        in_array($p->bbtb_kategori, ['Gizi Lebih', 'Obesitas']) => 'bg-purple-100 text-purple-700 border border-purple-200',
                                        default => 'bg-slate-100 text-slate-500',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-3 py-2.5 text-slate-400">{{ $i + 1 }}</td>
                                    <td class="px-3 py-2.5 font-medium text-slate-800">{{ $p->nama }}</td>
                                    <td class="px-3 py-2.5 text-slate-600">{{ $p->jenis_kelamin === 'L' ? '♂' : '♀' }}</td>
                                    <td class="px-3 py-2.5 text-slate-600">{{ $p->tanggal_lahir?->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2.5 text-slate-600">{{ $p->desa }}</td>
                                    <td class="px-3 py-2.5 text-slate-500">{{ $p->posyandu }}</td>
                                    <td class="px-3 py-2.5 text-right text-slate-600">{{ $p->bb_lahir ? number_format($p->bb_lahir, 2) . ' kg' : '-' }}</td>
                                    <td class="px-3 py-2.5 text-right font-medium text-slate-700">{{ $p->berat ? number_format($p->berat, 2) . ' kg' : '-' }}</td>
                                    <td class="px-3 py-2.5 text-right font-medium text-slate-700">{{ $p->tinggi ? number_format($p->tinggi, 1) . ' cm' : '-' }}</td>
                                    <td class="px-3 py-2.5">
                                        @if($p->tbu_kategori)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $tbuBadge }}">{{ $p->tbu_kategori }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        @if($p->bbu_kategori)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $bbuBadge }}">{{ $p->bbu_kategori }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5">
                                        @if($p->bbtb_kategori)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $bbtbBadge }}">{{ $p->bbtb_kategori }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        @if($p->naik_berat_badan === 'N')
                                            <span class="inline-block w-5 h-5 rounded-full bg-green-100 text-green-600 text-center leading-5 font-bold">↑</span>
                                        @elseif($p->naik_berat_badan === 'T')
                                            <span class="inline-block w-5 h-5 rounded-full bg-red-100 text-red-600 text-center leading-5 font-bold">↓</span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 text-slate-500">{{ $p->tanggal_pengukuran?->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="px-5 py-10 text-center text-slate-400">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Belum ada data. Silakan import file Excel.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================================================
             IMPORT MODAL
             ============================================================ --}}
        <div x-show="showImportModal" x-transition.opacity
            class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" style="display:none">
            <div @click.outside="showImportModal = false"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800">Import Data Excel Stunting</h3>
                    <button @click="showImportModal = false"
                        class="text-slate-400 hover:text-slate-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <p class="text-xs text-slate-500">Upload file Excel format BALITA STUNTING (*.xlsx). Data akan di-preview sebelum disimpan.</p>

                {{-- File Picker --}}
                <div x-show="importStep === 'pick'" class="space-y-3">
                    <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-8 cursor-pointer hover:border-green-400 transition-colors group">
                        <svg class="w-8 h-8 text-slate-300 group-hover:text-green-400 transition-colors mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                        <span class="text-sm text-slate-500 group-hover:text-green-600 transition-colors" x-text="importFileName || 'Pilih file Excel (.xlsx)'"></span>
                        <input id="stunting-import-file" type="file" accept=".xlsx,.xls" class="hidden" @change="handleFileSelect($event)">
                    </label>
                    <button @click="previewImport()"
                        :disabled="!importFile || importLoading"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!importLoading">Preview Data</span>
                        <span x-show="importLoading">Membaca file...</span>
                    </button>
                </div>

                {{-- Preview --}}
                <div x-show="importStep === 'preview'" class="space-y-3">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-3">
                        <p class="text-sm font-semibold text-green-700">✓ File berhasil dibaca</p>
                        <p class="text-xs text-green-600 mt-0.5">Total <strong x-text="importPreview.total_rows"></strong> data ditemukan</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 max-h-48 overflow-y-auto text-xs space-y-1">
                        <template x-for="(row, idx) in (importPreview.preview_samples || [])" :key="idx">
                            <div class="flex gap-2 text-slate-600">
                                <span class="font-semibold" x-text="idx+1 + '.'"></span>
                                <span x-text="row.nama"></span>
                                <span class="text-slate-400" x-text="row.desa"></span>
                                <span class="ml-auto text-xs px-1.5 py-0.5 rounded-full"
                                    :class="['Pendek','Sangat Pendek'].includes(row.tbu_kategori) ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'"
                                    x-text="row.tbu_kategori || '-'"></span>
                            </div>
                        </template>
                    </div>
                    <div class="flex gap-2">
                        <button @click="importStep = 'pick'; importFile = null; importFileName = ''"
                            class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">← Ganti File</button>
                        <button @click="commitImport()"
                            :disabled="importLoading"
                            class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition disabled:opacity-50">
                            <span x-show="!importLoading">Simpan Semua</span>
                            <span x-show="importLoading">Menyimpan...</span>
                        </button>
                    </div>
                </div>

                {{-- Success --}}
                <div x-show="importStep === 'done'" class="text-center space-y-3 py-4">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-800" x-text="importSuccessMsg"></p>
                    <button @click="showImportModal = false; location.reload()"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition">
                        Tutup & Refresh
                    </button>
                </div>

                {{-- Error --}}
                <div x-show="importError" class="bg-red-50 border border-red-200 rounded-xl p-3">
                    <p class="text-xs text-red-600" x-text="importError"></p>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    function stuntingDashboard() {
        return {
            selectedDesa:  '{{ $selectedDesa }}',
            selectedBulan: '{{ $selectedBulan }}',
            selectedTahun: '{{ $selectedTahun }}',
            searchQuery:   '',
            stats: {},

            // Import modal
            showImportModal: false,
            importStep: 'pick',
            importFile: null,
            importFileName: '',
            importLoading: false,
            importPreview: {},
            importTempToken: '',
            importSuccessMsg: '',
            importError: '',

            // Charts
            tbuChart: null,
            bbuChart: null,
            genderChart: null,
            desaChart: null,

            initDashboard() {
                this.renderCharts();
            },

            applyFilters() {
                const params = new URLSearchParams({
                    desa:   this.selectedDesa,
                    bulan:  this.selectedBulan,
                    tahun:  this.selectedTahun,
                    search: this.searchQuery,
                });
                fetch(`{{ route('stunting.stats.json') }}?${params}`)
                    .then(r => r.json())
                    .then(data => {
                        this.stats = data;
                        this.updateCharts(data);
                    });
            },

            clearFilters() {
                this.selectedDesa  = '';
                this.selectedBulan = '';
                this.selectedTahun = '';
                this.searchQuery   = '';
                this.applyFilters();
            },

            renderCharts() {
                // TB/U Donut
                this.tbuChart = new Chart(document.getElementById('tbuChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Normal', 'Pendek', 'Sangat Pendek'],
                        datasets: [{
                            data: [{{ $normalCount }}, {{ $pendekCount }}, {{ $sangatPendekCount }}],
                            backgroundColor: ['#22c55e', '#fbbf24', '#ef4444'],
                            borderWidth: 2,
                            borderColor: '#fff',
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
                });

                // BB/U Donut
                this.bbuChart = new Chart(document.getElementById('bbuChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Gizi Baik', 'Gizi Kurang', 'Sangat Kurang', 'Risiko Lebih', 'Gizi Lebih/Obesitas'],
                        datasets: [{
                            data: [{{ $bbuGiziBaikCount }}, {{ $bbuKurangCount }}, {{ $bbuSangatKurangCount }}, {{ $bbuRisikoLebihCount }}, {{ $bbuLebihCount + $bbuObesitasCount }}],
                            backgroundColor: ['#10b981', '#fbbf24', '#ef4444', '#facc15', '#a78bfa'],
                            borderWidth: 2,
                            borderColor: '#fff',
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
                });

                // Gender Donut
                this.genderChart = new Chart(document.getElementById('genderChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Laki-laki', 'Perempuan'],
                        datasets: [{
                            data: [{{ $lakiLaki }}, {{ $perempuan }}],
                            backgroundColor: ['#3b82f6', '#f472b6'],
                            borderWidth: 2,
                            borderColor: '#fff',
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
                });

                // Per-desa bar chart
                const desaData = @json($byDesa);
                this.desaChart = new Chart(document.getElementById('desaChart'), {
                    type: 'bar',
                    data: {
                        labels: desaData.map(d => d.desa || '(kosong)'),
                        datasets: [
                            {
                                label: 'Normal',
                                data: desaData.map(d => d.normal),
                                backgroundColor: '#22c55e',
                                borderRadius: 4,
                            },
                            {
                                label: 'Pendek',
                                data: desaData.map(d => d.pendek),
                                backgroundColor: '#fbbf24',
                                borderRadius: 4,
                            },
                            {
                                label: 'Sangat Pendek',
                                data: desaData.map(d => d.sangat_pendek),
                                backgroundColor: '#ef4444',
                                borderRadius: 4,
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: { stacked: true, grid: { display: false } },
                            y: { stacked: true, ticks: { stepSize: 1 } }
                        },
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
                    }
                });
            },

            updateCharts(data) {
                if (this.tbuChart) {
                    this.tbuChart.data.datasets[0].data = [
                        data.normalCount, data.pendekCount, data.sangatPendekCount
                    ];
                    this.tbuChart.update();
                }
                if (this.bbuChart) {
                    this.bbuChart.data.datasets[0].data = [
                        data.bbuGiziBaikCount, data.bbuKurangCount, data.bbuSangatKurangCount,
                        data.bbuRisikoLebihCount, data.bbuLebihCount + data.bbuObesitasCount
                    ];
                    this.bbuChart.update();
                }
                if (this.genderChart) {
                    this.genderChart.data.datasets[0].data = [data.lakiLaki, data.perempuan];
                    this.genderChart.update();
                }
                if (this.desaChart && data.byDesa) {
                    this.desaChart.data.labels = data.byDesa.map(d => d.desa || '(kosong)');
                    this.desaChart.data.datasets[0].data = data.byDesa.map(d => d.normal);
                    this.desaChart.data.datasets[1].data = data.byDesa.map(d => d.pendek);
                    this.desaChart.data.datasets[2].data = data.byDesa.map(d => d.sangat_pendek);
                    this.desaChart.update();
                }
            },

            // ── Import helpers ────────────────────────────────────────────────
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.importFile    = file;
                this.importFileName = file.name;
                this.importError   = '';
            },

            async previewImport() {
                if (!this.importFile) return;
                this.importLoading = true;
                this.importError   = '';
                const fd = new FormData();
                fd.append('file', this.importFile);
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                try {
                    const res  = await fetch('{{ route("stunting.import.preview") }}', { method: 'POST', body: fd });
                    const json = await res.json();
                    if (json.success) {
                        this.importPreview   = json.data;
                        this.importTempToken = json.data.temp_token;
                        this.importStep      = 'preview';
                    } else {
                        this.importError = json.message || 'Gagal membaca file.';
                    }
                } catch(e) {
                    this.importError = 'Terjadi kesalahan: ' + e.message;
                } finally {
                    this.importLoading = false;
                }
            },

            async commitImport() {
                this.importLoading = true;
                this.importError   = '';
                const fd = new FormData();
                fd.append('temp_token', this.importTempToken);
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                try {
                    const res  = await fetch('{{ route("stunting.import.commit") }}', { method: 'POST', body: fd });
                    const json = await res.json();
                    if (json.success) {
                        this.importSuccessMsg = json.message;
                        this.importStep       = 'done';
                    } else {
                        this.importError = json.message || 'Gagal menyimpan data.';
                    }
                } catch(e) {
                    this.importError = 'Terjadi kesalahan: ' + e.message;
                } finally {
                    this.importLoading = false;
                }
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
