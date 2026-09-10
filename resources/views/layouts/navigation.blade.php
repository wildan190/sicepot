<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo & Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/logo.png') }}" alt="SICEPOT Logo"
                            class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform">
                        <div class="flex flex-col">
                            <span
                                class="font-extrabold text-lg text-slate-800 tracking-tight leading-none group-hover:text-indigo-600 transition-colors">SICEPOT</span>
                            <span class="text-[10px] text-slate-500 font-semibold tracking-wide">Sistem Cepat Post &
                                Tracking</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('tb.*')">
                        {{ __('Dashboard TBC') }}
                    </x-nav-link>
                    <x-nav-link :href="route('anc.dashboard')" :active="request()->routeIs('anc.*')">
                        {{ __('Dashboard Ibu Hamil') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings & Realtime WebSocket Status -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- PieSocket Realtime Live Indicator & Audio/Notification Activator -->
                <div class="flex items-center gap-2" id="piesocket-nav-status">
                    <button type="button"
                        onclick="window.pieSocketClient && window.pieSocketClient.enableAlertNotifications()"
                        id="btn-enable-hp-alert"
                        title="Klik untuk mengaktifkan Sirine Audio & Notifikasi Browser di HP/Desktop"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl transition-all shadow-xs cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-rose-600 animate-pulse" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span class="hidden md:inline" id="txt-enable-hp-alert">Aktifkan Alert di HP</span>
                    </button>

                    <!-- WebSocket Connection Status Badge -->
                    <div id="piesocket-badge"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 text-[11px] font-semibold rounded-xl">
                        <span id="piesocket-dot" class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span id="piesocket-label" class="hidden lg:inline">Menghubungkan...</span>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger & Mobile Quick Status -->
            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <button type="button"
                    onclick="window.pieSocketClient && window.pieSocketClient.enableAlertNotifications()"
                    class="inline-flex items-center gap-1 px-2 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold rounded-lg cursor-pointer">
                    <svg class="w-3 h-3 text-rose-600 animate-pulse" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <span>Alert HP</span>
                </button>
                <div id="piesocket-badge-mobile" class="w-2.5 h-2.5 rounded-full bg-slate-400" title="Status WebSocket">
                </div>
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('tb.*')">
                {{ __('Dashboard TBC') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('anc.dashboard')" :active="request()->routeIs('anc.*')">
                {{ __('ANC') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>