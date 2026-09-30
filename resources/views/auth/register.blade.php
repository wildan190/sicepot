<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5),0_0_0_1px_rgba(255,255,255,0.15)] p-8 sm:p-10 border border-white/20">
            <!-- Header -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-2.5 mb-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform">
                    <span class="text-xl font-extrabold tracking-tight text-slate-800">SICEPOT</span>
                </a>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Akun Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi formulir untuk membuat akun petugas</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4 text-xs">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input id="name" class="block w-full px-3.5 py-2.5 text-xs text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium placeholder:text-slate-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama petugas / kader" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-600 font-semibold" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input id="email" class="block w-full px-3.5 py-2.5 text-xs text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium placeholder:text-slate-400" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@puskesmas.go.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 font-semibold" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                    <input id="password" class="block w-full px-3.5 py-2.5 text-xs text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium placeholder:text-slate-400"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 font-semibold" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Sandi</label>
                    <input id="password_confirmation" class="block w-full px-3.5 py-2.5 text-xs text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium placeholder:text-slate-400"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-600 font-semibold" />
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-teal-600 hover:from-indigo-700 hover:to-teal-700 text-white font-bold text-xs shadow-md shadow-indigo-600/25 cursor-pointer transition-all">
                        Daftarkan Akun
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a class="text-xs text-slate-500 hover:text-indigo-600 transition-colors" href="{{ route('login') }}">
                        Sudah punya akun? <span class="font-bold text-indigo-600 underline">Masuk di sini</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
