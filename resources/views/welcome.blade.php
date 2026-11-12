<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            {{-- Fallback: jalankan `npm run build` agar styling Ant Design aktif. --}}
        @endif
    </head>
    <body class="ant-layout font-sans">
        <div class="flex min-h-screen flex-col">
            <header class="border-b border-ant-split bg-ant-container">
                <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                    <a href="/" class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="h-8 w-8 rounded-ant object-cover">
                        <span class="text-[15px] font-semibold text-ant-title">SICEPOT</span>
                    </a>

                    @if (Route::has('login'))
                        <nav class="flex items-center gap-2">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="ant-btn ant-btn-primary">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="ant-btn ant-btn-default">
                                    Masuk
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ant-btn ant-btn-primary">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <main class="flex flex-1 items-center justify-center px-4 py-16 sm:px-6 lg:px-8">
                <div class="w-full max-w-2xl">
                    <div class="ant-card">
                        <div class="ant-card-body text-center">
                            <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="mx-auto h-14 w-14 rounded-ant-lg object-cover">

                            <h1 class="ant-page-title mt-4">SICEPOT</h1>
                            <p class="ant-page-subtitle">
                                Sistem Cepat Post &amp; Tracking &mdash; satu pintu pemantauan dan intervensi cepat
                                data kesehatan masyarakat.
                            </p>

                            <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="ant-btn ant-btn-primary ant-btn-lg">
                                        Buka Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="ant-btn ant-btn-primary ant-btn-lg">
                                        Masuk
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="ant-btn ant-btn-default ant-btn-lg">
                                            Daftar Akun
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="border-t border-ant-split bg-ant-container">
                <div class="mx-auto w-full max-w-6xl px-4 py-3 sm:px-6 lg:px-8">
                    <p class="text-[12px] text-ant-secondary">
                        SICEPOT &middot; v{{ app()->version() }}
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
