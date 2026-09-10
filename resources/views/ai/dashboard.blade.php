<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900"
        x-data="aiDashboard()" x-init="init()">

        {{-- ── Header ────────────────────────────────────────────────────────── --}}
        <div class="border-b border-white/10 bg-white/5 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex items-center gap-3">
                    <span class="p-2.5 rounded-xl bg-indigo-500/20 border border-indigo-400/30 text-indigo-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </span>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">AI Dashboard Generator</h1>
                        <p class="text-sm text-indigo-300">Ketik pertanyaan, Gemini AI buat dashboardnya otomatis</p>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-semibold rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
                            Gemini 2.5 Flash
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            {{-- ── Prompt Bar ────────────────────────────────────────────────── --}}
            <div class="bg-white/8 backdrop-blur-md border border-white/15 rounded-2xl p-6 shadow-xl">
                <label class="block text-sm font-semibold text-white/80 mb-3">
                    💬 Apa yang ingin kamu analisis?
                </label>
                <div class="flex gap-3">
                    <textarea
                        x-model="prompt"
                        @keydown.ctrl.enter="generate()"
                        placeholder="Contoh: Tampilkan distribusi ibu hamil risiko tinggi per kelurahan dan tren per bulan..."
                        rows="3"
                        class="flex-1 bg-white/10 border border-white/20 text-white placeholder-white/30 rounded-xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500/60 focus:border-indigo-400/50 transition-all"
                    ></textarea>
                    <button
                        @click="generate()"
                        :disabled="loading || !prompt.trim()"
                        class="px-5 py-3 bg-gradient-to-b from-indigo-500 to-indigo-600 hover:from-indigo-400 hover:to-indigo-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold rounded-xl shadow-lg transition-all flex flex-col items-center justify-center gap-1 min-w-[90px] cursor-pointer"
                    >
                        <template x-if="!loading">
                            <span class="flex flex-col items-center gap-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="text-xs">Generate</span>
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex flex-col items-center gap-1">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span class="text-xs">Proses...</span>
                            </span>
                        </template>
                    </button>
                </div>
                <p class="text-xs text-white/30 mt-2">Tekan Ctrl+Enter untuk generate</p>

                {{-- Contoh Prompt Chips --}}
                <div class="mt-4">
                    <p class="text-xs text-white/50 mb-2 font-medium">✨ Contoh prompt:</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="example in examples" :key="example">
                            <button
                                @click="prompt = example"
                                class="px-3 py-1.5 text-xs bg-white/8 hover:bg-white/15 border border-white/15 hover:border-indigo-400/50 text-white/70 hover:text-white rounded-lg transition-all cursor-pointer"
                                x-text="example"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── Error State ──────────────────────────────────────────────── --}}
            <template x-if="error">
                <div class="bg-rose-500/15 border border-rose-500/30 text-rose-300 rounded-xl px-5 py-4 flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-sm">Gagal generate dashboard</p>
                        <p class="text-xs mt-0.5 text-rose-400" x-text="error"></p>
                    </div>
                </div>
            </template>

            {{-- ── Loading Shimmer ──────────────────────────────────────────── --}}
            <template x-if="loading">
                <div class="space-y-6 animate-pulse">
                    <div class="h-8 w-64 bg-white/10 rounded-xl"></div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <template x-for="i in 4" :key="i">
                            <div class="h-28 bg-white/8 rounded-2xl border border-white/10"></div>
                        </template>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="h-72 bg-white/8 rounded-2xl border border-white/10"></div>
                        <div class="h-72 bg-white/8 rounded-2xl border border-white/10"></div>
                    </div>
                </div>
            </template>

            {{-- ── Generated Dashboard ──────────────────────────────────────── --}}
            <template x-if="dashboard && !loading">
                <div class="space-y-6">

                    {{-- Title & Description --}}
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight" x-text="dashboard.title"></h2>
                        <p class="text-indigo-300 text-sm mt-1" x-text="dashboard.description"></p>
                    </div>

                    {{-- KPI Cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        <template x-for="kpi in dashboard.kpis" :key="kpi.label">
                            <div :class="kpiCardClass(kpi.color)"
                                class="rounded-2xl p-4 border flex flex-col justify-between backdrop-blur-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold uppercase tracking-wider opacity-80"
                                        x-text="kpi.label"></span>
                                    <span class="p-1.5 rounded-lg" :class="kpiIconBg(kpi.color)"
                                        x-html="kpiIcon(kpi.icon)"></span>
                                </div>
                                <div class="mt-3">
                                    <span class="text-3xl font-bold" x-text="Number(kpi.value).toLocaleString('id-ID')"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Charts --}}
                    <div :class="dashboard.charts.length === 1 ? 'grid grid-cols-1' : 'grid grid-cols-1 lg:grid-cols-2'"
                        class="gap-6">
                        <template x-for="(chart, idx) in dashboard.charts" :key="idx">
                            <div class="bg-white/8 backdrop-blur-md border border-white/15 rounded-2xl p-5">
                                <h3 class="font-bold text-white text-base mb-4" x-text="chart.title"></h3>
                                <div class="relative h-72">
                                    <canvas :id="'ai-chart-' + idx"></canvas>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Insights --}}
                    <template x-if="dashboard.insights && dashboard.insights.length">
                        <div class="bg-indigo-500/10 border border-indigo-500/25 rounded-2xl p-5">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                    </path>
                                </svg>
                                <h3 class="font-bold text-indigo-200 text-sm">Insights dari Gemini AI</h3>
                            </div>
                            <ul class="space-y-2.5">
                                <template x-for="(insight, i) in dashboard.insights" :key="i">
                                    <li class="flex items-start gap-2.5 text-sm text-indigo-100/80">
                                        <span class="shrink-0 w-5 h-5 rounded-full bg-indigo-500/25 border border-indigo-400/30 text-indigo-300 text-xs flex items-center justify-center font-bold mt-0.5"
                                            x-text="i + 1"></span>
                                        <span x-text="insight"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>

                </div>
            </template>

            {{-- ── Empty State ─────────────────────────────────────────────── --}}
            <template x-if="!dashboard && !loading && !error">
                <div class="text-center py-20">
                    <div class="inline-flex p-6 rounded-3xl bg-white/5 border border-white/10 mb-6">
                        <svg class="w-14 h-14 text-indigo-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white/60 mb-2">Dashboard belum di-generate</h3>
                    <p class="text-sm text-white/30">Ketik pertanyaan di atas lalu klik Generate</p>
                </div>
            </template>

        </div>
    </div>

    @push('scripts')
    <script>
    function aiDashboard() {
        return {
            prompt: '',
            loading: false,
            dashboard: null,
            error: null,
            chartInstances: [],
            examples: [
                'Tampilkan distribusi ibu hamil risiko tinggi per kelurahan',
                'Berapa tren kasus TBC per bulan tahun ini?',
                'Kelurahan mana dengan ibu hamil KEK terbanyak?',
                'Ringkasan statistik ANC dan TBC secara keseluruhan',
                'Ibu hamil bekas sesar dan darah tinggi per kecamatan',
            ],

            init() {},

            async generate() {
                if (!this.prompt.trim() || this.loading) return;
                this.loading = true;
                this.error = null;
                this.dashboard = null;
                this.destroyCharts();

                try {
                    const res = await fetch('{{ route("ai.dashboard.generate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ prompt: this.prompt }),
                    });

                    const data = await res.json();
                    if (!data.success) throw new Error(data.message || 'Gagal generate');

                    this.dashboard = data.dashboard;
                    this.loading = false;

                    // Render charts setelah Alpine update DOM
                    await this.$nextTick();
                    this.renderCharts();
                } catch (e) {
                    this.error = e.message;
                    this.loading = false;
                }
            },

            destroyCharts() {
                this.chartInstances.forEach(c => c.destroy());
                this.chartInstances = [];
            },

            renderCharts() {
                if (!this.dashboard?.charts) return;
                const colorPalette = [
                    'rgba(99,102,241,0.85)', 'rgba(236,72,153,0.85)',
                    'rgba(20,184,166,0.85)', 'rgba(245,158,11,0.85)',
                    'rgba(139,92,246,0.85)', 'rgba(16,185,129,0.85)',
                    'rgba(239,68,68,0.85)',  'rgba(59,130,246,0.85)',
                ];

                this.dashboard.charts.forEach((chart, idx) => {
                    const canvas = document.getElementById('ai-chart-' + idx);
                    if (!canvas) return;

                    const colors = chart.labels?.map((_, i) => colorPalette[i % colorPalette.length]) ?? [];
                    const borderColors = colors.map(c => c.replace('0.85', '1'));

                    const instance = new Chart(canvas.getContext('2d'), {
                        type: chart.type || 'bar',
                        data: {
                            labels: chart.labels || [],
                            datasets: [{
                                label: chart.title,
                                data: chart.data || [],
                                backgroundColor: chart.type === 'doughnut' ? colors : colorPalette[idx % colorPalette.length],
                                borderColor: chart.type === 'doughnut' ? borderColors : colorPalette[idx % colorPalette.length].replace('0.85','1'),
                                borderWidth: 1.5,
                                borderRadius: chart.type === 'bar' ? 6 : 0,
                                tension: 0.4,
                                fill: chart.type === 'line',
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: chart.type === 'doughnut',
                                    labels: { color: 'rgba(255,255,255,0.7)', boxRadius: 4, font: { size: 11 } },
                                },
                            },
                            scales: chart.type === 'doughnut' ? {} : {
                                x: {
                                    ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 11 } },
                                    grid: { color: 'rgba(255,255,255,0.05)' },
                                },
                                y: {
                                    ticks: { color: 'rgba(255,255,255,0.5)', font: { size: 11 } },
                                    grid: { color: 'rgba(255,255,255,0.05)' },
                                    beginAtZero: true,
                                },
                            },
                        },
                    });
                    this.chartInstances.push(instance);
                });
            },

            kpiCardClass(color) {
                const map = {
                    blue:   'bg-blue-500/15 border-blue-400/25 text-blue-200',
                    green:  'bg-emerald-500/15 border-emerald-400/25 text-emerald-200',
                    red:    'bg-rose-500/15 border-rose-400/25 text-rose-200',
                    amber:  'bg-amber-500/15 border-amber-400/25 text-amber-200',
                    violet: 'bg-violet-500/15 border-violet-400/25 text-violet-200',
                    teal:   'bg-teal-500/15 border-teal-400/25 text-teal-200',
                    rose:   'bg-rose-500/15 border-rose-400/25 text-rose-200',
                    pink:   'bg-pink-500/15 border-pink-400/25 text-pink-200',
                    slate:  'bg-slate-500/15 border-slate-400/25 text-slate-200',
                };
                return map[color] || map.slate;
            },

            kpiIconBg(color) {
                const map = {
                    blue:   'bg-blue-500/20 text-blue-300',
                    green:  'bg-emerald-500/20 text-emerald-300',
                    red:    'bg-rose-500/20 text-rose-300',
                    amber:  'bg-amber-500/20 text-amber-300',
                    violet: 'bg-violet-500/20 text-violet-300',
                    teal:   'bg-teal-500/20 text-teal-300',
                    rose:   'bg-rose-500/20 text-rose-300',
                    pink:   'bg-pink-500/20 text-pink-300',
                    slate:  'bg-slate-500/20 text-slate-300',
                };
                return map[color] || map.slate;
            },

            kpiIcon(icon) {
                const icons = {
                    users: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                    chart: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
                    warning: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    heart: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
                    clipboard: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                    trending: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>',
                };
                return icons[icon] || icons.chart;
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
