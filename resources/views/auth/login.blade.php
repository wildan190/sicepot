<x-guest-layout>
    <div class="w-full max-w-4xl mx-auto" x-data="{ showPassword: false, remember: false }">
        
        <!-- Main Container Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5),0_0_0_1px_rgba(255,255,255,0.15)] overflow-hidden border border-white/20 transition-all duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
                
                <!-- Left Column: Creative Visual & Branding Showcase -->
                <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-8 sm:p-10 text-white flex flex-col justify-between relative overflow-hidden">
                    <!-- Subtle Internal Ambient Orbs -->
                    <div class="absolute -top-24 -left-24 w-56 h-56 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-teal-500/15 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Top Branding -->
                    <div class="relative z-10">
                        <a href="/" class="inline-flex items-center gap-3 group">
                            <div class="p-2.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 shadow-inner group-hover:scale-105 group-hover:bg-white/15 transition-all duration-300">
                                <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="w-8 h-8 rounded-xl object-cover">
                            </div>
                            <div>
                                <span class="text-xl font-extrabold tracking-tight text-white block leading-none">SICEPOT</span>
                                <span class="text-[10px] text-indigo-200/80 font-medium tracking-wider uppercase mt-0.5 block">Kesehatan Terpadu</span>
                            </div>
                        </a>

                        <div class="mt-8">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>Sistem Informasi Terintegrasi</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black mt-4 tracking-tight leading-tight text-white">
                                Pelayanan Cepat, <br/>
                                <span class="bg-gradient-to-r from-teal-300 via-indigo-200 to-pink-300 bg-clip-text text-transparent">Kesehatan Terpantau.</span>
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300/90 mt-3 leading-relaxed">
                                Akses satu pintu untuk monitoring dan intervensi cepat data TBC, Ibu Hamil (ANC), dan Balita Stunting.
                            </p>
                        </div>
                    </div>

                    <!-- Middle Feature Highlights -->
                    <div class="relative z-10 my-6 space-y-2.5">
                        <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
                            <span class="w-8 h-8 rounded-xl bg-pink-500/20 text-pink-300 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </span>
                            <div class="text-xs">
                                <span class="font-bold text-white block">ANC & Ibu Hamil</span>
                                <span class="text-[11px] text-slate-400">Deteksi risiko dini & siaga persalinan</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
                            <span class="w-8 h-8 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div class="text-xs">
                                <span class="font-bold text-white block">Penanggulangan TBC</span>
                                <span class="text-[11px] text-slate-400">Tracking OAT & pengingat via WhatsApp</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xs">
                            <span class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </span>
                            <div class="text-xs">
                                <span class="font-bold text-white block">Pencegahan Stunting</span>
                                <span class="text-[11px] text-slate-400">Intervensi gizi & pemantauan e-PPGBM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Note -->
                    <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-slate-400">
                        <span>Puskesmas & Posyandu Wilayah</span>
                        <span class="px-2 py-0.5 rounded-full bg-white/10 text-white font-mono font-bold text-[10px]">v2.6 Secure</span>
                    </div>
                </div>

                <!-- Right Column: Interactive Login Form -->
                <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-center bg-white">
                    <div class="max-w-md w-full mx-auto">
                        
                        <!-- Header -->
                        <div class="mb-7">
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                Silakan masuk dengan kredensial akun Anda untuk mengakses dashboard.
                            </p>
                        </div>

                        <!-- Session Status Alert -->
                        <x-auth-session-status class="mb-5" :status="session('status')" />

                        <!-- Form -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            <!-- Email Input -->
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Alamat Email
                                </label>
                                <div class="relative rounded-2xl shadow-xs transition-all duration-200 group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                        </svg>
                                    </div>
                                    <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                        placeholder="nama@puskesmas.go.id"
                                        class="block w-full pl-10 pr-4 py-3 text-sm text-slate-800 bg-slate-50/70 hover:bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium placeholder:text-slate-400" />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-600 font-semibold" />
                            </div>

                            <!-- Password Input -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Kata Sandi
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                                            Lupa sandi?
                                        </a>
                                    @endif
                                </div>
                                <div class="relative rounded-2xl shadow-xs transition-all duration-200 group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                        placeholder="••••••••"
                                        class="block w-full pl-10 pr-11 py-3 text-sm text-slate-800 bg-slate-50/70 hover:bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium placeholder:text-slate-400" />
                                    
                                    <!-- Toggle Password Visibility -->
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                                        tabindex="-1">
                                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-600 font-semibold" />
                            </div>

                            <!-- Remember Me Toggle -->
                            <div class="flex items-center justify-between pt-1">
                                <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                    <input id="remember_me" type="checkbox" name="remember"
                                        class="w-4 h-4 rounded-md border-slate-300 text-indigo-600 shadow-xs focus:ring-indigo-500 cursor-pointer">
                                    <span class="text-xs font-medium text-slate-600 hover:text-slate-800 transition-colors">Ingat sesi saya</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full relative py-3.5 px-4 rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-teal-600 hover:from-indigo-700 hover:via-indigo-800 hover:to-teal-700 text-white font-bold text-sm tracking-wide shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer">
                                    <span>Masuk ke Dashboard</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </form>



                    </div>
                </div>

            </div>
        </div>

        <!-- Back to Home Link -->
        <div class="text-center mt-5">
            <a href="/" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors group">
                <svg class="w-3.5 h-3.5 text-slate-500 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Beranda Utama</span>
            </a>
        </div>

    </div>
</x-guest-layout>
