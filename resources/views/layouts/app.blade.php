<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <style>
            /* Ensure Leaflet stays strictly within its container and behind modals */
            .leaflet-pane { z-index: 10 !important; }
            .leaflet-top, .leaflet-bottom { z-index: 11 !important; }
            .map-container { isolation: isolate; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- ================= BANNER PERMINTAAN IZIN NOTIFIKASI & SIRINE HP ================= -->
        <div id="notification-permission-banner" class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 z-[9998] max-w-md w-full bg-slate-900/95 backdrop-blur-md text-white p-4 rounded-2xl shadow-2xl border border-rose-500/40" style="display: none;">
            <div class="flex items-start gap-3">
                <div class="p-2.5 rounded-xl bg-rose-500/20 text-rose-400 shrink-0 mt-0.5">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-white flex items-center gap-1.5">
                        <span>Aktifkan Alert Kelahiran Realtime</span>
                        <span class="px-1.5 py-0.2 bg-rose-500 text-[10px] uppercase font-black tracking-wider rounded">Siaga HP</span>
                    </h4>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                        Agar suara sirine dan notifikasi darurat kelahiran dapat berbunyi langsung di browser HP/laptop Anda tanpa aplikasi khusus, silakan izinkan akses notifikasi.
                    </p>
                    <div class="flex items-center gap-2 mt-3">
                        <button type="button" 
                            onclick="window.pieSocketClient && window.pieSocketClient.enableAlertNotifications()"
                            class="px-3.5 py-2 bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white font-bold text-xs rounded-xl shadow-md cursor-pointer transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Izinkan & Aktifkan Sirine</span>
                        </button>
                        <button type="button" 
                            onclick="document.getElementById('notification-permission-banner').style.display='none'"
                            class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white font-semibold text-xs rounded-xl cursor-pointer transition-colors">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PIESOCKET WEBSOCKET REALTIME CLIENT (KELAHIRAN ALERT) ================= -->
        <script>
            (function() {
                const PIESOCKET_WS_URL = "wss://free.blr2.piesocket.com/v3/1?api_key=8Z72V1NXBvXdXABPTyxgAvUvLWUl8ZsTAJdaqskK&notify_self=1";
                
                class PieSocketBirthAlertClient {
                    constructor() {
                        this.ws = null;
                        this.reconnectAttempts = 0;
                        this.maxReconnectDelay = 10000;
                        this.audio = null;
                        this.isAudioUnlocked = false;
                        this.hasNotificationPermission = ('Notification' in window) && Notification.permission === 'granted';

                        this.initAudio();
                        this.connect();
                        this.setupUserInteractionListener();
                        this.updateUiState();
                    }

                    initAudio() {
                        try {
                            this.audio = new Audio('/assets/sounds/sirine.mp3');
                            this.audio.loop = true;
                        } catch (e) {
                            console.warn('Gagal menginisialisasi audio sirine:', e);
                        }
                    }

                    setupUserInteractionListener() {
                        const unlock = () => {
                            this.isAudioUnlocked = true;
                            // Preload & resume audio context
                            if (this.audio) {
                                this.audio.load();
                            }
                            this.updateUiState();
                            window.removeEventListener('click', unlock);
                            window.removeEventListener('touchstart', unlock);
                        };
                        window.addEventListener('click', unlock, { once: true });
                        window.addEventListener('touchstart', unlock, { once: true });

                        // Periksa apakah izin notifikasi browser belum diminta
                        setTimeout(() => {
                            this.checkAndPromptPermission();
                        }, 1200);
                    }

                    checkAndPromptPermission() {
                        const isGranted = ('Notification' in window) && Notification.permission === 'granted';
                        const banner = document.getElementById('notification-permission-banner');
                        if (!isGranted && banner) {
                            banner.style.display = 'block';
                        }
                    }

                    async enableAlertNotifications() {
                        this.isAudioUnlocked = true;
                        let notifGranted = false;

                        // 1. Minta izin Notifikasi Browser (Web Notification API)
                        if ('Notification' in window) {
                            try {
                                const perm = await Notification.requestPermission();
                                notifGranted = (perm === 'granted');
                                this.hasNotificationPermission = notifGranted;
                            } catch (err) {
                                console.warn('Request notification permission error:', err);
                            }
                        }

                        // 2. Play dan pause sekejap untuk membuka izin Autoplay Audio di HP (Mobile Safari / Chrome)
                        if (this.audio) {
                            try {
                                this.audio.currentTime = 0;
                                await this.audio.play();
                                setTimeout(() => {
                                    this.audio.pause();
                                    this.audio.currentTime = 0;
                                }, 300);
                            } catch (e) {
                                console.warn('Audio test error:', e);
                            }
                        }

                        // Sembunyikan banner jika ada
                        const banner = document.getElementById('notification-permission-banner');
                        if (banner) {
                            banner.style.display = 'none';
                        }

                        this.updateUiState();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: notifGranted ? 'Izin Notifikasi & Audio Diberikan!' : 'Sirine Suara HP Aktif!',
                                html: `
                                    <div class="text-xs text-slate-600 text-left space-y-2">
                                        <p class="font-bold text-emerald-600">✓ ${notifGranted ? 'Notifikasi browser dan sirine' : 'Sirine audio'} siaga berhasil diaktifkan.</p>
                                        <p class="text-slate-500">Saat ada pencatatan kelahiran baru, sistem akan otomatis membunyikan sirine dan menampilkan popup darurat seketika melalui WebSocket PieSocket tanpa perlu aplikasi HP.</p>
                                    </div>
                                `,
                                icon: 'success',
                                confirmButtonText: 'Bagus, Siaga Aktif',
                                confirmButtonColor: '#10b981',
                                customClass: {
                                    popup: 'rounded-2xl sm:rounded-3xl shadow-2xl !w-[92vw] !max-w-md !p-4 sm:!p-6 !m-auto',
                                    confirmButton: 'rounded-xl font-bold px-6 py-2.5 text-xs sm:text-sm !w-full sm:!w-auto'
                                }
                            });
                        }
                    }

                    updateUiState() {
                        const btn = document.getElementById('btn-enable-hp-alert');
                        const txt = document.getElementById('txt-enable-hp-alert');
                        if (btn && txt) {
                            if (this.isAudioUnlocked && this.hasNotificationPermission) {
                                btn.classList.remove('bg-rose-50', 'text-rose-700', 'border-rose-200');
                                btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                                txt.textContent = 'Alert HP Aktif';
                            }
                        }
                    }

                    connect() {
                        this.updateBadge('connecting');
                        try {
                            this.ws = new WebSocket(PIESOCKET_WS_URL);
                        } catch (e) {
                            console.error('WebSocket connection error:', e);
                            this.scheduleReconnect();
                            return;
                        }

                        this.ws.onopen = () => {
                            console.log('✓ PieSocket WebSocket connected (Room: 1)');
                            this.reconnectAttempts = 0;
                            this.updateBadge('connected');
                        };

                        this.ws.onmessage = (event) => {
                            try {
                                const payload = JSON.parse(event.data);
                                this.handleMessage(payload);
                            } catch (e) {
                                console.warn('PieSocket non-JSON message:', event.data);
                            }
                        };

                        this.ws.onclose = () => {
                            console.warn('PieSocket connection closed.');
                            this.updateBadge('disconnected');
                            this.scheduleReconnect();
                        };

                        this.ws.onerror = (err) => {
                            console.error('PieSocket WebSocket error:', err);
                            this.updateBadge('disconnected');
                        };
                    }

                    scheduleReconnect() {
                        this.reconnectAttempts++;
                        const delay = Math.min(2000 * Math.pow(1.5, this.reconnectAttempts), this.maxReconnectDelay);
                        setTimeout(() => this.connect(), delay);
                    }

                    updateBadge(status) {
                        const badge = document.getElementById('piesocket-badge');
                        const dot = document.getElementById('piesocket-dot');
                        const label = document.getElementById('piesocket-label');
                        const mobileDot = document.getElementById('piesocket-badge-mobile');

                        if (!badge || !dot) return;

                        if (status === 'connected') {
                            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold rounded-xl';
                            dot.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
                            if (label) label.textContent = 'Realtime Terhubung';
                            if (mobileDot) mobileDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse';
                        } else if (status === 'connecting') {
                            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-semibold rounded-xl';
                            dot.className = 'w-2 h-2 rounded-full bg-amber-500 animate-ping';
                            if (label) label.textContent = 'Menghubungkan...';
                            if (mobileDot) mobileDot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping';
                        } else {
                            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-semibold rounded-xl';
                            dot.className = 'w-2 h-2 rounded-full bg-rose-500';
                            if (label) label.textContent = 'Terputus (Reconnect)';
                            if (mobileDot) mobileDot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
                        }
                    }

                    handleMessage(payload) {
                        console.log('🔔 PieSocket message received:', payload);
                        
                        // Parse event: can be directly payload.event or payload.type or inside data
                        const eventName = payload.event || (payload.data && payload.data.type) || payload.type;

                        // FILTER KETAT: Hanya berlaku untuk alert kelahiran!
                        if (eventName === 'birth_alert' || (payload.data && payload.data.event === 'birth_alert')) {
                            const data = payload.data || payload;
                            this.triggerBirthAlert(data);
                        }
                    }

                    triggerBirthAlert(data) {
                        // 1. Bunyikan sirine audio kencang
                        if (this.audio) {
                            this.audio.currentTime = 0;
                            this.audio.play().catch(e => {
                                console.warn('Sirine autoplay ditunda browser, butuh interaksi user:', e);
                            });
                        }

                        // 2. Tampilkan Web Notification Browser di HP (jika didukung)
                        if ('Notification' in window && Notification.permission === 'granted') {
                            try {
                                new Notification('🚨 ALERT KELAHIRAN BARU!', {
                                    body: `Telah lahir bayi dari ${data.nama_lengkap} di ${data.tempat_bersalin || 'Faskes'}. Kondisi: ${data.kondisi_bayi || 'Sehat'}`,
                                    icon: '/images/favicon.png',
                                    badge: '/images/favicon.png',
                                    vibrate: [200, 100, 200, 100, 400],
                                    tag: 'birth-alert-' + (data.patient_id || Date.now()),
                                    requireInteraction: true
                                });
                            } catch (e) {
                                console.warn('Error showing Web Notification:', e);
                            }
                        }

                        // 3. Vibrasi HP jika didukung
                        if ('vibrate' in navigator) {
                            try { navigator.vibrate([300, 150, 300, 150, 600]); } catch (e) {}
                        }

                        // 4. Tampilkan Modal Alert Darurat SweetAlert
                        if (typeof Swal !== 'undefined') {
                            const namaIbu = data.nama_lengkap || 'Pasien Ibu Hamil';
                            const namaSuami = data.nama_suami ? `(Suami: Tn. ${data.nama_suami})` : '';
                            const faskes = data.tempat_bersalin || 'Puskesmas / Faskes Terdekat';
                            const tgl = data.tanggal_bersalin || new Date().toISOString().substring(0, 10);
                            const kondisi = data.kondisi_bayi || 'Lahir Hidup / Sehat';
                            const berat = data.berat_lahir_bayi ? `${data.berat_lahir_bayi} kg` : '-';
                            const penolong = data.penolong_persalinan || 'Bidan / Nakes';
                            const wilayah = `${data.kelurahan || '-'}, ${data.kabupaten || '-'}`;

                            Swal.fire({
                                title: `
                                    <div class="flex items-center justify-center gap-2 text-rose-600">
                                        <span class="animate-ping inline-flex h-3 w-3 rounded-full bg-rose-500 opacity-75"></span>
                                        <span class="text-base sm:text-xl font-extrabold uppercase tracking-tight">ALERT KELAHIRAN REALTIME!</span>
                                    </div>
                                `,
                                html: `
                                    <div class="text-left text-xs sm:text-sm space-y-3 mt-3">
                                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl">
                                            <div class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Pemberitahuan Persalinan Baru</div>
                                            <div class="text-base font-extrabold text-slate-800 mt-0.5">${namaIbu} <span class="text-xs font-normal text-slate-500">${namaSuami}</span></div>
                                            <div class="text-slate-500 text-[11px] mt-0.5">Wilayah: <strong>${wilayah}</strong></div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                                            <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                                <span class="text-slate-400 block text-[10px]">Tanggal Bersalin</span>
                                                <span class="font-bold text-slate-800">${tgl}</span>
                                            </div>
                                            <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                                <span class="text-slate-400 block text-[10px]">Berat Bayi</span>
                                                <span class="font-bold text-emerald-700">${berat}</span>
                                            </div>
                                            <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                                <span class="text-slate-400 block text-[10px]">Kondisi Bayi</span>
                                                <span class="font-bold text-slate-800">${kondisi}</span>
                                            </div>
                                            <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                                <span class="text-slate-400 block text-[10px]">Tempat & Penolong</span>
                                                <span class="font-semibold text-slate-700">${faskes} • ${penolong}</span>
                                            </div>
                                        </div>

                                        <p class="text-[10px] sm:text-[11px] text-slate-400 text-center">
                                            Pesan disiarkan secara instan melalui <strong>PieSocket WebSocket Realtime (Room 1)</strong>.
                                        </p>
                                    </div>
                                `,
                                icon: 'warning',
                                confirmButtonText: 'Matikan Sirine & Tutup',
                                confirmButtonColor: '#e11d48',
                                allowOutsideClick: false,
                                customClass: {
                                    popup: 'rounded-2xl sm:rounded-3xl shadow-2xl border-2 border-rose-300 !w-[94vw] !max-w-lg !p-4 sm:!p-6 !m-auto',
                                    confirmButton: 'rounded-xl font-bold px-6 py-2.5 sm:py-3 text-xs sm:text-sm !w-full sm:!w-auto'
                                }
                            }).then(() => {
                                if (this.audio) {
                                    this.audio.pause();
                                    this.audio.currentTime = 0;
                                }
                            });
                        }

                        // 5. Trigger Alpine event agar komponen ANC Dashboard dapat me-refresh tabel secara otomatis
                        window.dispatchEvent(new CustomEvent('birth-alert-received', { detail: data }));
                    }
                }

                // Inisialisasi Singleton Client
                document.addEventListener('DOMContentLoaded', () => {
                    window.pieSocketClient = new PieSocketBirthAlertClient();
                });
            })();
        </script>
    </body>
</html>
