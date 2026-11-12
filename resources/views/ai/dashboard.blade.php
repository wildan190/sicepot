<x-app-layout>
    <div x-data="aiDashboard()" x-init="init()">

        {{-- ── Header ────────────────────────────────────────────────────────── --}}
        <div class="border-b border-ant-split bg-ant-container">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-ant-lg bg-primary-50 text-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </span>
                    <div>
                        <h1 class="ant-page-title">AI Dashboard Generator</h1>
                        <p class="ant-page-subtitle">Ketik pertanyaan, Gemini AI buat dashboardnya otomatis</p>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <span class="ant-tag ant-tag-green">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-success"></span>
                            Gemini 2.5 Flash
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-6 space-y-6 sm:px-6 lg:px-8">

            {{-- ── Prompt Bar ────────────────────────────────────────────────── --}}
            <div class="ant-card">
                <div class="ant-card-body">
                    <label class="ant-label font-medium">
                        Apa yang ingin kamu analisis?
                    </label>
                    <div class="flex gap-3">
                        <textarea
                            x-model="prompt"
                            @keydown.ctrl.enter="generate()"
                            placeholder="Contoh: Tampilkan distribusi ibu hamil risiko tinggi per kelurahan dan tren per bulan..."
                            rows="3"
                            class="ant-input flex-1 !h-auto py-2.5 resize-none"
                        ></textarea>
                        <button
                            @click="generate()"
                            :disabled="loading || !prompt.trim()"
                            class="ant-btn ant-btn-primary flex-col !h-auto min-w-[90px] py-2.5"
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
                    <p class="ant-text-secondary mt-2">Tekan Ctrl+Enter untuk generate</p>

                    {{-- Contoh Prompt Chips --}}
                    <div class="mt-4">
                        <p class="ant-text-secondary mb-2 font-medium">Contoh prompt:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="example in examples" :key="example">
                                <button
                                    @click="prompt = example"
                                    class="ant-btn ant-btn-default ant-btn-sm !h-auto py-1"
                                    x-text="example"
                                ></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Error State ──────────────────────────────────────────────── --}}
            <template x-if="error">
                <div class="ant-alert ant-alert-error">
                    <svg class="w-5 h-5 shrink-0 mt-0.5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-[13px] text-ant-title">Gagal generate dashboard</p>
                        <p class="text-[13px] mt-0.5 text-danger-600" x-text="error"></p>
                    </div>
                </div>
            </template>

            {{-- ── Loading Shimmer ──────────────────────────────────────────── --}}
            <template x-if="loading">
                <div class="space-y-6 animate-pulse">
                    <div class="h-8 w-64 rounded-ant bg-ant-split"></div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <template x-for="i in 4" :key="i">
                            <div class="h-28 rounded-ant-lg border border-ant-split bg-ant-split/60"></div>
                        </template>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="h-72 rounded-ant-lg border border-ant-split bg-ant-split/60"></div>
                        <div class="h-72 rounded-ant-lg border border-ant-split bg-ant-split/60"></div>
                    </div>
                </div>
            </template>

            {{-- ── Generated Dashboard ──────────────────────────────────────── --}}
            <template x-if="dashboard && !loading">
                <div class="space-y-6">

                    {{-- Title & Description --}}
                    <div>
                        <h2 class="ant-page-title" x-text="dashboard.title"></h2>
                        <p class="ant-page-subtitle" x-text="dashboard.description"></p>
                    </div>

                    {{-- KPI Cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        <template x-for="kpi in dashboard.kpis" :key="kpi.label">
                            <div :class="kpiCardClass(kpi.color)"
                                class="rounded-ant-lg p-4 border flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <span class="text-[12px] font-semibold uppercase tracking-wider"
                                        x-text="kpi.label"></span>
                                    <span class="p-1.5 rounded-ant-sm" :class="kpiIconBg(kpi.color)"
                                        x-html="kpiIcon(kpi.icon)"></span>
                                </div>
                                <div class="mt-3">
                                    <span class="text-[22px] font-semibold text-ant-title" x-text="formatKpiValue(kpi.value)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Charts --}}
                    <div :class="dashboard.charts.length === 1 ? 'grid grid-cols-1' : 'grid grid-cols-1 lg:grid-cols-2'"
                        class="gap-6">
                        <template x-for="(chart, idx) in dashboard.charts" :key="idx">
                            <div class="ant-card">
                                <div class="ant-card-head">
                                    <h3 class="ant-card-title" x-text="chart.title"></h3>
                                </div>
                                <div class="ant-card-body">
                                    <div class="relative h-72">
                                        <canvas :id="'ai-chart-' + idx"></canvas>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Insights --}}
                    <template x-if="dashboard.insights && dashboard.insights.length">
                        <div class="ant-alert ant-alert-info">
                            <div class="w-full">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                        </path>
                                    </svg>
                                    <h3 class="text-[13px] font-semibold text-ant-title">Insights dari Gemini AI</h3>
                                </div>
                                <ul class="space-y-2.5">
                                    <template x-for="(insight, i) in dashboard.insights" :key="i">
                                        <li class="flex items-start gap-2.5 text-[13px] text-ant-text">
                                            <span class="shrink-0 w-5 h-5 rounded-full bg-primary-50 text-primary text-[11px] flex items-center justify-center font-semibold mt-0.5"
                                                x-text="i + 1"></span>
                                            <span x-text="insight"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </template>

                </div>
            </template>

            {{-- ── Empty State ─────────────────────────────────────────────── --}}
            <template x-if="!dashboard && !loading && !error">
                <div class="text-center py-20">
                    <div class="inline-flex p-6 rounded-ant-lg bg-ant-container border border-ant-split mb-6">
                        <svg class="w-14 h-14 text-ant-disabled" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-[15px] font-medium text-ant-secondary mb-1">Dashboard belum di-generate</h3>
                    <p class="ant-text-secondary">Ketik pertanyaan di atas lalu klik Generate</p>
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
                    'rgba(22,119,255,0.85)',  'rgba(82,196,26,0.85)',
                    'rgba(250,173,20,0.85)',  'rgba(255,77,79,0.85)',
                    'rgba(114,46,209,0.85)',  'rgba(19,194,194,0.85)',
                    'rgba(235,47,150,0.85)',  'rgba(250,140,22,0.85)',
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
                                    labels: { color: 'rgba(0,0,0,0.65)', boxRadius: 4, font: { size: 11 } },
                                },
                            },
                            scales: chart.type === 'doughnut' ? {} : {
                                x: {
                                    ticks: { color: 'rgba(0,0,0,0.45)', font: { size: 11 } },
                                    grid: { color: 'rgba(0,0,0,0.06)' },
                                },
                                y: {
                                    ticks: { color: 'rgba(0,0,0,0.45)', font: { size: 11 } },
                                    grid: { color: 'rgba(0,0,0,0.06)' },
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
                    blue:   'bg-primary-50 border-primary-100 text-primary-600',
                    green:  'bg-success-50 border-success-100 text-success-600',
                    red:    'bg-danger-50 border-danger-100 text-danger-600',
                    amber:  'bg-warning-50 border-warning-100 text-warning-600',
                    violet: 'bg-[#f9f0ff] border-[#efdbff] text-[#531dab]',
                    teal:   'bg-primary-50 border-primary-100 text-primary-600',
                    rose:   'bg-danger-50 border-danger-100 text-danger-600',
                    pink:   'bg-[#f9f0ff] border-[#efdbff] text-[#531dab]',
                    slate:  'bg-ant-split border-ant-split text-ant-text',
                };
                return map[color] || map.slate;
            },

            kpiIconBg(color) {
                const map = {
                    blue:   'bg-primary-100 text-primary-600',
                    green:  'bg-success-100 text-success-600',
                    red:    'bg-danger-100 text-danger-600',
                    amber:  'bg-warning-100 text-warning-600',
                    violet: 'bg-[#efdbff] text-[#531dab]',
                    teal:   'bg-primary-100 text-primary-600',
                    rose:   'bg-danger-100 text-danger-600',
                    pink:   'bg-[#efdbff] text-[#531dab]',
                    slate:  'bg-ant-split text-ant-text',
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

            formatKpiValue(val) {
                if (val === null || val === undefined) return '0';
                if (typeof val === 'number') return Number(val).toLocaleString('id-ID');
                const num = parseFloat(val);
                if (!isNaN(num) && String(num) === String(val).trim()) {
                    return Number(num).toLocaleString('id-ID');
                }
                return String(val);
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
