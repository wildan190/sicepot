<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- PAGE HEADER -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        Pengaturan Sistem
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Konfigurasi notifikasi realtime, alert sirine HP, dan koneksi server</p>
                </div>
            </div>

            <!-- GRID CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- CARD 1: KONEKSI REALTIME WEBSOCKET -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Status Koneksi Realtime</h3>
                                <p class="text-xs text-slate-500">PieSocket WebSocket Server (Room 1)</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-slate-500 block">Status Jaringan:</span>
                                <div class="mt-1">
                                    <div id="piesocket-badge" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold rounded-xl transition-all">
                                        <span id="piesocket-dot" class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                                        <span id="piesocket-label">Menghubungkan...</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button"
                                onclick="if(window.pieSocketClient) { window.pieSocketClient.connect(); }"
                                class="px-3 py-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 border border-indigo-200 rounded-xl transition-colors cursor-pointer">
                                Refresh Koneksi
                            </button>
                        </div>

                        <div class="text-xs text-slate-600 space-y-2 leading-relaxed">
                            <p class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Realtime broadcast aktif untuk <strong>Deteksi &amp; Alert Kelahiran Baru</strong> antar faskes secara instan tanpa delay.</span>
                            </p>
                            <p class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Koneksi WebSocket otomatis melakukan *auto-reconnect* jika jaringan terputus sementara.</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: ALERT SIRINE & NOTIFIKASI HP -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Alert Sirine &amp; Notifikasi HP</h3>
                                <p class="text-xs text-slate-500">Audio darurat &amp; Web Notification browser</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-xs font-semibold text-slate-500 block">Status Alert HP:</span>
                                <div class="mt-1">
                                    <span id="btn-enable-hp-alert" class="inline-flex items-center gap-2 px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl transition-all">
                                        <svg class="w-3.5 h-3.5 text-rose-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <span id="txt-enable-hp-alert">Belum Aktif</span>
                                    </span>
                                </div>
                            </div>

                            <button type="button"
                                onclick="window.pieSocketClient && window.pieSocketClient.enableAlertNotifications()"
                                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Aktifkan Alert di HP</span>
                            </button>
                        </div>

                        <!-- Tombol Test Audio Sirine -->
                        <div class="flex items-center justify-between p-3.5 rounded-2xl border border-slate-200 bg-white">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Uji Bunyi Sirine Audio</span>
                                <span class="text-[11px] text-slate-400">Pastikan volume speaker perangkat menyala</span>
                            </div>
                            <button type="button"
                                onclick="if(window.pieSocketClient && window.pieSocketClient.audio) {
                                    const a = window.pieSocketClient.audio;
                                    a.currentTime = 0;
                                    a.play();
                                    setTimeout(() => { a.pause(); a.currentTime = 0; }, 3000);
                                }"
                                class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition cursor-pointer">
                                🔊 Tes Suara (3 detik)
                            </button>
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed">
                            Ketika data kelahiran diinput oleh bidan atau puskesmas, smartphone/desktop yang telah mengaktifkan izin ini akan otomatis berdering dengan suara sirine peringatan darurat dan menampilkan banner notifikasi langsung.
                        </p>
                    </div>
                </div>

            </div>

            <!-- CARD 3: INFORMASI SISTEM & KREDENSIAL FASYANKES -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Integrasi &amp; Lingkungan Sistem
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-400 block mb-1">Aplikasi</span>
                        <span class="font-bold text-slate-800">SICEPOT v2.0 (Sistem Cepat Post &amp; Tracking)</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-400 block mb-1">AI Intelligence</span>
                        <span class="font-bold text-slate-800">Google Gemini 2.5 Flash Triage Engine</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-400 block mb-1">Realtime Engine</span>
                        <span class="font-bold text-slate-800">PieSocket WebSocket Protocol (WSS)</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
