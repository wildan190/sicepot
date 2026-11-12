<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SICEPOT') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">

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
    <body class="font-sans">
        @php
            $breadcrumbLabel = match (true) {
                request()->routeIs('anc.*') => 'Dashboard Ibu Hamil',
                request()->routeIs('stunting.*') => 'Dashboard Stunting',
                request()->routeIs('ai.*') => 'AI Dashboard',
                request()->routeIs('settings.*') => 'Pengaturan',
                request()->routeIs('profile.*') => 'Profil',
                request()->routeIs('tb.*'), request()->routeIs('dashboard') => 'Dashboard TBC',
                default => 'Beranda',
            };
        @endphp

        <div x-data="{ sidebarOpen: false }" class="ant-layout">
            @include('layouts.navigation')

            <div class="flex min-h-screen flex-col lg:pl-56">
                <!-- ── Header ──────────────────────────────────────────────── -->
                <header class="sticky top-0 z-30 flex h-14 items-center justify-between gap-3 border-b border-ant-split bg-white px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" @click="sidebarOpen = true"
                            class="ant-btn ant-btn-text !px-2 lg:hidden" aria-label="Buka menu">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <nav class="ant-breadcrumb min-w-0">
                            <a href="{{ route('dashboard') }}" class="hidden sm:inline">Beranda</a>
                            <span class="hidden sm:inline text-ant-disabled">/</span>
                            <span class="truncate font-medium text-ant-title">{{ $breadcrumbLabel }}</span>
                        </nav>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Realtime WebSocket Status -->
                        <span id="piesocket-badge" class="ant-tag ant-tag-gray hidden sm:inline-flex">
                            <span id="piesocket-dot" class="h-1.5 w-1.5 rounded-full bg-ant-disabled"></span>
                            <span id="piesocket-label">Menghubungkan...</span>
                        </span>
                        <span id="piesocket-badge-mobile" class="h-2 w-2 rounded-full bg-ant-disabled sm:hidden"></span>

                        <!-- Alert HP -->
                        <button type="button" id="btn-enable-hp-alert"
                            onclick="window.pieSocketClient && window.pieSocketClient.enableAlertNotifications()"
                            class="ant-btn ant-btn-default ant-btn-sm">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3A6 6 0 006 11v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span id="txt-enable-hp-alert" class="hidden sm:inline">Alert HP</span>
                        </button>

                        <!-- User -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="ant-btn ant-btn-default">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-50 text-[11px] font-semibold text-primary">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                    </span>
                                    <span class="hidden max-w-[9rem] truncate font-medium text-ant-title sm:inline">{{ Auth::user()->name }}</span>
                                    @if(Auth::user()->hasRole('Admin'))
                                        <span class="ant-tag ant-tag-blue hidden md:inline-flex">Admin</span>
                                    @elseif(Auth::user()->hasRole('Guest'))
                                        <span class="ant-tag ant-tag-gray hidden md:inline-flex">Guest</span>
                                    @endif
                                    <svg class="h-3.5 w-3.5 text-ant-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="border-b border-ant-split px-3 py-2 sm:hidden">
                                    <div class="text-[13px] font-medium text-ant-title">{{ Auth::user()->name }}</div>
                                    <div class="truncate text-[11px] text-ant-secondary">{{ Auth::user()->email }}</div>
                                </div>

                                @role('Admin')
                                    <x-dropdown-link :href="route('settings.index')">{{ __('Pengaturan Sistem') }}</x-dropdown-link>
                                @endrole

                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profil') }}</x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Keluar') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- ── Page Heading ────────────────────────────────────────── -->
                @isset($header)
                    <div class="border-b border-ant-split bg-white">
                        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- ── Page Content ────────────────────────────────────────── -->
                <main class="flex-1">
                    {{ $slot }}
                </main>

                <footer class="border-t border-ant-split bg-white px-4 py-3 sm:px-6">
                    <p class="text-[12px] text-ant-secondary">
                        SICEPOT &middot; Sistem Cepat Post &amp; Tracking &middot; {{ now()->year }}
                    </p>
                </footer>
            </div>
        </div>

        <!-- ================= BANNER PERMINTAAN IZIN NOTIFIKASI & SIRINE HP ================= -->
        <div id="notification-permission-banner" class="fixed bottom-4 left-4 right-4 z-[9998] w-full max-w-md rounded-ant-lg border border-ant-border bg-white p-4 shadow-ant-dropdown sm:left-auto sm:right-6" style="display: none;">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-danger-50 text-danger-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3A6 6 0 006 11v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </span>
                <div class="flex-1">
                    <h4 class="text-[13px] font-medium text-ant-title">Aktifkan Alert Kelahiran Realtime</h4>
                    <p class="mt-1 text-[12px] leading-relaxed text-ant-secondary">
                        Izinkan notifikasi agar sirine dan popup darurat kelahiran dapat berbunyi langsung di perangkat Anda.
                    </p>
                    <div class="mt-3 flex items-center gap-2">
                        <button type="button"
                            onclick="window.pieSocketClient && window.pieSocketClient.enableAlertNotifications()"
                            class="ant-btn ant-btn-primary ant-btn-sm">
                            Izinkan &amp; Aktifkan
                        </button>
                        <button type="button"
                            onclick="document.getElementById('notification-permission-banner').style.display='none'"
                            class="ant-btn ant-btn-text ant-btn-sm">
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
                            if (this.audio) {
                                this.audio.load();
                            }
                            this.updateUiState();
                            window.removeEventListener('click', unlock);
                            window.removeEventListener('touchstart', unlock);
                        };
                        window.addEventListener('click', unlock, { once: true });
                        window.addEventListener('touchstart', unlock, { once: true });

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

                        if ('Notification' in window) {
                            try {
                                const perm = await Notification.requestPermission();
                                notifGranted = (perm === 'granted');
                                this.hasNotificationPermission = notifGranted;
                            } catch (err) {
                                console.warn('Request notification permission error:', err);
                            }
                        }

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

                        const banner = document.getElementById('notification-permission-banner');
                        if (banner) {
                            banner.style.display = 'none';
                        }

                        this.updateUiState();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: notifGranted ? 'Izin Notifikasi & Audio Diberikan' : 'Sirine Suara HP Aktif',
                                html: `
                                    <div style="text-align:left;font-size:13px;color:#595959;line-height:1.6">
                                        <p style="color:#389e0d;font-weight:600;margin-bottom:8px">
                                            ${notifGranted ? 'Notifikasi browser dan sirine' : 'Sirine audio'} siaga berhasil diaktifkan.
                                        </p>
                                        <p>Saat ada pencatatan kelahiran baru, sistem akan membunyikan sirine dan menampilkan popup darurat melalui WebSocket PieSocket tanpa perlu aplikasi tambahan.</p>
                                    </div>
                                `,
                                icon: 'success',
                                confirmButtonText: 'Baik, Siaga Aktif',
                                confirmButtonColor: '#1677ff',
                                customClass: {
                                    popup: 'rounded-ant-lg',
                                    confirmButton: 'ant-btn ant-btn-primary'
                                }
                            });
                        }
                    }

                    updateUiState() {
                        const btn = document.getElementById('btn-enable-hp-alert');
                        const txt = document.getElementById('txt-enable-hp-alert');
                        if (btn && txt) {
                            if (this.isAudioUnlocked && this.hasNotificationPermission) {
                                btn.classList.remove('ant-btn-default');
                                btn.classList.add('ant-btn-primary');
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
                            this.updateBadge('disconnected');
                            this.scheduleReconnect();
                        };

                        this.ws.onerror = (err) => {
                            if (document.visibilityState === 'hidden') return;
                            console.warn('PieSocket WebSocket disconnected or error:', err);
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
                            badge.className = 'ant-tag ant-tag-green hidden sm:inline-flex';
                            dot.className = 'h-1.5 w-1.5 rounded-full bg-success-500';
                            if (label) label.textContent = 'Realtime Terhubung';
                            if (mobileDot) mobileDot.className = 'h-2 w-2 rounded-full bg-success-500 sm:hidden';
                        } else if (status === 'connecting') {
                            badge.className = 'ant-tag ant-tag-gold hidden sm:inline-flex';
                            dot.className = 'h-1.5 w-1.5 rounded-full bg-warning-500';
                            if (label) label.textContent = 'Menghubungkan...';
                            if (mobileDot) mobileDot.className = 'h-2 w-2 rounded-full bg-warning-500 sm:hidden';
                        } else {
                            badge.className = 'ant-tag ant-tag-red hidden sm:inline-flex';
                            dot.className = 'h-1.5 w-1.5 rounded-full bg-danger-500';
                            if (label) label.textContent = 'Terputus';
                            if (mobileDot) mobileDot.className = 'h-2 w-2 rounded-full bg-danger-500 sm:hidden';
                        }
                    }

                    handleMessage(payload) {
                        const eventName = payload.event || (payload.data && payload.data.type) || payload.type;

                        if (eventName === 'birth_alert' || (payload.data && payload.data.event === 'birth_alert')) {
                            const data = payload.data || payload;
                            this.triggerBirthAlert(data);
                        }
                    }

                    triggerBirthAlert(data) {
                        if (this.audio) {
                            this.audio.currentTime = 0;
                            this.audio.play().catch(e => {
                                console.warn('Sirine autoplay ditunda browser, butuh interaksi user:', e);
                            });
                        }

                        if ('Notification' in window && Notification.permission === 'granted') {
                            try {
                                new Notification('ALERT KELAHIRAN BARU', {
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

                        if (this.isAudioUnlocked && 'vibrate' in navigator) {
                            try { navigator.vibrate([300, 150, 300, 150, 600]); } catch (e) {}
                        }

                        if (typeof Swal !== 'undefined') {
                            const namaIbu = data.nama_lengkap || 'Pasien Ibu Hamil';
                            const namaSuami = data.nama_suami ? `(Suami: Tn. ${data.nama_suami})` : '';
                            const faskes = data.tempat_bersalin || 'Puskesmas / Faskes Terdekat';
                            const tgl = data.tanggal_bersalin || new Date().toISOString().substring(0, 10);
                            const penolong = data.penolong_persalinan || 'Bidan / Nakes';
                            const wilayah = `${data.kelurahan || '-'}, ${data.kabupaten || '-'}`;

                            Swal.fire({
                                title: 'Alert Kelahiran Realtime',
                                html: `
                                    <div style="text-align:left;font-size:13px;color:#595959;line-height:1.6">
                                        <div style="border:1px solid #ffccc7;background:#fff2f0;border-radius:6px;padding:12px;margin-bottom:12px">
                                            <div style="font-size:11px;color:#d9363e;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Pemberitahuan Persalinan Baru</div>
                                            <div style="font-size:16px;font-weight:600;color:#262626;margin-top:4px">${namaIbu} <span style="font-size:12px;font-weight:400;color:#8c8c8c">${namaSuami}</span></div>
                                            <div style="font-size:12px;color:#8c8c8c;margin-top:4px">Wilayah: <strong style="color:#262626">${wilayah}</strong></div>
                                        </div>
                                        <table style="width:100%;font-size:12px;border-collapse:collapse">
                                            <tr>
                                                <td style="padding:8px 10px;border:1px solid #f0f0f0;color:#8c8c8c;width:50%">Tanggal Bersalin</td>
                                                <td style="padding:8px 10px;border:1px solid #f0f0f0;color:#262626;font-weight:600">${tgl}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 10px;border:1px solid #f0f0f0;color:#8c8c8c">Tempat &amp; Penolong</td>
                                                <td style="padding:8px 10px;border:1px solid #f0f0f0;color:#262626">${faskes} &middot; ${penolong}</td>
                                            </tr>
                                        </table>
                                        <p style="font-size:11px;color:#bfbfbf;text-align:center;margin-top:12px">
                                            Disiarkan melalui PieSocket WebSocket Realtime.
                                        </p>
                                    </div>
                                `,
                                icon: 'warning',
                                confirmButtonText: 'Matikan Sirine & Tutup',
                                confirmButtonColor: '#ff4d4f',
                                allowOutsideClick: false,
                                customClass: {
                                    popup: 'rounded-ant-lg',
                                    confirmButton: 'ant-btn ant-btn-dangerous'
                                }
                            }).then(() => {
                                if (this.audio) {
                                    this.audio.pause();
                                    this.audio.currentTime = 0;
                                }
                            });
                        }

                        window.dispatchEvent(new CustomEvent('birth-alert-received', { detail: data }));
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    window.pieSocketClient = new PieSocketBirthAlertClient();
                });
            })();
        </script>
        @stack('scripts')
    </body>
</html>
