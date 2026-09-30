<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-950 selection:bg-indigo-500 selection:text-white min-h-screen relative overflow-x-hidden">
        <!-- Ambient Background Glow & Decorative Gradients -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/25 rounded-full blur-3xl animate-pulse" style="animation-duration: 7s;"></div>
            <div class="absolute top-1/3 -right-32 w-80 h-80 bg-teal-500/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 9s;"></div>
            <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-pink-600/15 rounded-full blur-3xl animate-pulse" style="animation-duration: 8s;"></div>
            
            <!-- Subtle Grid Pattern -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:32px_32px] opacity-40"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </div>
    </body>
</html>
