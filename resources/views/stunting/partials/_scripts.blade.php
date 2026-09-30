        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            function stuntingDashboard() {
                return {
                    showGuideModal: false,
                    selectedDesa: '{{ $selectedDesa }}',
                    selectedBulan: '{{ $selectedBulan }}',
                    selectedTahun: '{{ $selectedTahun }}',
                    searchQuery: '',
                    stats: {},

                    // WhatsApp Direct Reminder (wa.me)
                    showWhatsAppModal: false,
                    targetPatient: null,
                    waPhone: '',
                    waTemplateType: 'posyandu_schedule',
                    waMessage: '',

                    // Import / Bulk Import modal
                    showImportModal: false,
                    importStep: 'pick',         // pick | processing | done
                    importLoading: false,
                    importSuccessMsg: '',
                    importError: '',
                    // bulk upload state
                    bulkFiles: [],
                    bulkResults: [],
                    bulkProgress: 0,
                    bulkProgressLabel: '',
                    bulkSummary: { inserted: 0, updated: 0, failed: 0 },
                    // legacy single-file (kept for compatibility)
                    importFile: null,
                    importFileName: '',
                    importPreview: {},
                    importTempToken: '',

                    // Clear massive modal
                    showClearMassiveModal: false,
                    clearMassiveKeyword: '',
                    isClearingMassive: false,
                    clearMassiveError: '',

                    // Toast Notification State
                    toast: {
                        show: false,
                        type: 'success',
                        title: '',
                        message: '',
                        timeout: null
                    },

                    notify(type, title, message) {
                        if (this.toast.timeout) clearTimeout(this.toast.timeout);
                        this.toast.type = type;
                        this.toast.title = title;
                        this.toast.message = message;
                        this.toast.show = true;
                        this.toast.timeout = setTimeout(() => {
                            this.toast.show = false;
                        }, 4000);
                    },

                    // Add & Edit & View Modal State
                    showAddModal: false,
                    addModalTab: 'manual', // 'manual' | 'ai'
                    isSubmittingNewPatient: false,
                    newPatient: {},
                    aiAddStep: 'prompt',   // 'prompt' | 'preview'
                    aiAddPrompt: '',
                    aiAddLoading: false,
                    aiAddError: '',

                    showEditModal: false,
                    isSubmittingEditPatient: false,
                    editingPatient: {},

                    showViewModal: false,
                    viewingPatient: null,

                    // Paginated patient data
                    patientsData: @json($patients),
                    perPage: 15,
                    isTableLoading: false,

                    // Charts
                    tbuChart: null,
                    bbuChart: null,
                    genderChart: null,
                    desaChart: null,
                    monthlyChart: null,

                    initDashboard() {
                        this.renderCharts();
                    },

                    formatDate(val) {
                        if (!val) return '-';
                        const s = String(val).split('T')[0].split('-');
                        if (s.length === 3) return s[2] + '/' + s[1] + '/' + s[0];
                        return val;
                    },

                    getTbuBadgeClass(val) {
                        if (val === 'Sangat Pendek') return 'bg-red-100 text-red-700 border border-red-200';
                        if (val === 'Pendek') return 'bg-amber-100 text-amber-700 border border-amber-200';
                        if (val === 'Normal') return 'bg-green-100 text-green-700 border border-green-200';
                        if (val === 'Tinggi') return 'bg-blue-100 text-blue-700 border border-blue-200';
                        return 'bg-slate-100 text-slate-600 border border-slate-200';
                    },

                    getBbuBadgeClass(val) {
                        if (['Sangat Kurang'].includes(val)) return 'bg-red-100 text-red-700 border border-red-200';
                        if (['Kurang'].includes(val)) return 'bg-amber-100 text-amber-700 border border-amber-200';
                        if (['Normal', 'Gizi Baik'].includes(val)) return 'bg-green-100 text-green-700 border border-green-200';
                        if (['Risiko Lebih', 'Risiko Gizi Lebih'].includes(val)) return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                        if (['Lebih', 'Gizi Lebih', 'Obesitas'].includes(val)) return 'bg-purple-100 text-purple-700 border border-purple-200';
                        return 'bg-slate-100 text-slate-600 border border-slate-200';
                    },

                    getBbtbBadgeClass(val) {
                        if (['Gizi Kurang', 'Kurang'].includes(val)) return 'bg-amber-100 text-amber-700 border border-amber-200';
                        if (['Gizi Baik', 'Normal'].includes(val)) return 'bg-green-100 text-green-700 border border-green-200';
                        if (['Risiko Gizi Lebih'].includes(val)) return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                        if (['Gizi Lebih', 'Obesitas'].includes(val)) return 'bg-purple-100 text-purple-700 border border-purple-200';
                        return 'bg-slate-100 text-slate-600 border border-slate-200';
                    },

                    applyFilters(page = 1) {
                        this.isTableLoading = true;
                        const params = new URLSearchParams({
                            desa: this.selectedDesa,
                            bulan: this.selectedBulan,
                            tahun: this.selectedTahun,
                            search: this.searchQuery,
                            page: page,
                            per_page: this.perPage,
                        });
                        fetch(`{{ route('stunting.stats.json') }}?${params}`)
                            .then(r => r.json())
                            .then(data => {
                                this.stats = data;
                                if (data.patients) {
                                    this.patientsData = data.patients;
                                }
                                this.updateCharts(data);
                            })
                            .finally(() => {
                                this.isTableLoading = false;
                            });
                    },

                    changePage(page) {
                        if (page < 1 || (this.patientsData && page > this.patientsData.last_page)) return;
                        this.applyFilters(page);
                    },

                    changePerPage() {
                        this.applyFilters(1);
                    },

                    clearFilters() {
                        this.selectedDesa = '';
                        this.selectedBulan = '01';
                        this.selectedTahun = '{{ $selectedTahun ?: (!empty($yearList) ? $yearList[0] : date('Y')) }}';
                        this.searchQuery = '';
                        this.applyFilters(1);
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
                                maintainAspectRatio: false,
                                scales: {
                                    x: { stacked: true, grid: { display: false } },
                                    y: { stacked: true, ticks: { stepSize: 1 } }
                                },
                                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
                            }
                        });

                        // Monthly Trend Candlestick Chart
                        const monthlyData = @json($monthlyTrend ?? []);
                        const monthLabels = monthlyData.map(m => {
                            if (!m.bulan_label) return '-';
                            const parts = m.bulan_label.split('-');
                            if (parts.length === 2) {
                                const d = new Date(parts[0], parseInt(parts[1]) - 1, 1);
                                return d.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
                            }
                            return m.bulan_label;
                        });

                        // Candle builder helper
                        const buildCandleDataset = (dataList) => {
                            return dataList.map(m => {
                                const total = Number(m.total) || 0;
                                const stunting = Number(m.stunting) || 0;
                                const nonStunting = Math.max(0, total - stunting);

                                // High: Total Pengukuran balita
                                // Low: Kasus Stunting
                                // Open: Total pengukuran
                                // Close: Balita normal/non-stunting
                                const isBullish = nonStunting >= stunting; // Mayoritas terkendali/sehat
                                const bodyMin = Math.min(stunting, nonStunting);
                                const bodyMax = Math.max(stunting, nonStunting);

                                return {
                                    total: total,
                                    stunting: stunting,
                                    nonStunting: nonStunting,
                                    high: total,
                                    low: stunting,
                                    open: total,
                                    close: nonStunting,
                                    isBullish: isBullish,
                                    // floating bar range for candle body [min, max]
                                    barRange: [bodyMin, bodyMax]
                                };
                            });
                        };

                        const candleDataset = buildCandleDataset(monthlyData);

                        // Custom Plugin to draw candlestick wicks (sumbu lilin atas dan bawah)
                        const candleWickPlugin = {
                            id: 'candleWickPlugin',
                            afterDatasetsDraw(chart) {
                                const ctx = chart.ctx;
                                const meta = chart.getDatasetMeta(0);
                                if (!meta || !meta.data) return;

                                const yScale = chart.scales.y;
                                const candleMeta = chart._candleMeta || [];

                                ctx.save();
                                meta.data.forEach((bar, index) => {
                                    const item = candleMeta[index];
                                    if (!item) return;

                                    const x = bar.x;
                                    const yHigh = yScale.getPixelForValue(item.high);
                                    const yLow = yScale.getPixelForValue(item.low);
                                    const color = item.isBullish ? '#10b981' : '#f43f5e';

                                    ctx.strokeStyle = color;
                                    ctx.lineWidth = 2;
                                    ctx.beginPath();
                                    // Sumbu vertikal dari High (Total Pengukuran) ke Low (Stunting)
                                    ctx.moveTo(x, yHigh);
                                    ctx.lineTo(x, yLow);
                                    ctx.stroke();

                                    // Top whisker cap
                                    ctx.beginPath();
                                    ctx.moveTo(x - 5, yHigh);
                                    ctx.lineTo(x + 5, yHigh);
                                    ctx.stroke();

                                    // Bottom whisker cap
                                    ctx.beginPath();
                                    ctx.moveTo(x - 5, yLow);
                                    ctx.lineTo(x + 5, yLow);
                                    ctx.stroke();
                                });
                                ctx.restore();
                            }
                        };

                        const ctxMonthly = document.getElementById('monthlyChart');
                        if (ctxMonthly) {
                            this.monthlyChart = new Chart(ctxMonthly, {
                                type: 'bar',
                                plugins: [candleWickPlugin],
                                data: {
                                    labels: monthLabels,
                                    datasets: [
                                        {
                                            label: 'Rentang Lilin (Body)',
                                            data: candleDataset.map(c => c.barRange),
                                            backgroundColor: candleDataset.map(c => c.isBullish ? 'rgba(16, 185, 129, 0.85)' : 'rgba(244, 63, 94, 0.85)'),
                                            borderColor: candleDataset.map(c => c.isBullish ? '#059669' : '#e11d48'),
                                            borderWidth: 1.5,
                                            borderRadius: 4,
                                            borderSkipped: false,
                                            barPercentage: 0.45,
                                            categoryPercentage: 0.6,
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    interaction: {
                                        mode: 'index',
                                        intersect: false,
                                    },
                                    scales: {
                                        x: {
                                            grid: { display: false },
                                            ticks: { font: { size: 11 } }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            ticks: { stepSize: 1, font: { size: 11 } },
                                            title: { display: true, text: 'Jumlah Balita', font: { size: 11, weight: 'bold' } }
                                        }
                                    },
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                            titleFont: { size: 12, weight: 'bold' },
                                            bodyFont: { size: 11 },
                                            padding: 12,
                                            cornerRadius: 10,
                                            callbacks: {
                                                title: (items) => {
                                                    return 'Bulan: ' + (items[0]?.label || '');
                                                },
                                                label: (item) => {
                                                    const candle = (chartMonthlyChart._candleMeta || [])[item.dataIndex];
                                                    if (!candle) return '';
                                                    return [
                                                        `[High] Total Pengukuran : ${candle.total} balita`,
                                                        `[Open] Balita Normal : ${candle.nonStunting} balita`,
                                                        `[Close] Kasus Stunting : ${candle.stunting} balita`,
                                                        `Status Tren : ${candle.isBullish ? 'Terkendali / Positif' : 'Perhatian Khusus'}`
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                            const chartMonthlyChart = this.monthlyChart;
                            chartMonthlyChart._candleMeta = candleDataset;
                            this._buildCandleDataset = buildCandleDataset;
                        }
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
                        if (this.monthlyChart && data.monthlyTrend) {
                            const newMonthLabels = data.monthlyTrend.map(m => {
                                if (!m.bulan_label) return '-';
                                const parts = m.bulan_label.split('-');
                                if (parts.length === 2) {
                                    const d = new Date(parts[0], parseInt(parts[1]) - 1, 1);
                                    return d.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
                                }
                                return m.bulan_label;
                            });

                            const newCandleData = this._buildCandleDataset ? this._buildCandleDataset(data.monthlyTrend) : [];
                            this.monthlyChart._candleMeta = newCandleData;
                            this.monthlyChart.data.labels = newMonthLabels;
                            this.monthlyChart.data.datasets[0].data = newCandleData.map(c => c.barRange);
                            this.monthlyChart.data.datasets[0].backgroundColor = newCandleData.map(c => c.isBullish ? 'rgba(16, 185, 129, 0.85)' : 'rgba(244, 63, 94, 0.85)');
                            this.monthlyChart.data.datasets[0].borderColor = newCandleData.map(c => c.isBullish ? '#059669' : '#e11d48');
                            this.monthlyChart.update();
                        }
                    },

                    // ── Bulk Import helpers ────────────────────────────────────────────

                    handleBulkFileSelect(event) {
                        const files = Array.from(event.target.files);
                        // Merge dengan yang sudah ada, deduplicate by name
                        const existing = new Set(this.bulkFiles.map(f => f.name));
                        files.forEach(f => {
                            if (!existing.has(f.name)) {
                                this.bulkFiles.push(f);
                                existing.add(f.name);
                            }
                        });
                        this.importError = '';
                        // Reset input agar bisa pilih file yang sama lagi nanti
                        event.target.value = '';
                    },

                    async startBulkImport() {
                        if (this.bulkFiles.length === 0 || this.importLoading) return;
                        this.importLoading = true;
                        this.importError = '';
                        this.importStep = 'processing';
                        this.bulkProgress = 5;
                        this.bulkProgressLabel = 'Mengunggah dan memproses file...';

                        const fd = new FormData();
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        this.bulkFiles.forEach(f => fd.append('files[]', f));

                        // Simulate progress while waiting
                        const progressInterval = setInterval(() => {
                            if (this.bulkProgress < 85) this.bulkProgress += 3;
                        }, 400);

                        try {
                            const res = await fetch('{{ route("stunting.import.bulk") }}', {
                                method: 'POST',
                                body: fd,
                            });
                            const json = await res.json();
                            clearInterval(progressInterval);
                            this.bulkProgress = 100;
                            this.bulkResults = json.results || [];
                            this.importSuccessMsg = json.message || 'Import selesai.';
                            this.bulkSummary = {
                                inserted: json.inserted ?? 0,
                                updated: json.updated ?? 0,
                                failed: json.failed ?? 0,
                            };
                            this.importStep = 'done';
                        } catch (e) {
                            clearInterval(progressInterval);
                            this.importError = 'Terjadi kesalahan jaringan: ' + e.message;
                            this.importStep = 'pick';
                        } finally {
                            this.importLoading = false;
                        }
                    },

                    resetBulkImport() {
                        this.importStep = 'pick';
                        this.bulkFiles = [];
                        this.bulkResults = [];
                        this.bulkProgress = 0;
                        this.bulkProgressLabel = '';
                        this.bulkSummary = { inserted: 0, updated: 0, failed: 0 };
                        this.importSuccessMsg = '';
                        this.importError = '';
                        const inp = document.getElementById('stunting-bulk-file');
                        if (inp) inp.value = '';
                    },

                    // ── Legacy single-file helpers (kept for compatibility) ─────────────
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        this.importFile = file;
                        this.importFileName = file.name;
                        this.importError = '';
                    },

                    async previewImport() {
                        if (!this.importFile) return;
                        this.importLoading = true;
                        this.importError = '';
                        const fd = new FormData();
                        fd.append('file', this.importFile);
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        try {
                            const res = await fetch('{{ route("stunting.import.preview") }}', { method: 'POST', body: fd });
                            const json = await res.json();
                            if (json.success) {
                                this.importPreview = json.data;
                                this.importTempToken = json.data.temp_token;
                                this.importStep = 'preview';
                            } else {
                                this.importError = json.message || 'Gagal membaca file.';
                            }
                        } catch (e) {
                            this.importError = 'Terjadi kesalahan: ' + e.message;
                        } finally {
                            this.importLoading = false;
                        }
                    },

                    async commitImport() {
                        this.importLoading = true;
                        this.importError = '';
                        const fd = new FormData();
                        fd.append('temp_token', this.importTempToken);
                        fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        try {
                            const res = await fetch('{{ route("stunting.import.commit") }}', { method: 'POST', body: fd });
                            const json = await res.json();
                            if (json.success) {
                                this.importSuccessMsg = json.message;
                                this.importStep = 'done';
                            } else {
                                this.importError = json.message || 'Gagal menyimpan data.';
                            }
                        } catch (e) {
                            this.importError = 'Terjadi kesalahan: ' + e.message;
                        } finally {
                            this.importLoading = false;
                        }
                    },


                    openClearMassiveModal() {
                        this.clearMassiveKeyword = '';
                        this.clearMassiveError = '';
                        this.showClearMassiveModal = true;
                    },

                    async executeClearMassive() {
                        if (this.clearMassiveKeyword.trim() !== 'HAPUS' || this.isClearingMassive) return;
                        this.isClearingMassive = true;
                        this.clearMassiveError = '';
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').content;
                            const res = await fetch('{{ route("stunting.clear-massive") }}', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.showClearMassiveModal = false;
                                location.reload();
                            } else {
                                this.clearMassiveError = data.message || 'Gagal mengosongkan data.';
                            }
                        } catch (e) {
                            this.clearMassiveError = 'Terjadi kesalahan sistem: ' + e.message;
                        } finally {
                            this.isClearingMassive = false;
                        }
                    },

                    // ── Patient CRUD & Modal Helpers ───────────────────────────────────
                    openAddModal() {
                        this.addModalTab = 'manual';
                        this.aiAddStep = 'prompt';
                        this.aiAddPrompt = '';
                        this.aiAddError = '';
                        this.newPatient = {
                            nama: '',
                            nik: '',
                            jenis_kelamin: '',
                            tanggal_lahir: '',
                            nama_ortu: '',
                            desa: this.selectedDesa || '',
                            posyandu: '',
                            puskesmas: '',
                            berat: '',
                            tinggi: '',
                            lila: '',
                            tbu_kategori: 'Normal'
                        };
                        this.showAddModal = true;
                    },

                    async runStuntingAiParse() {
                        if (this.aiAddLoading || this.aiAddPrompt.trim().length < 10) return;
                        this.aiAddLoading = true;
                        this.aiAddError = '';
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`{{ route('stunting.ai.parse') }}`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                body: JSON.stringify({ prompt: this.aiAddPrompt })
                            });
                            const result = await res.json();
                            if (result.success && result.data) {
                                const clean = {};
                                Object.entries(result.data).forEach(([k, v]) => {
                                    if (v !== null && v !== '') clean[k] = v;
                                });
                                this.newPatient = clean;
                                this.aiAddStep = 'preview';
                            } else {
                                this.aiAddError = result.message || 'AI tidak dapat membaca format data balita ini.';
                            }
                        } catch (e) {
                            this.aiAddError = 'Terjadi kesalahan saat memproses dengan AI: ' + e.message;
                        } finally {
                            this.aiAddLoading = false;
                        }
                    },

                    async saveNewPatient() {
                        if (this.isSubmittingNewPatient) return;
                        this.isSubmittingNewPatient = true;
                        this.aiAddError = '';
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`{{ route('stunting.patients.store') }}`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                body: JSON.stringify(this.newPatient)
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.showAddModal = false;
                                this.applyFilters(1);
                                const title = data.action === 'updated' ? 'Data Diperbarui' : 'Balita Ditambahkan';
                                const msg = data.message || (data.action === 'updated' ? 'Data balita berhasil diperbarui.' : 'Data balita baru berhasil tersimpan.');
                                this.notify('success', title, msg);
                            } else {
                                this.aiAddError = data.message || 'Gagal menyimpan data.';
                            }
                        } catch (e) {
                            this.aiAddError = 'Gagal menyimpan: ' + e.message;
                        } finally {
                            this.isSubmittingNewPatient = false;
                        }
                    },

                    openEditModal(p) {
                        this.editingPatient = JSON.parse(JSON.stringify(p));
                        // format dates for HTML input
                        if (this.editingPatient.tanggal_lahir) {
                            this.editingPatient.tanggal_lahir = String(this.editingPatient.tanggal_lahir).split('T')[0];
                        }
                        if (this.editingPatient.tanggal_pengukuran) {
                            this.editingPatient.tanggal_pengukuran = String(this.editingPatient.tanggal_pengukuran).split('T')[0];
                        }
                        this.showEditModal = true;
                    },

                    async saveEditPatient() {
                        if (this.isSubmittingEditPatient || !this.editingPatient.id) return;
                        this.isSubmittingEditPatient = true;
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`/stunting/patients/${this.editingPatient.id}`, {
                                method: 'PUT',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                                body: JSON.stringify(this.editingPatient)
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.showEditModal = false;
                                this.applyFilters(this.patientsData.current_page || 1);
                                this.notify('success', 'Data Diperbarui', 'Rekam balita berhasil diperbarui secara realtime.');
                            } else {
                                this.notify('error', 'Gagal Simpan', data.message || 'Gagal memperbarui data.');
                            }
                        } catch (e) {
                            this.notify('error', 'Koneksi Bermasalah', 'Kesalahan sistem: ' + e.message);
                        } finally {
                            this.isSubmittingEditPatient = false;
                        }
                    },

                    openViewModal(p) {
                        this.viewingPatient = p;
                        this.showViewModal = true;
                    },

                    async deletePatient(patient) {
                        if (!confirm(`Hapus rekam data balita "${patient.nama}"?`)) return;

                        try {
                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const res = await fetch(`/stunting/patients/${patient.id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                            });
                            const data = await res.json();
                            if (data.success) {
                                this.applyFilters(this.patientsData.current_page || 1);
                                this.notify('success', 'Data Dihapus', `Data balita "${patient.nama}" berhasil dihapus.`);
                            } else {
                                this.notify('error', 'Gagal Menghapus', data.message || 'Gagal menghapus data.');
                            }
                        } catch (e) {
                            this.notify('error', 'Kesalahan', 'Gagal menghapus: ' + e.message);
                        }
                    },

                    // ================= INOVASI: WHATSAPP DIRECT REMINDER (wa.me) =================
                    openWhatsAppModal(patient) {
                        if (!patient) return;
                        this.targetPatient = JSON.parse(JSON.stringify(patient));
                        this.waTemplateType = 'posyandu_schedule';
                        this.waPhone = patient.no_telepon || '';
                        this.showWhatsAppModal = true;
                        this.prepareWaMessage();
                    },

                    prepareWaMessage() {
                        if (!this.targetPatient) return;
                        const p = this.targetPatient;
                        const namaAnak = p.nama || 'Ananda';
                        const namaOrtu = p.nama_ortu ? `Bpk/Ibu ${p.nama_ortu}` : 'Bpk/Ibu Orang Tua Balita';
                        const posyanduStr = p.posyandu ? `Posyandu ${p.posyandu}` : 'Posyandu terdekat';
                        const desaStr = p.desa ? `Desa/Kelurahan ${p.desa}` : 'Puskesmas';
                        const bbStr = p.berat ? `${Number(p.berat).toFixed(2)} kg` : '-';
                        const tbStr = p.tinggi ? `${Number(p.tinggi).toFixed(1)} cm` : '-';
                        const statusTbu = p.tbu_kategori || 'Perlu Perhatian';

                        if (this.waTemplateType === 'stunting_pmt') {
                            this.waMessage = `Halo ${namaOrtu},\n\nSalam Peduli Tumbuh Kembang dari Tim Pencegahan Stunting (SICEPOT - ${desaStr}).\n\nMengingatkan perkembangan gizi ${namaAnak} (Status TB/U: ${statusTbu}, TB: ${tbStr}, BB: ${bbStr}).\n\nUntuk mengejar pertumbuhan optimal, mohon pastikan:\n1. Pemberian Makanan Tambahan (PMT) kaya protein hewani (telur, ikan, ayam, daging, susu) dihabiskan setiap hari.\n2. Pola asuh makan gizi seimbang dan menjaga kebersihan sanitasi lingkungan rumah.\n3. Pantau kenaikan berat badan secara disiplin setiap bulan.\n\nMari bersama kita dukung tumbuh kembang ${namaAnak} agar sehat, cerdas, dan ceria! Terima kasih.`;
                        } else if (this.waTemplateType === 'weight_faltering') {
                            this.waMessage = `PERHATIAN PERTUMBUHAN BALITA - ${desaStr} (SICEPOT)\n\nKepada Yth. ${namaOrtu} (${namaAnak}),\n\nBerdasarkan hasil penimbangan terakhir, berat badan ${namaAnak} tercatat tidak mengalami kenaikan yang adekuat / berada di bawah grafik tumbuh kembang (BB saat ini: ${bbStr}).\n\nPENTING:\nKondisi berat badan seret (Weight Faltering) perlu penanganan dini agar tidak berlanjut menjadi stunting.\n\nMohon segera membawa ${namaAnak} ke ${posyanduStr} atau Puskesmas terdekat untuk konsultasi gizi dan evaluasi kesehatan bersama petugas kami. Layanan ini bebas biaya. Terima kasih!`;
                        } else if (this.waTemplateType === 'referral_spa') {
                            this.waMessage = `PEMBERITAHUAN RUJUKAN & PEMERIKSAAN LANJUTAN - SICEPOT\n\nKepada Yth. ${namaOrtu},\n\nSalam sehat dari Tim Kesehatan Balita ${desaStr}.\nTerkait pemantauan indikator tumbuh kembang ${namaAnak}, petugas merekomendasikan pemeriksaan kesehatan lanjutan / konsultasi Dokter Spesialis Anak (Sp.A) di RS / Puskesmas rujukan.\n\nPemeriksaan ini bertujuan memastikan tidak adanya penyakit penyerta (seperti infeksi atau anemia) serta menentukan terapi nutrisi spesifik.\n\nSilakan menghubungi petugas gizi atau bidan desa kami di Puskesmas untuk bantuan proses administrasi rujukan. Terima kasih.`;
                        } else {
                            // Default: Jadwal Penimbangan & Posyandu Rutin
                            this.waMessage = `Halo ${namaOrtu},\n\nSalam hangat dari Kader & Tim Kesehatan ${posyanduStr}, ${desaStr} (SICEPOT).\n\nMengingatkan jadwal penimbangan dan pengukuran rutin tumbuh kembang ${namaAnak} di Posyandu bulan ini.\n\nPemeriksaan rutin (BB, TB, LiLA, vitamin A, dan imunisasi) sangat penting untuk memastikan pertumbuhan anak tetap terpantau dengan baik.\n\nJangan lupa membawa Buku KIA saat datang ke Posyandu ya, Bunda/Ayah. Sampai jumpa di Posyandu!`;
                        }
                    },

                    sendWhatsAppMessage() {
                        let clean = String(this.waPhone || '').replace(/\D+/g, '');
                        if (clean.startsWith('0')) clean = '62' + clean.substring(1);
                        else if (clean.startsWith('8')) clean = '62' + clean;
                        else if (!clean.startsWith('62')) clean = '62' + clean;

                        const url = `https://wa.me/${clean}?text=${encodeURIComponent(this.waMessage)}`;
                        window.open(url, '_blank');
                    },
                };
            }
        </script>
