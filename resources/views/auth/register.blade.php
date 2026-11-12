<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <div class="ant-card p-6 sm:p-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-2.5 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="w-10 h-10 rounded-ant object-cover">
                    <span class="text-xl font-semibold tracking-tight text-ant-title">SICEPOT</span>
                </a>
                <h3 class="ant-page-title">Daftar Akun Baru</h3>
                <p class="ant-text-secondary mt-0.5">Lengkapi formulir untuk membuat akun petugas</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name">Nama Lengkap</x-input-label>
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama petugas / kader" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email">Alamat Email</x-input-label>
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@puskesmas.go.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password">Kata Sandi</x-input-label>
                    <x-text-input id="password"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation">Konfirmasi Sandi</x-input-label>
                    <x-text-input id="password_confirmation"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="ant-btn-block">
                        Daftarkan Akun
                    </x-primary-button>
                </div>

                <div class="text-center pt-2">
                    <a class="text-xs text-ant-secondary hover:text-primary transition-colors" href="{{ route('login') }}">
                        Sudah punya akun? <span class="font-semibold text-primary underline">Masuk di sini</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
