<x-app-layout>
    <div class="ant-content">
        <div class="max-w-5xl mx-auto space-y-6">

            <!-- PAGE HEADER -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-ant-split">
                <div>
                    <h2 class="ant-page-title flex items-center gap-2.5">
                        <span class="p-2 rounded-ant-lg bg-primary-50 text-primary border border-primary-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        Pengaturan Sistem
                    </h2>
                    <p class="ant-page-subtitle">Konfigurasi notifikasi realtime, alert sirine HP, dan koneksi server</p>
                </div>
            </div>

            <!-- GRID CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- CARD 1: KONEKSI REALTIME WEBSOCKET -->
                <div class="ant-card">
                    <div class="ant-card-head">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-ant-lg bg-success-50 text-success flex items-center justify-center font-bold">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="ant-card-title">Status Koneksi Realtime</h3>
                                <p class="ant-card-extra">PieSocket WebSocket Server (Room 1)</p>
                            </div>
                        </div>
                    </div>

                    <div class="ant-card-body space-y-4">
                        <div class="p-4 rounded-ant-lg bg-ant-layout border border-ant-split flex items-center justify-between">
                            <div>
                                <span class="text-[13px] font-medium text-ant-secondary block">Status Jaringan:</span>
                                <div class="mt-1">
                                    <div id="piesocket-badge" class="ant-tag ant-tag-gray">
                                        <span id="piesocket-dot" class="w-2 h-2 rounded-full bg-ant-disabled"></span>
                                        <span id="piesocket-label">Menghubungkan...</span>
                                    </div>
                                </div>
                            </div>
                            <button type="button"
                                onclick="if(window.pieSocketClient) { window.pieSocketClient.connect(); }"
                                class="ant-btn ant-btn-default ant-btn-sm">
                                Refresh Koneksi
                            </button>
                        </div>

                        <div class="ant-text-secondary space-y-2 leading-relaxed">
                            <p class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Realtime broadcast aktif untuk <strong>Deteksi &amp; Alert Kelahiran Baru</strong> antar faskes secara instan tanpa delay.</span>
                            </p>
                            <p class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-success shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Koneksi WebSocket otomatis melakukan *auto-reconnect* jika jaringan terputus sementara.</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: ALERT SIRINE & NOTIFIKASI HP -->
                <div class="ant-card">
                    <div class="ant-card-head">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-ant-lg bg-danger-50 text-danger flex items-center justify-center font-bold">
                                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="ant-card-title">Alert Sirine &amp; Notifikasi HP</h3>
                                <p class="ant-card-extra">Audio darurat &amp; Web Notification browser</p>
                            </div>
                        </div>
                    </div>

                    <div class="ant-card-body space-y-4">
                        <div class="p-4 rounded-ant-lg bg-ant-layout border border-ant-split flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-[13px] font-medium text-ant-secondary block">Status Alert HP:</span>
                                <div class="mt-1">
                                    <span id="btn-enable-hp-alert" class="ant-tag ant-tag-red">
                                        <svg class="w-3.5 h-3.5 text-danger animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <span id="txt-enable-hp-alert">Belum Aktif</span>
                                    </span>
                                </div>
                            </div>

                            <button type="button"
                                onclick="window.pieSocketClient && window.pieSocketClient.enableAlertNotifications()"
                                class="ant-btn ant-btn-success">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Aktifkan Alert di HP</span>
                            </button>
                        </div>

                        <!-- Tombol Test Audio Sirine -->
                        <div class="flex items-center justify-between p-3.5 rounded-ant-lg border border-ant-split bg-white">
                            <div>
                                <span class="text-[13px] font-medium text-ant-title block">Uji Bunyi Sirine Audio</span>
                                <span class="text-[12px] text-ant-secondary">Pastikan volume speaker perangkat menyala</span>
                            </div>
                            <button type="button"
                                onclick="if(window.pieSocketClient && window.pieSocketClient.audio) {
                                    const a = window.pieSocketClient.audio;
                                    a.currentTime = 0;
                                    a.play();
                                    setTimeout(() => { a.pause(); a.currentTime = 0; }, 3000);
                                }"
                                class="ant-btn ant-btn-default ant-btn-sm">
                                🔊 Tes Suara (3 detik)
                            </button>
                        </div>

                        <p class="ant-text-secondary leading-relaxed">
                            Ketika data kelahiran diinput oleh bidan atau puskesmas, smartphone/desktop yang telah mengaktifkan izin ini akan otomatis berdering dengan suara sirine peringatan darurat dan menampilkan banner notifikasi langsung.
                        </p>
                    </div>
                </div>

            </div>

            <!-- CARD 3: MANAJEMEN PENGGUNA & ROLE AKSES -->
            <div class="ant-card">
                <div class="ant-card-head">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-ant-lg bg-primary-50 text-primary flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="ant-card-title">Manajemen Hak Akses &amp; Role (Spatie Permissions)</h3>
                            <p class="ant-card-extra">Daftar akun dan hak akses pada sistem SICEPOT</p>
                        </div>
                    </div>
                </div>

                <div class="ant-card-body">
                    <div class="overflow-x-auto">
                        <table class="ant-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Hak Akses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\User::with('roles')->get() as $u)
                                    <tr>
                                        <td class="font-semibold text-ant-title">{{ $u->name }}</td>
                                        <td class="text-ant-secondary">{{ $u->email }}</td>
                                        <td>
                                            @foreach($u->roles as $r)
                                                <span class="ant-tag {{ $r->name === 'Admin' ? 'ant-tag-blue' : 'ant-tag-gray' }}">
                                                    {{ $r->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($u->hasRole('Admin'))
                                                <span class="text-success-600 font-semibold">Akses Penuh (Create, Edit, Delete, Import, Clear Data, Settings)</span>
                                            @elseif($u->hasRole('Guest'))
                                                <span class="text-ant-secondary font-medium">Hanya Melihat (Read-Only) / Tanpa Aksi</span>
                                            @else
                                                <span class="text-ant-disabled">Belum memiliki role</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CARD 4: INFORMASI SISTEM & KREDENSIAL FASYANKES -->
            <div class="ant-card">
                <div class="ant-card-body">
                    <h3 class="ant-section-title mb-4 pb-2 border-b border-ant-split flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Integrasi &amp; Lingkungan Sistem
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-[13px]">
                        <div class="p-4 rounded-ant-lg bg-ant-layout border border-ant-split">
                            <span class="text-ant-secondary block mb-1">Aplikasi</span>
                            <span class="font-semibold text-ant-title">SICEPOT v2.0 (Sistem Cepat Post &amp; Tracking)</span>
                        </div>
                        <div class="p-4 rounded-ant-lg bg-ant-layout border border-ant-split">
                            <span class="text-ant-secondary block mb-1">AI Intelligence</span>
                            <span class="font-semibold text-ant-title">Google Gemini 2.5 Flash Triage Engine</span>
                        </div>
                        <div class="p-4 rounded-ant-lg bg-ant-layout border border-ant-split">
                            <span class="text-ant-secondary block mb-1">Realtime Engine</span>
                            <span class="font-semibold text-ant-title">PieSocket WebSocket Protocol (WSS)</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
