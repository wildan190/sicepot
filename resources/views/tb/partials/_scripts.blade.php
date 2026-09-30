    <!-- JAVASCRIPT & REALTIME CONTROLLER -->
    <script>
        // Non-reactive storage for Chart.js and Leaflet instances to prevent Alpine Proxy recursion
        const tbCharts = {
            kelurahan: null,
            monthly: null,
            gender: null,
            oat: null,
            map: null,
            markersLayer: null,
            fasyanksLayer: null,
        };

        function tbDashboard() {
            return {
                selectedKabupaten: '{{ $selectedKabupaten }}',
                selectedKelurahan: '{{ $selectedKelurahan }}',
                selectedType: '{{ $selectedType }}',
                searchQuery: '',
                kelurahanList: [],
                kpi: @json($kpi),
                kelurahanChartData: @json($kelurahan_chart),
                oatKelurahanChartData: @json($oat_kelurahan_chart),
                monthlyChartData: @json($monthly_chart),
                genderChartData: @json($gender_chart),
                ageChartData: @json($age_chart),
                patientsData: @json($patients),
                mapData: @json($map_data),

                // Import Modal State
                showImportModal: false,
                isDragging: false,
                isParsing: false,
                isSubmittingImport: false,
                importPreview: null,

                // Clear Massive Modal State
                showClearMassiveModal: false,
                isClearingMassive: false,
                clearMassiveKeyword: '',

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
                addModalTab: 'manual', // 'manual' | 'ai'
                isSubmittingNewPatient: false,
                editingPatient: {},
                // AI Prompt Add state
                aiAddStep: 'prompt',   // 'prompt' | 'preview'
                aiAddPrompt: '',
                aiAddLoading: false,
                aiAddError: '',
                newPatient: {},

                // GIS Map Widget Config (per-kecamatan, saveable)
                tbMapCfgOpen: false,
                tbMapCfgSaved: false,
                tbMapLoading: false,
                tbMapKecamatanList: [],
                tbMapCfg: {
                    kecamatan: '',
                    viewMode: 'markers',
                    height: 460,
                    showPenderita: true,
                    showInvestigasi: true,
                },

                // Legacy (kept for updateMap compatibility)
                mapViewMode: 'markers',

                // Target Patient & Innovation Modal States
                targetPatient: null,
                showWhatsAppModal: false,
                waPhone: '',
                waTemplateType: 'oat_daily',
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

                initDashboard() {
                    // Restore saved map config from localStorage
                    try {
                        const saved = localStorage.getItem('tb_map_cfg');
                        if (saved) {
                            const parsed = JSON.parse(saved);
                            this.tbMapCfg = Object.assign({}, this.tbMapCfg, parsed);
                            this.mapViewMode = this.tbMapCfg.viewMode;
                        }
                    } catch (e) { }

                    this.loadKelurahanList();
                    this.loadTbMapKecamatanList();
                    this.$nextTick(() => {
                        this.renderCharts();
                        this.initMap();
                    });
                },

                openAddModal() {
                    this.addModalTab = 'manual';
                    this.aiAddStep = 'prompt';
                    this.aiAddPrompt = '';
                    this.aiAddError = '';
                    this.newPatient = {
                        report_type: 'skrining',
                        batuk_2_minggu: 'Tidak',
                        bb_turun: 'Tidak',
                        keringat_malam: 'Tidak',
                        kontak_tb: 'Tidak',
                        sudah_pengobatan: 'Belum'
                    };
                    this.showAddModal = true;
                },

                async runTbAiParse() {
                    if (this.aiAddLoading || this.aiAddPrompt.trim().length < 10) return;
                    this.aiAddLoading = true;
                    this.aiAddError = '';
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('tb.ai.parse') }}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify({ prompt: this.aiAddPrompt })
                        });
                        const result = await res.json();
                        if (result.success && result.data) {
                            // Strip null values so only populated fields show in preview
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
                        const res = await fetch(`{{ route('tb.kelurahan.list') }}?kabupaten=${encodeURIComponent(this.selectedKabupaten || '')}`);
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
                        report_type: this.selectedType || '',
                        search: this.searchQuery || '',
                        page: page
                    });

                    try {
                        const res = await fetch(`{{ route('tb.stats.json') }}?${params.toString()}`);
                        const data = await res.json();

                        this.kpi = data.kpi;
                        this.kelurahanChartData = data.kelurahan_chart;
                        this.oatKelurahanChartData = data.oat_kelurahan_chart;
                        this.monthlyChartData = data.monthly_chart;
                        this.genderChartData = data.gender_chart;
                        this.ageChartData = data.age_chart;
                        this.patientsData = data.patients;
                        if (data.map_data) {
                            this.mapData = data.map_data;
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
                    this.selectedType = '';
                    this.searchQuery = '';
                    this.loadKelurahanList();
                    this.applyFilters();
                },

                changePage(page) {
                    this.applyFilters(page);
                },

                renderCharts() {
                    // 1. Kelurahan Horizontal Bar Chart
                    const ctxKel = document.getElementById('kelurahanChart')?.getContext('2d');
                    if (ctxKel) {
                        if (tbCharts.kelurahan) {
                            try { tbCharts.kelurahan.destroy(); } catch (e) { }
                        }
                        tbCharts.kelurahan = new Chart(ctxKel, {
                            type: 'bar',
                            data: {
                                labels: this.kelurahanChartData.labels,
                                datasets: [{
                                    label: 'Jumlah Kasus',
                                    data: this.kelurahanChartData.values,
                                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { grid: { color: '#f1f5f9' } },
                                    y: { grid: { display: false } }
                                }
                            }
                        });
                    }

                    // 2. Gender Doughnut Chart
                    const ctxGen = document.getElementById('genderChart')?.getContext('2d');
                    if (ctxGen) {
                        if (tbCharts.gender) {
                            try { tbCharts.gender.destroy(); } catch (e) { }
                        }
                        tbCharts.gender = new Chart(ctxGen, {
                            type: 'doughnut',
                            data: {
                                labels: this.genderChartData.labels,
                                datasets: [{
                                    data: this.genderChartData.values,
                                    backgroundColor: ['#6366f1', '#ec4899'],
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
                    const ctxMonth = document.getElementById('monthlyChart')?.getContext('2d');
                    if (ctxMonth) {
                        if (tbCharts.monthly) {
                            try { tbCharts.monthly.destroy(); } catch (e) { }
                        }
                        tbCharts.monthly = new Chart(ctxMonth, {
                            type: 'line',
                            data: {
                                labels: this.monthlyChartData.labels,
                                datasets: [{
                                    label: 'Kasus Baru',
                                    data: this.monthlyChartData.values,
                                    borderColor: '#0284c7',
                                    backgroundColor: 'rgba(2, 132, 199, 0.1)',
                                    fill: true,
                                    tension: 0.35,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#0284c7',
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }

                    // 4. OAT Kelurahan Horizontal Bar Chart
                    const ctxOat = document.getElementById('oatKelurahanChart')?.getContext('2d');
                    if (ctxOat) {
                        if (tbCharts.oat) {
                            try { tbCharts.oat.destroy(); } catch (e) { }
                        }
                        tbCharts.oat = new Chart(ctxOat, {
                            type: 'bar',
                            data: {
                                labels: this.oatKelurahanChartData.labels,
                                datasets: [{
                                    label: 'Pasien OAT (TB-03)',
                                    data: this.oatKelurahanChartData.values,
                                    backgroundColor: 'rgba(244, 63, 94, 0.82)',
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: { beginAtZero: true, grid: { color: '#fef2f2' }, ticks: { precision: 0 } },
                                    y: { grid: { display: false } }
                                }
                            }
                        });
                    }
                },

                updateCharts() {
                    if (tbCharts.kelurahan) {
                        tbCharts.kelurahan.data.labels = this.kelurahanChartData.labels;
                        tbCharts.kelurahan.data.datasets[0].data = this.kelurahanChartData.values;
                        tbCharts.kelurahan.update();
                    }
                    if (tbCharts.gender) {
                        tbCharts.gender.data.labels = this.genderChartData.labels;
                        tbCharts.gender.data.datasets[0].data = this.genderChartData.values;
                        tbCharts.gender.update();
                    }
                    if (tbCharts.monthly) {
                        tbCharts.monthly.data.labels = this.monthlyChartData.labels;
                        tbCharts.monthly.data.datasets[0].data = this.monthlyChartData.values;
                        tbCharts.monthly.update();
                    }
                    if (tbCharts.oat) {
                        tbCharts.oat.data.labels = this.oatKelurahanChartData.labels;
                        tbCharts.oat.data.datasets[0].data = this.oatKelurahanChartData.values;
                        tbCharts.oat.update();
                    }
                },

                // ── TBC Map Config Helpers ──────────────────────────────────────

                async loadTbMapKecamatanList() {
                    try {
                        const res = await fetch(`{{ route('tb.kecamatan.list') }}?kabupaten=${encodeURIComponent(this.selectedKabupaten || '')}`);
                        this.tbMapKecamatanList = await res.json();
                    } catch (e) { }
                },

                saveTbMapCfg(showFeedback = false) {
                    try {
                        localStorage.setItem('tb_map_cfg', JSON.stringify(this.tbMapCfg));
                        if (showFeedback) {
                            this.tbMapCfgSaved = true;
                            setTimeout(() => { this.tbMapCfgSaved = false; }, 2500);
                        }
                    } catch (e) { }
                },

                resetTbMapCfg() {
                    this.tbMapCfg = { kecamatan: '', viewMode: 'markers', height: 460, showPenderita: true, showInvestigasi: true };
                    try { localStorage.removeItem('tb_map_cfg'); } catch (e) { }
                    this.tbMapCfgSaved = true;
                    setTimeout(() => { this.tbMapCfgSaved = false; }, 1800);
                    this.onTbMapKecamatanChange();
                },

                resizeTbMap() {
                    this.$nextTick(() => {
                        if (tbCharts.map) {
                            tbCharts.map.invalidateSize();
                        }
                    });
                },

                async onTbMapKecamatanChange() {
                    this.saveTbMapCfg();
                    await this.loadTbMapGeoData();
                    this.updateMap();
                },

                async loadTbMapGeoData() {
                    this.tbMapLoading = true;
                    try {
                        const params = new URLSearchParams({
                            kabupaten: this.selectedKabupaten || '',
                            kecamatan: this.tbMapCfg.kecamatan || '',
                        });
                        const res = await fetch(`{{ route('tb.map.data') }}?${params.toString()}`);
                        const data = await res.json();
                        this.mapData = data.points || [];
                        // Re-center map on new kecamatan
                        if (tbCharts.map && data.center) {
                            tbCharts.map.setView([data.center.lat, data.center.lng], data.center.zoom || 13);
                        }
                    } catch (e) {
                        console.error('TB map data error:', e);
                    } finally {
                        this.tbMapLoading = false;
                    }
                },

                // ── Map Init & Render ───────────────────────────────────────────

                initMap() {
                    const init = () => {
                        const mapElem = document.getElementById('tbMap');
                        if (!mapElem) return;

                        if (typeof L === 'undefined') {
                            setTimeout(init, 100);
                            return;
                        }

                        if (tbCharts.map) {
                            try { tbCharts.map.remove(); } catch (e) { }
                            tbCharts.map = null;
                        }

                        tbCharts.map = L.map('tbMap').setView([-6.2889, 106.6092], 12);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                            maxZoom: 18
                        }).addTo(tbCharts.map);

                        tbCharts.markersLayer = L.layerGroup().addTo(tbCharts.map);
                        tbCharts.fasyanksLayer = L.layerGroup().addTo(tbCharts.map);
                        this.updateMap();

                        [100, 300, 600, 1000].forEach(delay => {
                            setTimeout(() => { if (tbCharts.map) tbCharts.map.invalidateSize(); }, delay);
                        });
                    };
                    init();
                },

                updateMap() {
                    if (!tbCharts.map || !tbCharts.markersLayer || typeof L === 'undefined') return;

                    tbCharts.markersLayer.clearLayers();
                    if (tbCharts.fasyanksLayer) tbCharts.fasyanksLayer.clearLayers();

                    if (!this.mapData || this.mapData.length === 0) return;

                    const bounds = [];
                    const showPenderita = this.tbMapCfg.showPenderita !== false;
                    const showInvestigasi = this.tbMapCfg.showInvestigasi !== false;

                    this.mapData.forEach(item => {
                        const pTotal = item.penderita_total !== undefined ? item.penderita_total : (item.total || 0);
                        const ikTotal = item.investigasi_total || 0;

                        // ── 1. Titik Merah: Data Penderita TB (TB-03) ──
                        if (showPenderita && pTotal > 0) {
                            const latP = item.lat_penderita || item.lat;
                            const lngP = item.lng_penderita || item.lng;

                            if (latP && lngP) {
                                bounds.push([latP, lngP]);
                                const pRadius = Math.max(9, Math.min(22, 6 + Math.sqrt(pTotal) * 2.8));

                                const redCircle = L.circleMarker([latP, lngP], {
                                    color: '#b91c1c',
                                    fillColor: '#ef4444',
                                    fillOpacity: 0.88,
                                    radius: pRadius,
                                    weight: 2,
                                });

                                redCircle.bindPopup(`
                                    <div style="font-family:inherit;font-size:12px;min-width:210px;padding:2px;">
                                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px;">
                                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ef4444;border:2px solid #b91c1c;"></span>
                                            <div style="font-weight:700;font-size:13px;color:#1e293b;">${item.kelurahan}</div>
                                        </div>
                                        <div style="color:#64748b;font-size:11px;margin-bottom:6px;">${item.kecamatan ? 'Kec. ' + item.kecamatan + ' · ' : ''}${item.kabupaten || ''}</div>
                                        <div style="background:#fef2f2;border:1px solid #fee2e2;border-radius:8px;padding:8px 10px;">
                                            <div style="font-weight:700;font-size:10px;color:#991b1b;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">📊 Data Penderita TB (TB-03)</div>
                                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#1e293b;margin-bottom:2px;">
                                                <span hidden>Total Kasus TBC:</span>
                                                <strong style="color:#dc2626;">${pTotal} pasien</strong>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:11px;color:#475569;margin-bottom:2px;">
                                                <span>Sedang Terapi OAT:</span>
                                                <strong style="color:#b91c1c;">${item.penderita_active || item.total_active || 0}</strong>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:11px;color:#475569;">
                                                <span>Sembuh / Lengkap:</span>
                                                <strong style="color:#15803d;">${item.penderita_sembuh || 0}</strong>
                                            </div>
                                        </div>
                                    </div>
                                `);

                                tbCharts.markersLayer.addLayer(redCircle);
                            }
                        }

                        // ── 2. Titik Hijau: Investigasi Kontak (TB-16K) ──
                        if (showInvestigasi && ikTotal > 0) {
                            const latIK = item.lat_investigasi || item.lat;
                            const lngIK = item.lng_investigasi || item.lng;

                            if (latIK && lngIK) {
                                bounds.push([latIK, lngIK]);
                                const ikRadius = Math.max(9, Math.min(24, 6 + Math.sqrt(ikTotal) * 1.6));

                                const greenCircle = L.circleMarker([latIK, lngIK], {
                                    color: '#059669',
                                    fillColor: '#10b981',
                                    fillOpacity: 0.88,
                                    radius: ikRadius,
                                    weight: 2,
                                });

                                greenCircle.bindPopup(`
                                    <div style="font-family:inherit;font-size:12px;min-width:220px;padding:2px;">
                                        <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px;">
                                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#10b981;border:2px solid #059669;"></span>
                                            <div style="font-weight:700;font-size:13px;color:#1e293b;">${item.kelurahan}</div>
                                        </div>
                                        <div style="color:#64748b;font-size:11px;margin-bottom:6px;">${item.kecamatan ? 'Kec. ' + item.kecamatan + ' · ' : ''}${item.kabupaten || ''}</div>
                                        <div style="background:#ecfdf5;border:1px solid #d1fae5;border-radius:8px;padding:8px 10px;">
                                            <div style="font-weight:700;font-size:10px;color:#065f46;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">🔎 Investigasi Kontak (TB-16K)</div>
                                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#1e293b;margin-bottom:2px;">
                                                <span>Kontak Dilacak:</span>
                                                <strong style="color:#059669;">${ikTotal} orang</strong>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:11px;color:#475569;margin-bottom:2px;">
                                                <span>Kasus Indeks Terkait:</span>
                                                <strong style="color:#047857;">${item.investigasi_indeks || 0} pasien</strong>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:11px;color:#475569;margin-bottom:2px;">
                                                <span>Serumah / Erat:</span>
                                                <strong style="color:#0f766e;">${item.investigasi_serumah || 0} / ${item.investigasi_erat || 0}</strong>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;font-size:11px;color:#475569;">
                                                <span>Ditemukan Sakit TBC:</span>
                                                <strong style="color:#dc2626;">${item.investigasi_sakit || 0}</strong>
                                            </div>
                                        </div>
                                    </div>
                                `);

                                tbCharts.markersLayer.addLayer(greenCircle);
                            }
                        }
                    });

                    if (bounds.length > 0) {
                        tbCharts.map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
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
                        // Validate extension
                        const ext = file.name.split('.').pop().toLowerCase();
                        if (!['xlsx', 'xls', 'csv', 'txt'].includes(ext)) {
                            alert('Format berkas tidak didukung. Harap masukkan berkas .xlsx, .xls, atau .csv');
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
                        const res = await fetch(`{{ route('tb.import.preview') }}`, {
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
                            alert(data.message || 'Gagal mem-parsing berkas');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan saat mengunggah berkas: ' + e.message);
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
                        const res = await fetch(`{{ route('tb.import.commit') }}`, {
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
                            this.notify('error', 'Gagal Import', data.message || 'Gagal mengimpor data berkas');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Sistem', 'Terjadi kesalahan sistem saat menyimpan import.');
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
                        const res = await fetch(`/tb/patients/${this.editingPatient.id}`, {
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
                            // Realtime table update
                            const idx = this.patientsData.data.findIndex(p => p.id === this.editingPatient.id);
                            if (idx !== -1) {
                                this.patientsData.data[idx] = data.data;
                            }
                            // Refresh stats & charts to reflect edit
                            this.applyFilters(this.patientsData.current_page);
                            this.notify('success', 'Data Diperbarui', 'Rekam data pasien berhasil diupdate secara realtime.');
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
                        const res = await fetch(`{{ route('tb.patients.store') }}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                            body: JSON.stringify(this.newPatient)
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showAddModal = false;
                            this.applyFilters();
                            this.loadKelurahanList();
                            this.notify('success', 'Pasien Ditambahkan', 'Data pasien baru berhasil disimpan ke database.');
                        } else {
                            this.aiAddError = data.message || 'Gagal menyimpan data pasien.';
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
                        const res = await fetch(`/tb/patients/${patient.id}`, {
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

                // Clear Massive
                openClearMassiveModal() {
                    this.clearMassiveKeyword = '';
                    this.showClearMassiveModal = true;
                },

                async executeClearMassive() {
                    if (this.clearMassiveKeyword.trim() !== 'HAPUS' || this.isClearingMassive) return;

                    this.isClearingMassive = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const res = await fetch(`{{ route('tb.patients.clear-massive') }}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.showClearMassiveModal = false;
                            this.clearMassiveKeyword = '';
                            // Refresh filters, list, charts & map to empty state
                            this.applyFilters(1);
                            this.loadKelurahanList();
                            this.notify('success', 'Clear Data Berhasil', data.message || 'Semua data pasien TBC berhasil dikosongkan.');
                        } else {
                            this.notify('error', 'Gagal Clear Data', data.message || 'Gagal mengosongkan data pasien.');
                        }
                    } catch (e) {
                        this.notify('error', 'Kesalahan Sistem', 'Terjadi kesalahan sistem saat mengosongkan data: ' + e.message);
                    } finally {
                        this.isClearingMassive = false;
                    }
                },

                // ================= INOVASI 1: WHATSAPP DIRECT (wa.me) =================
                openWhatsAppModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.waTemplateType = 'oat_daily';
                    this.showWhatsAppModal = true;
                    this.prepareWaMessage();
                },

                prepareWaMessage() {
                    if (!this.targetPatient) return;
                    const p = this.targetPatient;
                    const kelStr = p.kelurahan || 'Puskesmas';
                    const diagnosis = p.hasil_diagnosis || p.hasil_tcm || 'TBC';

                    this.waPhone = p.no_telepon || '';

                    if (this.waTemplateType === 'sputum_eval') {
                        this.waMessage = `Halo Bpk/Ibu ${p.nama_lengkap || ''},\n\nPemberitahuan dari Tim Penanggulangan TBC Puskesmas ${kelStr} (SICEPOT):\nMengingatkan bahwa sudah waktunya untuk pemeriksaan dahak ulang (evaluasi laboratorium akhir bulan ke-2 / ke-5).\n\nPemeriksaan dahak sangat krusial untuk memastikan kuman TBC telah berkurang/hilang dan efektivitas obat berjalan baik.\n\nMohon hadir ke laboratorium puskesmas pada hari kerja membawa pot dahak. Pelayanan gratis. Mari tuntaskan pengobatan hingga sembuh!`;
                    } else if (this.waTemplateType === 'dropout_warning') {
                        this.waMessage = `PERINGATAN KESEHATAN TBC (Puskesmas ${kelStr})\n\nKepada Bpk/Ibu ${p.nama_lengkap || ''},\nBerdasarkan data SITB/SICEPOT, Anda terindikasi terlambat/belum mengambil obat TBC (OAT) sesuai jadwal.\n\nPENTING:\nPutus minum obat TBC berisiko tinggi menyebabkan resistensi kuman (TB Kebal Obat / MDR-TB) yang jauh lebih berbahaya dan memerlukan pengobatan bertahun-tahun.\n\nHarap SEGERA datang ke Puskesmas ${kelStr} hari ini atau hubungi petugas kami untuk pendampingan. Kami siap membantu Anda sampai tuntas.`;
                    } else {
                        // Default: Pengingat Minum Obat Harian
                        this.waMessage = `Halo Bpk/Ibu ${p.nama_lengkap || ''},\n\nSalam sehat dari Petugas TBC Puskesmas ${kelStr} (SICEPOT).\nMengingatkan untuk tidak lupa meminum Obat Anti Tuberkulosis (OAT) hari ini secara teratur pada jam yang sama bersama Pengawas Minum Obat (PMO).\n\nKunci kesembuhan TBC adalah kedisiplinan minum obat tanpa terlewat satu hari pun. Tetap semangat menjalani pengobatan hingga tuntas!`;
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

                // ================= INOVASI 2: SKRINING DUPLIKASI DATA =================
                async openDuplicateModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showDuplicateModal = true;
                    this.isLoadingDuplicates = true;
                    this.duplicateResults = [];

                    try {
                        const res = await fetch(`/tb/patients/${patient.id}/duplicates`, {
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

                // ================= INOVASI 2: REKAM JEJAK / TIMELINE =================
                async openTimelineModal(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showTimelineModal = true;
                    this.isLoadingTimeline = true;
                    this.timelineData = [];

                    try {
                        const res = await fetch(`/tb/patients/${patient.id}/timeline`, {
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

                // ================= INOVASI 5: AI TRIAGE (GEMINI 2.5 FLASH) =================
                async openAiTriage(patient) {
                    if (!patient) return;
                    this.targetPatient = JSON.parse(JSON.stringify(patient));
                    this.showAiModal = true;
                    this.isLoadingAi = true;
                    this.aiAnalysisResult = '';

                    try {
                        const res = await fetch(`/tb/patients/${patient.id}/ai-triage`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.aiAnalysisResult = data.content;
                        } else {
                            this.aiAnalysisResult = 'Gagal melakukan analisis AI: ' + (data.message || 'Koneksi API bermasalah.');
                        }
                    } catch (e) {
                        this.aiAnalysisResult = 'Terjadi kesalahan saat menghubungi layanan Google Gemini: ' + e.message;
                    } finally {
                        this.isLoadingAi = false;
                    }
                },

                formatAiContent(content) {
                    if (!content) return '';
                    let formatted = content
                        .replace(/### (.*?)\n/g, '<h4 class="font-bold text-sm text-slate-800 mt-3 mb-1">$1</h4>')
                        .replace(/## (.*?)\n/g, '<h3 class="font-bold text-base text-purple-900 mt-4 mb-2 pb-1 border-b border-purple-100">$1</h3>')
                        .replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-slate-900">$1</strong>')
                        .replace(/\* (.*?)\n/g, '<div class="flex items-start gap-1.5 my-1 ml-2"><span class="text-purple-600 font-bold">•</span><span>$1</span></div>')
                        .replace(/- (.*?)\n/g, '<div class="flex items-start gap-1.5 my-1 ml-2"><span class="text-purple-600 font-bold">•</span><span>$1</span></div>');
                    return formatted;
                }
            };
        }

    </script>
