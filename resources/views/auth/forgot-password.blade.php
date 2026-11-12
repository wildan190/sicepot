<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <div class="ant-card p-6 sm:p-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-2.5 mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="w-10 h-10 rounded-ant object-cover">
                    <span class="text-xl font-semibold tracking-tight text-ant-title">SICEPOT</span>
                </a>
                <h3 class="ant-page-title">Lupa Kata Sandi</h3>
                <p class="ant-text-secondary mt-1">Masukkan alamat email terdaftar untuk menerima tautan pemulihan kata sandi.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email">Alamat Email</x-input-label>
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@puskesmas.go.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="ant-btn-block">
                        Kirim Tautan Pemulihan
                    </x-primary-button>
                </div>

                <div class="text-center pt-2">
                    <a class="text-xs text-ant-secondary hover:text-primary transition-colors" href="{{ route('login') }}">
                        Kembali ke <span class="font-semibold text-primary underline">Halaman Masuk</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
