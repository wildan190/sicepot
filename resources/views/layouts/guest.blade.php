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
    </head>
    <body class="min-h-screen bg-ant-layout font-sans">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
            <div class="{{ $wide ? 'lg:hidden' : '' }} mb-6 flex flex-col items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="h-12 w-12 rounded-ant-lg object-cover">
                <div class="text-center">
                    <h1 class="text-[18px] font-semibold text-ant-title">SICEPOT</h1>
                    <p class="text-[12px] text-ant-secondary">Sistem Cepat Post &amp; Tracking</p>
                </div>
            </div>

            <div class="w-full {{ $wide ? 'max-w-5xl' : 'sm:max-w-md' }}">
                {{ $slot }}
            </div>

            <p class="mt-6 text-[12px] text-ant-secondary">&copy; {{ now()->year }} SICEPOT</p>
        </div>
    </body>
</html>
