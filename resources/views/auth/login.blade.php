<x-guest-layout :wide="true">
    <div class="w-full" x-data="{ showPassword: false }">

        <!-- Kartu split-screen -->
        <div class="grid overflow-hidden rounded-ant-lg border border-ant-split bg-white shadow-ant-card lg:grid-cols-2">

            <!-- ── Panel Branding ─────────────────────────────────────────── -->
            <aside
                class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 p-8 text-white lg:flex">
                <!-- Ornamen latar -->
                <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                    <div class="absolute -right-16 -top-20 h-60 w-60 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute -bottom-24 -left-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_0%,rgba(255,255,255,0.14),transparent_55%)]">
                    </div>
                </div>

                <!-- Logo -->
                <div class="relative flex items-center gap-3">
                    <div class="rounded-ant-lg bg-white/15 p-1.5 ring-1 ring-inset ring-white/25">
                        <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="h-8 w-8 rounded-ant object-cover">
                    </div>
                    <div>
                        <p class="text-[15px] font-semibold leading-none tracking-tight">SICEPOT</p>
                        <p class="mt-1 text-[11px] text-white/70">Sistem Cepat Post &amp; Tracking</p>
                    </div>
                </div>

                <!-- Headline -->
                <div class="relative mt-10">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-medium ring-1 ring-inset ring-white/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#b7eb8f]"></span>
                        Sistem Informasi Terintegrasi
                    </span>
                    <h1 class="mt-4 text-[26px] font-semibold leading-snug tracking-tight text-white">
                        Pelayanan Cepat,<br />Kesehatan Terpantau.
                    </h1>
                    <p class="mt-3 max-w-xs text-[13px] leading-relaxed text-white/75">
                        Satu pintu untuk monitoring dan intervensi cepat data TBC, Ibu Hamil (ANC), dan Balita Stunting.
                    </p>

                    <ul class="mt-7 space-y-2.5 text-[13px]">
                        <li class="flex items-center gap-3">
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-ant bg-white/15 ring-1 ring-inset ring-white/15">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </span>
                            <span class="text-white/90">ANC &amp; Ibu Hamil</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-ant bg-white/15 ring-1 ring-inset ring-white/15">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="text-white/90">Penanggulangan TBC</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-ant bg-white/15 ring-1 ring-inset ring-white/15">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            <span class="text-white/90">Pencegahan Stunting</span>
                        </li>
                    </ul>
                </div>

                <!-- Footer panel -->
                <div
                    class="relative mt-12 flex items-center justify-between border-t border-white/15 pt-4 text-[11px] text-white/65">
                    <span>Puskesmas &amp; Posyandu Wilayah</span>
                    <span
                        class="rounded-ant-sm bg-white/15 px-2 py-0.5 font-mono text-[10px] font-semibold text-white/90">v2.6
                        Secure</span>
                </div>
            </aside>

            <!-- ── Kolom Form ─────────────────────────────────────────────── -->
            <section class="flex flex-col p-6 sm:p-9">
                <div class="mb-6">
                    <h2 class="text-[20px] font-semibold tracking-tight text-ant-title">Masuk ke Akun Anda</h2>
                    <p class="mt-1 text-[13px] text-ant-secondary">
                        Masukkan kredensial petugas untuk mengakses dashboard.
                    </p>
                </div>

                <!-- Session Status Alert -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4 mb-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="ant-label">
                            Alamat Email
                        </label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-ant-disabled group-focus-within:text-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="nama@puskesmas.go.id"
                                class="ant-input h-10 !pl-9" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="ant-label !mb-0">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-[12px] font-medium text-primary hover:text-primary-600 transition-colors">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-ant-disabled group-focus-within:text-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="ant-input h-10 !pl-9 !pr-10" />

                            <!-- Toggle Password Visibility -->
                            <button type="button" @click="showPassword = !showPassword" tabindex="-1"
                                :title="showPassword ? 'Sembunyikan sandi' : 'Tampilkan sandi'"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-ant-secondary hover:text-primary transition-colors cursor-pointer">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <!-- Remember Me -->
                    <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 rounded-ant-sm border-ant-border text-primary focus:ring-primary-100 cursor-pointer">
                        <span class="text-[13px] text-ant-text transition-colors">Ingat sesi saya</span>
                    </label>

                    <!-- Submit Button -->
                    <div class="pt-1.5">
                        <x-primary-button class="ant-btn-block ant-btn-lg">
                            <span>Masuk ke Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </x-primary-button>
                    </div>
                </form>

                <!-- Footer Form -->
                <div
                    class="mt-auto pt-4 border-t border-ant-split flex items-center justify-between text-[11px] text-ant-secondary">
                    <span>Butuh bantuan? Hubungi admin puskesmas</span>
                    <span
                        class="px-2 py-0.5 rounded-ant-sm bg-ant-layout text-ant-text font-mono font-bold text-[10px]">v2.6</span>
                </div>
            </section>
        </div>

        <!-- Back to Home Link -->
        <div class="text-center mt-5">
            <a href="/"
                class="inline-flex items-center gap-1.5 text-[12px] text-ant-secondary hover:text-ant-title transition-colors">
                <svg class="w-3.5 h-3.5 text-ant-disabled" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Beranda Utama</span>
            </a>
        </div>

    </div>
</x-guest-layout>
