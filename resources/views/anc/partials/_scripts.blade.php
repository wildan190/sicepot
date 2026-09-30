    <!-- JAVASCRIPT & REALTIME CONTROLLER -->
    <script>
        // Non-reactive storage for Chart.js and Leaflet instances to prevent Alpine Proxy recursion
        const ancCharts = {
            kelurahan: null,
            monthly: null,
            age: null,
            poedji: null,
            map: null,
            markersLayer: null,
            fasyanksLayer: null,
        };

        function ancDashboard() {
            return {
                selectedKabupaten: '{{ $selectedKabupaten }}',
                selectedKelurahan: '{{ $selectedKelurahan }}',
                selectedBulan: '{{ $selectedBulan }}',
                searchQuery: '',
                kelurahanList: [],
                kpi: @json($kpi),
                kelurahanChartData: @json($kelurahan_chart),
                monthlyChartData: @json($monthly_chart),
                ageChartData: @json($age_chart),
                poedjiChartData: @json($poedji_chart),
                patientsData: @json($patients),
                mapData: @json($map_data),
                imminentDeliveries: @json($imminent_deliveries ?? []),
                upcomingDeliveries: @json($upcoming_deliveries ?? []),
                sirineAudio: null,
                hasTriggeredInitialAlert: false,

                // Import Modal State
                showImportModal: false,
                isDragging: false,
                isParsing: false,
                isSubmittingImport: false,
                importPreview: null,

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

                // Edit & Add Modal State
                showEditModal: false,
                showAddModal: false,
                isSubmittingNewPatient: false,
                editingPatient: {},
                // AI Prompt Add state
                aiAddStep: 'prompt',   // 'prompt' | 'preview'
                aiAddPrompt: '',
                aiAddLoading: false,
                aiAddError: '',
                newPatient: {},

                // Realtime Birth Recording & PieSocket Alert State
                showRecordBirthModal: false,
                isSubmittingBirth: false,
                isTestingBirthAlert: false,

                // Clear All Data State
                showClearAllModal: false,
                isClearingAll: false,
                clearConfirmText: '',
                targetBirthPatient: null,
                birthForm: {
                    tanggal_bersalin: new Date().toISOString().substring(0, 10),
                    tempat_bersalin: 'Puskesmas PONED',
                    penolong_persalinan: 'Bidan Desa / Nakes',
                    kondisi_bayi: 'Lahir Hidup, Sehat & Menangis Kuat',
                    berat_lahir_bayi: '',
                    komplikasi_persalinan: ''
                },

                // GIS Map Widget Config (per-kecamatan, saveable)
                ancMapCfgOpen: false,
                ancMapCfgSaved: false,
                ancMapLoading: false,
                ancMapKecamatanList: [],
                ancMapCfg: {
                    kecamatan: '',
                    viewMode: 'markers',
                    height: 460,
                    showKEK: false,
                    showAnemia: false,
                    showHiper: false,
                    showImminent: false,
                    showFasyankes: true,
                },

                // Legacy (kept for compat)
                mapViewMode: 'markers',

                // Target Patient & Innovation Modal States
                targetPatient: null,
                showWhatsAppModal: false,
                waRecipient: 'ibu',
                waPhone: '',
                waTemplateType: 'anc_checkup',
                waMessage: '',

                showDuplicateModal: false,
                isLoadingDuplicates: false,
                duplicateResults: [],

                showTimelineModal: false,
                isLoadingTimeline: false,
                timelineData: [],

                showAiModal: false,
                isLoadingAi: false,
                aiAnalysisResult: '',

                // View / Detail Modal State
                showViewModal: false,
                viewingPatient: null,

                formatDate(dateVal) {
                    if (!dateVal) return '-';
                    // If contains ISO time e.g. 2026-08-30T00:00:00...
                    const raw = String(dateVal).split('T')[0];
                    const parts = raw.split('-');
                    if (parts.length === 3) {
                        const months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        const day = parseInt(parts[2], 10);
                        const monthIdx = parseInt(parts[1], 10);
                        const year = parts[0];
                        if (monthIdx >= 1 && monthIdx <= 12 && !isNaN(day)) {
                            return `${day} ${months[monthIdx]} ${year}`;
                        }
                        return raw;
                    }
                    return raw;
                },

                openViewModal(patient) {
                    this.viewingPatient = JSON.parse(JSON.stringify(patient));
                    this.showViewModal = true;
                },

                openWhatsAppModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.waRecipient = 'ibu';
                    this.waTemplateType = 'anc_checkup';
                    this.showWhatsAppModal = true;
                    this.prepareWaMessage();
                },

                prepareWaMessage() {
                    if (!this.targetPatient) return;
                    const p = this.targetPatient;
                    const hplStr = p.hpl ? String(p.hpl).substring(0, 10) : '-';
                    const kelStr = p.kelurahan || 'Puskesmas';

                    if (this.waRecipient === 'ibu') {
                        this.waPhone = p.no_telepon || '';
                        if (this.waTemplateType === 'h1_alert') {
                            this.waMessage = `Halo Ibu ${p.nama_lengkap || ''},\n\nPemberitahuan dari Tim Pelayanan KIA ${kelStr} (SICEPOT):\nBerdasarkan catatan rekam medis, Hari Perkiraan Lahir (HPL) Anda diperkirakan BESOK / Segera (${hplStr}).\n\nMohon pastikan:\n1. Buku KIA & berkas BPJS/KTP sudah siap.\n2. Tas persalinan ibu & bayi siap dibawa.\n3. Suami/Keluarga siaga mendampingi ke faskes rujukan persalinan.\n\nBila mengalami kontraksi teratur atau keluar cairan/flek, segera kunjungi fasyankes terdekat. Semoga persalinan lancar & sehat selalu!`;
                        } else if (this.waTemplateType === 'kek_nutrition') {
                            this.waMessage = `Halo Ibu ${p.nama_lengkap || ''},\n\nPengingat Kesehatan dari Puskesmas ${kelStr}:\nBerdasarkan hasil pengukuran LiLA Anda (${p.lila || '-'} cm), Anda memerlukan asupan nutrisi ekstra untuk mendukung tumbuh kembang janin yang optimal.\n\nMohon rutin mengonsumsi:\n- Makanan tinggi protein (telur, ikan, ayam, tahu, tempe)\n- Makanan Tambahan (PMT) dari puskesmas\n- Tablet Tambah Darah (TTD) 1 tablet setiap malam\n\nMari bersama wujudkan kehamilan sehat bebas risiko!`;
                        } else {
                            this.waMessage = `Halo Ibu ${p.nama_lengkap || ''},\n\nSalam hangat dari Petugas Kesehatan Puskesmas ${kelStr} (SICEPOT).\nMengingatkan untuk jadwal pemeriksaan kehamilan (${p.kunjungan_ke || 'rutin'}).\nPemeriksaan berkala sangat penting untuk memantau kesehatan Ibu dan janin dalam kandungan.\n\nSilakan datang ke Puskesmas/Posyandu terdekat membawa Buku KIA. Terima kasih!`;
                        }
                    } else {
                        // Suami SIAGA
                        this.waPhone = p.no_telepon || '';
                        const donor = p.calon_pendonor ? `Calon Pendonor: ${p.calon_pendonor}` : 'Mohon siapkan 2 calon pendonor darah keluarga';
                        this.waMessage = `Halo Bpk. ${p.nama_suami || 'Suami SIAGA'},\n\nSalam dari Tim P4K Puskesmas ${kelStr} (Program Perencanaan Persalinan & Pencegahan Komplikasi):\nIstri tercinta (${p.nama_lengkap || ''}) diperkirakan melahirkan sekitar tanggal ${hplStr}.\n\nSebagai Suami SIAGA, mohon pastikan:\n- Kendaraan / transportasi siaga menuju faskes.\n- Tabungan persalinan & berkas administrasi siap.\n- ${donor}.\n\nDukungan Bapak sangat berarti bagi keselamatan ibu dan buah hati. Terima kasih!`;
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

                async openDuplicateModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showDuplicateModal = true;
                    this.isLoadingDuplicates = true;
                    this.duplicateResults = [];

                    try {
                        const res = await fetch(`/anc/patients/${patient.id}/duplicates`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        this.duplicateResults = data.duplicates || [];
                    } catch (e) {
                        this.notify('error', 'Gagal', 'Terjadi kesalahan saat memindai duplikasi.');
                    } finally {
                        this.isLoadingDuplicates = false;
                    }
                },

                async openTimelineModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showTimelineModal = true;
                    this.isLoadingTimeline = true;
                    this.timelineData = [];

                    try {
                        const res = await fetch(`/anc/patients/${patient.id}/timeline`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        this.timelineData = data.timeline || [];
                    } catch (e) {
                        this.notify('error', 'Gagal', 'Gagal memuat rekam jejak pasien.');
                    } finally {
                        this.isLoadingTimeline = false;
                    }
                },

                async openAiTriage(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showAiModal = true;
                    this.isLoadingAi = true;
                    this.aiAnalysisResult = '';

                    try {
                        const res = await fetch(`/anc/patients/${patient.id}/ai-triage`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.aiAnalysisResult = data.content;
                        } else {
                            this.aiAnalysisResult = 'Gagal melakukan analisis AI: ' + (data.message || 'Koneksi API bermasalah.');
                        }
                    } catch (e) {
                        this.aiAnalysisResult = 'Kesalahan saat menghubungi server: ' + e.message;
                    } finally {
                        this.isLoadingAi = false;
                    }
                },

                formatAiContent(text) {
                    if (!text) return '';
                    // Basic Markdown-like bold formatting to HTML
                    return text
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/^### (.*$)/gim, '<h4 class="font-bold text-slate-800 text-sm mt-3 mb-1">$1</h4>')
                        .replace(/^## (.*$)/gim, '<h3 class="font-black text-slate-900 text-base mt-4 mb-1">$1</h3>');
                },

                initDashboard() {
                    // Restore saved map config from localStorage
                    try {
                        const saved = localStorage.getItem('anc_map_cfg');
                        if (saved) {
                            const parsed = JSON.parse(saved);
                            this.ancMapCfg = Object.assign({}, this.ancMapCfg, parsed);
                            this.mapViewMode = this.ancMapCfg.viewMode;
                        }
                    } catch (e) { }

                    this.loadKelurahanList();
                    this.loadAncMapKecamatanList();
                    this.$nextTick(() => {
                        this.renderCharts();
                        this.initMap();
                        setTimeout(() => {
                            this.checkAndTriggerH1Alert();
                        }, 800);
                    });

                    window.addEventListener('birth-alert-received', (e) => {
                        console.log('Realtime event received in ANC Dashboard:', e.detail);
                        this.applyFilters(this.patientsData.current_page || 1);
                    });
                },

                openAddModal() {
                    this.aiAddStep = 'prompt';
                    this.aiAddPrompt = '';
                    this.aiAddError = '';
                    this.newPatient = {};
                    this.showAddModal = true;
                },

                async runAncAiParse() {
                    if (this.aiAddLoading || this.aiAddPrompt.trim().length < 10) return;
                    this.aiAddLoading = true;
                    this.aiAddError = '';
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.ai.parse') }}`, {
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
                            this.aiAddError = result.message || 'AI tidak dapat memproses deskripsi ini. Coba tulis lebih lengkap.';
                        }
                    } catch (e) {
                        this.aiAddError = 'Terjadi kesalahan saat menghubungi AI: ' + e.message;
                    } finally {
                        this.aiAddLoading = false;
                    }
                },

                async loadKelurahanList() {
                    try {
                        const res = await fetch(`{{ route('anc.kelurahan.list') }}?kabupaten=${encodeURIComponent(this.selectedKabupaten || '')}`);
                        this.kelurahanList = await res.json();
                    } catch (e) {
                        console.error('Error loading kelurahan:', e);
                    }
                },

                onKabupatenChange() {
                    this.selectedKelurahan = '';
                    this.loadKelurahanList();
                    this.applyFilters();
                },

                async applyFilters(page = 1) {
                    const params = new URLSearchParams({
                        kabupaten: this.selectedKabupaten || '',
                        kelurahan: this.selectedKelurahan || '',
                        bulan: this.selectedBulan || '',
                        search: this.searchQuery || '',
                        page: page
                    });

                    try {
                        const res = await fetch(`{{ route('anc.stats.json') }}?${params.toString()}`);
                        const data = await res.json();

                        this.kpi = data.kpi;
                        this.kelurahanChartData = data.kelurahan_chart;
                        this.monthlyChartData = data.monthly_chart;
                        this.ageChartData = data.age_chart;
                        if (data.poedji_chart) {
                            this.poedjiChartData = data.poedji_chart;
                        }
                        if (data.map_data) {
                            this.mapData = data.map_data;
                        }
                        this.patientsData = data.patients;
                        if (data.imminent_deliveries) {
                            this.imminentDeliveries = data.imminent_deliveries;
                        }
                        if (data.upcoming_deliveries !== undefined) {
                            this.upcomingDeliveries = data.upcoming_deliveries;
                        }

                        this.updateCharts();
                        this.updateMap();
                    } catch (e) {
                        console.error('Error applying filters:', e);
                    }
                },

                resetFilters() {
                    this.selectedKabupaten = '';
                    this.selectedKelurahan = '';
                    this.selectedBulan = '';
                    this.searchQuery = '';
                    this.loadKelurahanList();
                    this.applyFilters();
                },

                changePage(page) {
                    this.applyFilters(page);
                },

                renderCharts() {
                    // 1. Kelurahan Horizontal Bar Chart (2 dataset: total & BSC)
                    const ctxKel = document.getElementById('ancKelurahanChart')?.getContext('2d');
                    if (ctxKel) {
                        if (ancCharts.kelurahan) {
                            try { ancCharts.kelurahan.destroy(); } catch (e) { }
                        }
                        ancCharts.kelurahan = new Chart(ctxKel, {
                            type: 'bar',
                            data: {
                                labels: this.kelurahanChartData.labels,
                                datasets: [
                                    {
                                        label: 'Total Ibu Hamil',
                                        data: this.kelurahanChartData.values,
                                        backgroundColor: 'rgba(236, 72, 153, 0.80)',
                                        borderRadius: 6,
                                    },
                                    {
                                        label: 'Ibu Hamil Risiko',
                                        data: this.kelurahanChartData.risiko_values ?? [],
                                        backgroundColor: 'rgba(239, 68, 68, 0.75)',
                                        borderRadius: 6,
                                    }
                                ]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        labels: { boxWidth: 12, font: { size: 11 } }
                                    }
                                },
                                scales: {
                                    x: { grid: { color: '#f1f5f9' }, stacked: false },
                                    y: { grid: { display: false }, stacked: false }
                                }
                            }
                        });
                    }

                    // 2. Age Group Doughnut Chart
                    const ctxAge = document.getElementById('ancAgeChart')?.getContext('2d');
                    if (ctxAge) {
                        if (ancCharts.age) {
                            try { ancCharts.age.destroy(); } catch (e) { }
                        }
                        ancCharts.age = new Chart(ctxAge, {
                            type: 'doughnut',
                            data: {
                                labels: this.ageChartData.labels,
                                datasets: [{
                                    data: this.ageChartData.values,
                                    backgroundColor: ['#f59e0b', '#10b981', '#f43f5e'],
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                                }
                            }
                        });
                    }

                    // 3. Monthly Trend Line Chart
                    const ctxMonth = document.getElementById('ancMonthlyChart')?.getContext('2d');
                    if (ctxMonth) {
                        if (ancCharts.monthly) {
                            try { ancCharts.monthly.destroy(); } catch (e) { }
                        }
                        ancCharts.monthly = new Chart(ctxMonth, {
                            type: 'bar',
                            data: {
                                labels: this.monthlyChartData.labels,
                                datasets: [{
                                    label: 'Tafsiran Persalinan (HPL)',
                                    data: this.monthlyChartData.values,
                                    backgroundColor: 'rgba(99, 102, 241, 0.75)',
                                    borderColor: '#6366f1',
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => ` ${ctx.parsed.y} ibu hamil akan bersalin`
                                        }
                                    }
                                },
                                scales: {
                                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }

                    // 4. Poedji Rochjati Donut Chart
                    const ctxPoedji = document.getElementById('poedjiChart')?.getContext('2d');
                    if (ctxPoedji) {
                        if (ancCharts.poedji) {
                            try { ancCharts.poedji.destroy(); } catch (e) { }
                        }
                        ancCharts.poedji = new Chart(ctxPoedji, {
                            type: 'doughnut',
                            data: {
                                labels: this.poedjiChartData.labels,
                                datasets: [{
                                    data: this.poedjiChartData.values,
                                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                                }
                            }
                        });
                    }
                },

                updateCharts() {
                    if (ancCharts.kelurahan) {
                        ancCharts.kelurahan.data.labels = this.kelurahanChartData.labels;
                        ancCharts.kelurahan.data.datasets[0].data = this.kelurahanChartData.values;
                        ancCharts.kelurahan.data.datasets[1].data = this.kelurahanChartData.risiko_values ?? [];
                        ancCharts.kelurahan.update();
                    }
                    if (ancCharts.age) {
                        ancCharts.age.data.labels = this.ageChartData.labels;
                        ancCharts.age.data.datasets[0].data = this.ageChartData.values;
                        ancCharts.age.update();
                    }
                    if (ancCharts.monthly) {
                        ancCharts.monthly.data.labels = this.monthlyChartData.labels;
                        ancCharts.monthly.data.datasets[0].data = this.monthlyChartData.values;
                        ancCharts.monthly.update();
                    }
                    if (ancCharts.poedji && this.poedjiChartData) {
                        ancCharts.poedji.data.labels = this.poedjiChartData.labels;
                        ancCharts.poedji.data.datasets[0].data = this.poedjiChartData.values;
                        ancCharts.poedji.update();
                    }
                },

                // ── ANC Map Config Helpers ──────────────────────────────────────

                async loadAncMapKecamatanList() {
                    try {
                        const res = await fetch(`{{ route('anc.kecamatan.list') }}?kabupaten=${encodeURIComponent(this.selectedKabupaten || '')}`);
                        this.ancMapKecamatanList = await res.json();
                    } catch (e) { }
                },

                saveAncMapCfg(showFeedback = false) {
                    try {
                        localStorage.setItem('anc_map_cfg', JSON.stringify(this.ancMapCfg));
                        if (showFeedback) {
                            this.ancMapCfgSaved = true;
                            setTimeout(() => { this.ancMapCfgSaved = false; }, 2500);
                        }
                    } catch (e) { }
                },

                resetAncMapCfg() {
                    this.ancMapCfg = { kecamatan: '', viewMode: 'markers', height: 460, showKEK: false, showAnemia: false, showHiper: false, showImminent: false, showFasyankes: true };
                    try { localStorage.removeItem('anc_map_cfg'); } catch (e) { }
                    this.ancMapCfgSaved = true;
                    setTimeout(() => { this.ancMapCfgSaved = false; }, 1800);
                    this.onAncMapKecamatanChange();
                },

                resizeAncMap() {
                    this.$nextTick(() => {
                        if (ancCharts.map) ancCharts.map.invalidateSize();
                    });
                },

                async onAncMapKecamatanChange() {
                    this.saveAncMapCfg();
                    await this.loadAncMapGeoData();
                    this.updateMap();
                },

                async loadAncMapGeoData() {
                    this.ancMapLoading = true;
                    try {
                        const params = new URLSearchParams({
                            kabupaten: this.selectedKabupaten || '',
                            kecamatan: this.ancMapCfg.kecamatan || '',
                        });
                        const res = await fetch(`{{ route('anc.map.data') }}?${params.toString()}`);
                        const data = await res.json();
                        this.mapData = data.points || [];
                        if (ancCharts.map && data.center) {
                            ancCharts.map.setView([data.center.lat, data.center.lng], data.center.zoom || 13);
                        }
                    } catch (e) {
                        console.error('ANC map data error:', e);
                    } finally {
                        this.ancMapLoading = false;
                    }
                },

                // ── Map Init & Render ───────────────────────────────────────────

                initMap() {
                    const init = () => {
                        const mapElem = document.getElementById('ancMap');
                        if (!mapElem) return;

                        if (typeof L === 'undefined') {
                            setTimeout(init, 100);
                            return;
                        }

                        if (ancCharts.map) {
                            try { ancCharts.map.remove(); } catch (e) { }
                            ancCharts.map = null;
                        }

                        ancCharts.map = L.map('ancMap').setView([-6.2889, 106.6092], 12);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                            maxZoom: 18
                        }).addTo(ancCharts.map);

                        ancCharts.markersLayer = L.layerGroup().addTo(ancCharts.map);
                        ancCharts.fasyanksLayer = L.layerGroup().addTo(ancCharts.map);
                        this.updateMap();

                        [100, 300, 600, 1000].forEach(delay => {
                            setTimeout(() => { if (ancCharts.map) ancCharts.map.invalidateSize(); }, delay);
                        });
                    };
                    init();
                },

                updateMap() {
                    if (!ancCharts.map || !ancCharts.markersLayer || typeof L === 'undefined') return;

                    ancCharts.markersLayer.clearLayers();
                    if (ancCharts.fasyanksLayer) ancCharts.fasyanksLayer.clearLayers();

                    const viewMode = this.ancMapCfg.viewMode || this.mapViewMode;

                    if (!this.mapData || this.mapData.length === 0) return;

                    const bounds = [];

                    this.mapData.forEach(item => {
                        if (!item.lat || !item.lng) return;
                        bounds.push([item.lat, item.lng]);

                        // ── Color by risk level ──
                        const riskLevel = item.risk_level || (item.total_risti > 0 ? 'high' : 'normal');
                        let color, radius;
                        if (riskLevel === 'critical') {
                            color = '#f43f5e'; radius = 20;   // rose — KRST
                        } else if (riskLevel === 'high') {
                            color = '#f59e0b'; radius = 15;   // amber — KRT
                        } else {
                            color = '#f472b6'; radius = 11;   // pink — KRR / normal
                        }

                        // ── Mode: Heatmap halo ──
                        if (viewMode === 'heatmap') {
                            const haloR = Math.max(350, item.total * 70);
                            ancCharts.markersLayer.addLayer(L.circle([item.lat, item.lng], {
                                radius: haloR,
                                color: 'transparent',
                                fillColor: color,
                                fillOpacity: Math.min(0.5, 0.1 + (item.total_risti || 0) * 0.05),
                            }));
                        }

                        // ── Mode: RISTI detection rings ──
                        if (viewMode === 'risti' && riskLevel !== 'normal') {
                            ancCharts.markersLayer.addLayer(L.circle([item.lat, item.lng], {
                                radius: riskLevel === 'critical' ? 700 : 500,
                                color: color, fillColor: color,
                                fillOpacity: riskLevel === 'critical' ? 0.22 : 0.12,
                                weight: 2, dashArray: '5 5',
                            }));
                        }

                        // ── Overlay: KEK zone ──
                        if (this.ancMapCfg.showKEK && item.total_kek > 0) {
                            ancCharts.markersLayer.addLayer(L.circle([item.lat, item.lng], {
                                radius: 400, color: '#d97706', fillColor: '#fbbf24',
                                fillOpacity: 0.2, weight: 1.5, dashArray: '3 5',
                            }));
                        }

                        // ── Overlay: Anemia zone ──
                        if (this.ancMapCfg.showAnemia && item.total_anemia > 0) {
                            ancCharts.markersLayer.addLayer(L.circle([item.lat, item.lng], {
                                radius: 320, color: '#e11d48', fillColor: '#fb7185',
                                fillOpacity: 0.18, weight: 1.5, dashArray: '3 4',
                            }));
                        }

                        // ── Overlay: Hipertensi zone ──
                        if (this.ancMapCfg.showHiper && item.total_hiper > 0) {
                            ancCharts.markersLayer.addLayer(L.circle([item.lat, item.lng], {
                                radius: 280, color: '#ea580c', fillColor: '#fb923c',
                                fillOpacity: 0.2, weight: 1.5,
                            }));
                        }

                        // ── Overlay: Imminent delivery pulse ──
                        if (this.ancMapCfg.showImminent && item.total_imminent > 0) {
                            ancCharts.markersLayer.addLayer(L.circle([item.lat, item.lng], {
                                radius: 200, color: '#059669', fillColor: '#34d399',
                                fillOpacity: 0.35, weight: 2,
                            }));
                        }

                        // ── Main marker ──
                        const circle = L.circleMarker([item.lat, item.lng], {
                            color, fillColor: color,
                            fillOpacity: riskLevel !== 'normal' ? 0.88 : 0.65,
                            radius, weight: 2,
                        });

                        // ── Fasyankes sub-markers ──
                        if (this.ancMapCfg.showFasyankes && item.fasyankes && item.fasyankes.length > 0) {
                            item.fasyankes.slice(0, 3).forEach((f, i) => {
                                const angle = (i * 120) * (Math.PI / 180);
                                const fm = L.circleMarker(
                                    [item.lat + 0.002 * Math.cos(angle), item.lng + 0.002 * Math.sin(angle)],
                                    { color: '#0284c7', fillColor: '#38bdf8', fillOpacity: 0.75, radius: 6, weight: 1.5 }
                                );
                                fm.bindPopup(`<div style="font-size:11px;font-weight:600;">${f.name}</div>
                                    <div style="font-size:11px;color:#475569;">${f.total} ibu hamil · ${f.total_risti || 0} RISTI</div>`);
                                if (ancCharts.fasyanksLayer) ancCharts.fasyanksLayer.addLayer(fm);
                            });
                        }

                        // ── Popup ──
                        const ristiPct = item.risti_ratio != null ? item.risti_ratio + '%' : '-';
                        const kekBadge = item.total_kek > 0 ? `<div style="display:flex;justify-content:space-between;font-size:11px;"><span style="color:#92400e;">KEK (LiLA&lt;23.5)</span><span style="font-weight:700;color:#d97706;">${item.total_kek}</span></div>` : '';
                        const anmBadge = item.total_anemia > 0 ? `<div style="display:flex;justify-content:space-between;font-size:11px;"><span style="color:#9f1239;">Anemia (Hb&lt;11)</span><span style="font-weight:700;color:#e11d48;">${item.total_anemia}</span></div>` : '';
                        const hipBadge = item.total_hiper > 0 ? `<div style="display:flex;justify-content:space-between;font-size:11px;"><span style="color:#9a3412;">Hipertensi</span><span style="font-weight:700;color:#ea580c;">${item.total_hiper}</span></div>` : '';
                        const immBadge = item.total_imminent > 0
                            ? `<div style="margin-top:4px;padding:3px 5px;background:#ecfdf5;border-radius:5px;font-size:10px;color:#065f46;font-weight:700;">🟢 HPL ≤7 Hari: ${item.total_imminent} ibu hamil</div>` : '';
                        const krstBadge = item.total_krst > 0
                            ? `<div style="margin-top:3px;padding:3px 5px;background:#fff1f2;border-radius:5px;font-size:10px;color:#9f1239;font-weight:700;">⚠ KRST (RS PONEK): ${item.total_krst}</div>` : '';

                        circle.bindPopup(`
                            <div style="font-family:inherit;font-size:12px;min-width:200px;">
                                <div style="font-weight:700;font-size:13px;color:#1e293b;margin-bottom:2px;">${item.kelurahan}</div>
                                <div style="color:#64748b;font-size:11px;margin-bottom:5px;">${item.kecamatan ? 'Kec. ' + item.kecamatan + ' · ' : ''}${item.kabupaten || ''}</div>
                                <div style="display:flex;justify-content:space-between;border-top:1px solid #f1f5f9;padding-top:5px;font-weight:700;">
                                    <span>Total Ibu Hamil</span>
                                    <span style="color:${color};">${item.total}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;font-size:11px;">
                                    <span style="color:#be123c;">RISTI (KRT+KRST)</span>
                                    <span style="font-weight:700;color:#f43f5e;">${item.total_risti || 0} <span style="font-weight:400;color:#94a3b8;">(${ristiPct})</span></span>
                                </div>
                                ${kekBadge}${anmBadge}${hipBadge}${krstBadge}${immBadge}
                            </div>
                        `);

                        ancCharts.markersLayer.addLayer(circle);
                    });

                    if (bounds.length > 0) {
                        ancCharts.map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
                    }
                },

                // File Upload & Preview Handler
                onFileSelected(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.processFile(file);
                    }
                    event.target.value = '';
                },

                onFileDrop(event) {
                    this.isDragging = false;
                    const files = event.dataTransfer.files;
                    if (files && files.length > 0) {
                        const file = files[0];
                        const ext = file.name.split('.').pop().toLowerCase();
                        if (!['xlsx', 'xls', 'csv', 'txt'].includes(ext)) {
                            this.notify('error', 'Format Tidak Didukung', 'Harap masukkan berkas .xlsx, .xls, atau .csv');
                            return;
                        }
                        this.processFile(file);
                    }
                },

                async processFile(file) {
                    const formData = new FormData();
                    formData.append('file', file);

                    this.isParsing = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.import.preview') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.importPreview = data.data;
                        } else {
                            this.notify('error', 'Gagal Membaca File', data.message || 'Gagal mem-parsing berkas');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Upload', 'Terjadi kesalahan saat mengunggah: ' + e.message);
                    } finally {
                        this.isParsing = false;
                    }
                },

                resetImport() {
                    this.importPreview = null;
                },

                closeImportModal() {
                    this.showImportModal = false;
                    this.importPreview = null;
                },

                async commitImport() {
                    if (!this.importPreview || !this.importPreview.temp_token) return;

                    this.isSubmittingImport = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.import.commit') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ temp_token: this.importPreview.temp_token })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.closeImportModal();
                            this.applyFilters();
                            this.loadKelurahanList();
                            this.notify('success', 'Import Berhasil', data.message);
                        } else {
                            this.notify('error', 'Gagal Import', data.message || 'Gagal mengimpor data');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Sistem', 'Terjadi kesalahan sistem saat menyimpan data import.');
                    } finally {
                        this.isSubmittingImport = false;
                    }
                },

                // Realtime Edit Patient
                openEditModal(patient) {
                    this.editingPatient = JSON.parse(JSON.stringify(patient));
                    this.showEditModal = true;
                },

                async saveEditPatient() {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`/anc/patients/${this.editingPatient.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.editingPatient)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showEditModal = false;
                            const idx = this.patientsData.data.findIndex(p => p.id === this.editingPatient.id);
                            if (idx !== -1) {
                                this.patientsData.data[idx] = data.data;
                            }
                            this.applyFilters(this.patientsData.current_page);
                            this.notify('success', 'Data Diperbarui', 'Rekam data ibu hamil berhasil diupdate secara realtime.');
                        } else {
                            this.notify('error', 'Gagal Simpan', data.message || 'Gagal memperbarui data');
                        }
                    } catch (e) {
                        this.notify('error', 'Koneksi Bermasalah', 'Kesalahan koneksi saat menyimpan perubahan.');
                    }
                },

                // Add Patient
                async saveNewPatient() {
                    if (this.isSubmittingNewPatient) return;
                    this.isSubmittingNewPatient = true;
                    this.aiAddError = '';
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.patients.store') }}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify(this.newPatient)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showAddModal = false;
                            this.applyFilters();
                            this.loadKelurahanList();
                            this.notify('success', 'Ibu Hamil Ditambahkan', data.message || 'Data berhasil disimpan.');
                        } else {
                            this.aiAddError = data.message || 'Gagal menyimpan data ibu hamil.';
                        }
                    } catch (e) {
                        this.aiAddError = 'Gagal menyimpan: ' + e.message;
                    } finally {
                        this.isSubmittingNewPatient = false;
                    }
                },

                // Delete Patient
                async deletePatient(patient) {
                    if (!confirm(`Hapus rekam data ${patient.nama_lengkap}?`)) return;

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`/anc/patients/${patient.id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.applyFilters(this.patientsData.current_page);
                            this.notify('success', 'Data Dihapus', `Data ${patient.nama_lengkap} berhasil dihapus.`);
                        } else {
                            this.notify('error', 'Gagal Hapus', 'Gagal menghapus data.');
                        }
                    } catch (e) {
                        this.notify('error', 'Gagal Hapus', 'Terjadi kesalahan saat menghapus data.');
                    }
                },

                // Audio Sirine & Alert Persalinan H-1
                async playSirine() {
                    try {
                        if (!this.sirineAudio) {
                            this.sirineAudio = new Audio('/assets/sounds/sirine.mp3');
                            this.sirineAudio.loop = true;
                        }
                        this.sirineAudio.currentTime = 0;
                        await this.sirineAudio.play();
                    } catch (err) {
                        // Browser Autoplay Policy: play() rejected because user hasn't interacted with document yet
                        console.warn('Autoplay audio ditunda menunggu interaksi pengguna:', err.message);

                        // Pasang one-time listener saat pengguna pertama kali berinteraksi (klik/tekan tombol di halaman)
                        const unlockAudio = () => {
                            if (this.sirineAudio && Swal.isVisible()) {
                                this.sirineAudio.play().catch(e => console.warn('Play retry:', e));
                            }
                            window.removeEventListener('click', unlockAudio);
                            window.removeEventListener('keydown', unlockAudio);
                            window.removeEventListener('touchstart', unlockAudio);
                        };
                        window.addEventListener('click', unlockAudio, { once: true });
                        window.addEventListener('keydown', unlockAudio, { once: true });
                        window.addEventListener('touchstart', unlockAudio, { once: true });
                    }
                },

                stopSirine() {
                    if (this.sirineAudio) {
                        this.sirineAudio.pause();
                        this.sirineAudio.currentTime = 0;
                    }
                },

                testSirineAudio() {
                    this.playSirine();
                    Swal.fire({
                        title: 'Uji Coba Sirine Siaga',
                        html: `
                            <div class="text-left text-xs sm:text-sm space-y-2 mt-2">
                                <p class="text-slate-600">Audio sirine siaga sedang berbunyi dari berkas: <br><code class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded text-[11px] sm:text-xs break-all">public/assets/sounds/sirine.mp3</code></p>
                                <p class="text-slate-500 text-[11px] sm:text-xs">Sirine ini akan otomatis dibunyikan bersama pop-up SweetAlert saat sistem mendeteksi ada ibu hamil yang mendekati <strong>H-14 (2 Minggu) Hari Perkiraan Lahir (HPL)</strong>.</p>
                            </div>
                        `,
                        icon: 'warning',
                        confirmButtonText: 'Matikan Sirine',
                        confirmButtonColor: '#e11d48',
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-2xl sm:rounded-3xl shadow-2xl border border-rose-100 !w-[92vw] !max-w-md !p-4 sm:!p-6 !m-auto',
                            confirmButton: 'rounded-xl font-bold px-6 py-2.5 text-xs sm:text-sm shadow-md !w-full sm:!w-auto'
                        }
                    }).then(() => {
                        this.stopSirine();
                    });
                },

                isDueWithin14Days(dateStr) {
                    if (!dateStr) return false;
                    const cleanDate = dateStr.substring(0, 10);
                    const today = new Date();
                    const in14 = new Date();
                    in14.setDate(today.getDate() + 14);
                    const todayStr = today.toISOString().substring(0, 10);
                    const in14Str = in14.toISOString().substring(0, 10);
                    return cleanDate >= todayStr && cleanDate <= in14Str;
                },

                checkAndTriggerH1Alert() {
                    if (!this.imminentDeliveries || this.imminentDeliveries.length === 0) return;
                    if (this.hasTriggeredInitialAlert) return;
                    this.hasTriggeredInitialAlert = true;

                    // Trigger alert modal
                    this.showH1ModalAlert();
                },

                showH1ModalAlert() {
                    const count = this.imminentDeliveries ? this.imminentDeliveries.length : 0;
                    if (count === 0) {
                        Swal.fire({
                            title: 'Tidak Ada HPL dalam 2 Minggu',
                            text: 'Saat ini tidak ada data ibu hamil yang perkiraan lahirnya dalam 14 hari ke depan.',
                            icon: 'info',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#ec4899',
                            customClass: {
                                popup: 'rounded-2xl sm:rounded-3xl !w-[90vw] !max-w-sm !p-4 sm:!p-6 !m-auto',
                                confirmButton: 'rounded-xl font-bold px-6 py-2.5 text-xs sm:text-sm !w-full sm:!w-auto'
                            }
                        });
                        return;
                    }

                    // Putar audio sirine
                    this.playSirine();

                    // Generate patient rows HTML
                    let patientListHtml = '<div class="space-y-2 mt-3 max-h-56 sm:max-h-60 overflow-y-auto pr-1 text-left">';
                    this.imminentDeliveries.forEach((p, idx) => {
                        const ristiBadge = p.status_risti && p.status_risti.toLowerCase().includes('tinggi')
                            ? '<span class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-bold rounded-full whitespace-nowrap">RISTI</span>'
                            : '<span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-semibold rounded-full whitespace-nowrap">Normal</span>';

                        patientListHtml += `
                            <div class="p-2.5 sm:p-3 bg-rose-50/80 border border-rose-200 rounded-xl sm:rounded-2xl flex items-start sm:items-center justify-between gap-2 text-xs">
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-slate-800 text-xs sm:text-sm break-words">${idx + 1}. ${p.nama_lengkap} (${p.umur || '-'} th)</div>
                                    <div class="text-slate-500 text-[10px] sm:text-[11px] mt-0.5 break-words">NIK: ${p.nik || '-'} | Kel: ${p.kelurahan || '-'}, ${p.kabupaten || '-'}</div>
                                    <div class="text-rose-700 font-semibold text-[10px] sm:text-[11px] mt-0.5">HPL: ${p.hpl ? p.hpl.substring(0, 10) : '-'}</div>
                                </div>
                                <div class="text-right shrink-0 mt-0.5 sm:mt-0">
                                    ${ristiBadge}
                                </div>
                            </div>
                        `;
                    });
                    patientListHtml += '</div>';

                    Swal.fire({
                        title: `<span class="text-rose-600 flex items-center justify-center gap-1.5 sm:gap-2 text-base sm:text-xl font-bold leading-snug">
                            <span class="animate-ping inline-flex h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full bg-rose-500 opacity-75 shrink-0"></span>
                            <span>PERINGATAN H-14 PERSALINAN!</span>
                        </span>`,
                        html: `
                            <div class="text-xs sm:text-sm text-slate-600">
                                <p class="font-bold text-slate-800">Ditemukan <span class="text-rose-600 font-extrabold text-sm sm:text-base">${count} Ibu Hamil</span> dengan Hari Perkiraan Lahir (HPL) dalam <u>2 MINGGU</u> ke depan!</p>
                                <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Sirine siaga diaktifkan. Klik di mana saja pada layar jika browser Anda meminta interaksi suara untuk memutar sirine.</p>
                                ${patientListHtml}
                            </div>
                        `,
                        icon: 'warning',
                        confirmButtonText: 'Matikan Sirine & Siapkan Pertolongan',
                        confirmButtonColor: '#e11d48',
                        allowOutsideClick: false,
                        didOpen: () => {
                            // Coba jalankan kembali sirine saat modal SweetAlert telah terbuka
                            this.playSirine();
                        },
                        customClass: {
                            popup: 'rounded-2xl sm:rounded-3xl shadow-2xl border-2 border-rose-200 !w-[94vw] !max-w-lg !p-4 sm:!p-6 !m-auto',
                            confirmButton: 'rounded-xl font-bold px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm shadow-md !w-full sm:!w-auto'
                        }
                    }).then(() => {
                        this.stopSirine();
                    });
                },

                showH30ModalAlert() {
                    const list = this.upcomingDeliveries || [];
                    if (list.length === 0) {
                        Swal.fire({
                            title: 'Tidak Ada Data H-30',
                            text: 'Saat ini tidak ada ibu hamil dengan HPL dalam 30 hari ke depan.',
                            icon: 'info',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#f59e0b',
                            customClass: {
                                popup: 'rounded-2xl sm:rounded-3xl !w-[90vw] !max-w-sm !p-4 sm:!p-6 !m-auto',
                                confirmButton: 'rounded-xl font-bold px-6 py-2.5 text-xs sm:text-sm !w-full sm:!w-auto'
                            }
                        });
                        return;
                    }

                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    let patientListHtml = '<div class="space-y-2 mt-3 max-h-64 sm:max-h-72 overflow-y-auto pr-1 text-left">';
                    list.forEach((p, idx) => {
                        const hplDate = p.hpl ? new Date(p.hpl.substring(0, 10)) : null;
                        const sisaHari = hplDate
                            ? Math.round((hplDate - today) / (1000 * 60 * 60 * 24))
                            : null;
                        const sisaLabel = sisaHari !== null
                            ? `<span class="font-bold text-amber-700">H-${sisaHari}</span>`
                            : '-';

                        const ristiBadge = p.status_risti && p.status_risti.toLowerCase().includes('tinggi')
                            ? '<span class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-bold rounded-full whitespace-nowrap">RISTI</span>'
                            : '<span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-semibold rounded-full whitespace-nowrap">Normal</span>';

                        const urgencyBg = sisaHari !== null && sisaHari <= 7
                            ? 'bg-orange-50 border-orange-200'
                            : 'bg-amber-50/80 border-amber-200';

                        patientListHtml += `
                            <div class="p-2.5 sm:p-3 ${urgencyBg} border rounded-xl sm:rounded-2xl flex items-start sm:items-center justify-between gap-2 text-xs">
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-slate-800 text-xs sm:text-sm break-words">${idx + 1}. ${p.nama_lengkap} (${p.umur || '-'} th)</div>
                                    <div class="text-slate-500 text-[10px] sm:text-[11px] mt-0.5 break-words">NIK: ${p.nik || '-'} | Kel: ${p.kelurahan || '-'}, ${p.kabupaten || '-'}</div>
                                    <div class="text-[10px] sm:text-[11px] mt-0.5">
                                        <span class="text-slate-600">HPL: ${p.hpl ? p.hpl.substring(0, 10) : '-'}</span>
                                        <span class="mx-1 text-slate-400">·</span>
                                        ${sisaLabel}
                                    </div>
                                    ${p.no_telepon ? `<div class="text-[10px] text-slate-400 mt-0.5">📞 ${p.no_telepon}${p.nama_suami ? ' (Suami: ' + p.nama_suami + ')' : ''}</div>` : ''}
                                </div>
                                <div class="text-right shrink-0 mt-0.5 sm:mt-0">
                                    ${ristiBadge}
                                </div>
                            </div>
                        `;
                    });
                    patientListHtml += '</div>';

                    Swal.fire({
                        title: `<span class="text-amber-600 flex items-center justify-center gap-1.5 sm:gap-2 text-base sm:text-xl font-bold leading-snug">
                            <span>📅 PERSIAPAN PERSALINAN H-30</span>
                        </span>`,
                        html: `
                            <div class="text-xs sm:text-sm text-slate-600">
                                <p class="font-bold text-slate-800">Ditemukan <span class="text-amber-600 font-extrabold text-sm sm:text-base">${list.length} Ibu Hamil</span> dengan HPL dalam <u>30 hari ke depan</u>.</p>
                                <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Segera verifikasi kelengkapan P4K: donor darah, tabungan, transportasi, dan pendamping persalinan.</p>
                                ${patientListHtml}
                            </div>
                        `,
                        icon: 'warning',
                        iconColor: '#f59e0b',
                        confirmButtonText: 'Mengerti, Siapkan P4K',
                        confirmButtonColor: '#f59e0b',
                        allowOutsideClick: true,
                        customClass: {
                            popup: 'rounded-2xl sm:rounded-3xl shadow-2xl border-2 border-amber-200 !w-[94vw] !max-w-lg !p-4 sm:!p-6 !m-auto',
                            confirmButton: 'rounded-xl font-bold px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm shadow-md !w-full sm:!w-auto'
                        }
                    });
                },

                // Modal & Aksi Pencatatan Kelahiran
                openRecordBirthModal(patient) {
                    this.targetBirthPatient = JSON.parse(JSON.stringify(patient));
                    this.birthForm = {
                        tanggal_bersalin: new Date().toISOString().substring(0, 10),
                        tempat_bersalin: patient.rekomendasi_faskes || 'Puskesmas PONED',
                        penolong_persalinan: 'Bidan Desa / Nakes',
                        kondisi_bayi: 'Lahir Hidup, Sehat & Menangis Kuat',
                        berat_lahir_bayi: '',
                        komplikasi_persalinan: ''
                    };
                    this.showRecordBirthModal = true;
                },

                async submitRecordBirth() {
                    if (!this.targetBirthPatient) return;
                    this.isSubmittingBirth = true;

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`/anc/patients/${this.targetBirthPatient.id}/record-birth`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.birthForm)
                        });

                        const data = await res.json();
                        if (data.success) {
                            this.showRecordBirthModal = false;
                            this.applyFilters(this.patientsData.current_page || 1);
                            this.notify('success', 'Kelahiran Berhasil Dicatat', data.message);
                        } else {
                            this.notify('error', 'Gagal Mencatat', data.message || 'Terjadi kesalahan sistem.');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Jaringan', 'Gagal mengirim data kelahiran: ' + e.message);
                    } finally {
                        this.isSubmittingBirth = false;
                    }
                },

                // Uji Coba Sinyal WebSocket Alert Kelahiran
                async triggerTestBirthAlert() {
                    this.isTestingBirthAlert = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.test-birth-alert') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.notify('success', 'Sinyal WebSocket Terkirim', 'Event kelahiran disiarkan ke PieSocket.');
                        } else {
                            this.notify('warning', 'Pemberitahuan', data.message || 'Sinyal terkirim');
                        }
                    } catch (e) {
                        this.notify('error', 'Gagal Tes Alert', 'Kesalahan jaringan: ' + e.message);
                    } finally {
                        this.isTestingBirthAlert = false;
                    }
                },

                // Hapus Seluruh Data ANC
                async clearAllData() {
                    if (this.clearConfirmText !== 'HAPUS SEMUA') {
                        this.notify('error', 'Konfirmasi Salah', 'Ketik "HAPUS SEMUA" untuk melanjutkan.');
                        return;
                    }
                    this.isClearingAll = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('anc.clear-all') }}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showClearAllModal = false;
                            this.clearConfirmText = '';
                            this.notify('success', 'Data Berhasil Dihapus', data.message);
                            setTimeout(() => this.applyFilters(1), 800);
                        } else {
                            this.notify('error', 'Gagal Menghapus', data.message || 'Terjadi kesalahan.');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Jaringan', 'Gagal menghapus data: ' + e.message);
                    } finally {
                        this.isClearingAll = false;
                    }
                }
            };
        }

    </script>
