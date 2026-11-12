@php
    use Illuminate\Support\Facades\Auth;

    $isAdmin = Auth::user()?->hasRole('Admin');

    $menu = [
        [
            'label' => 'Dashboard TBC',
            'href' => route('dashboard'),
            'active' => request()->routeIs('dashboard') || request()->routeIs('tb.*'),
            'icon' => 'M4 5a1 1 0 011-1h5v16H5a1 1 0 01-1-1V5zm10-1h5a1 1 0 011 1v6h-6V4zm0 9h6v6a1 1 0 01-1 1h-5v-7z',
        ],
        [
            'label' => 'Dashboard Ibu Hamil',
            'href' => route('anc.dashboard'),
            'active' => request()->routeIs('anc.*'),
            'icon' => 'M12 20.5s-7.5-4.7-7.5-10A4.5 4.5 0 0112 7.6a4.5 4.5 0 017.5 2.9c0 5.3-7.5 10-7.5 10z',
        ],
        [
            'label' => 'Dashboard Stunting',
            'href' => route('stunting.dashboard'),
            'active' => request()->routeIs('stunting.*'),
            'icon' => 'M4 19h16M7 19v-5m5 5V8m5 11v-8',
        ],
        [
            'label' => 'AI Dashboard',
            'href' => route('ai.dashboard'),
            'active' => request()->routeIs('ai.*'),
            'icon' => 'M9 3h6v3a3 3 0 003 3v6a3 3 0 00-3 3v3H9v-3a3 3 0 00-3-3V9a3 3 0 003-3V3z',
            'tag' => 'AI',
        ],
    ];

    if ($isAdmin) {
        $menu[] = [
            'label' => 'Pengaturan',
            'href' => route('settings.index'),
            'active' => request()->routeIs('settings.*'),
            'icon' => 'M10.3 3h3.4l.4 2.3 2 1.2 2.2-.8 1.7 3-1.7 1.6v2.3l1.7 1.6-1.7 3-2.2-.8-2 1.2-.4 2.3h-3.4l-.4-2.3-2-1.2-2.2.8-1.7-3 1.7-1.6V10l-1.7-1.6 1.7-3 2.2.8 2-1.2.4-2.3zM12 14.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z',
        ];
    }
@endphp

<!-- ── Sider (Desktop) ──────────────────────────────────────────────── -->
<aside class="hidden lg:flex fixed inset-y-0 left-0 z-40 w-56 flex-col border-r border-ant-split bg-white">
    <div class="flex h-14 items-center gap-2.5 border-b border-ant-split px-4">
        <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="h-8 w-8 rounded-ant object-cover">
        <div class="flex flex-col leading-tight">
            <span class="text-[15px] font-semibold text-ant-title">SICEPOT</span>
            <span class="text-[11px] text-ant-secondary">Post &amp; Tracking</span>
        </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3">
        @foreach ($menu as $item)
            <a href="{{ $item['href'] }}" class="ant-menu-item {{ $item['active'] ? 'ant-menu-item-selected' : '' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $item['icon'] }}"></path>
                </svg>
                <span class="flex-1 truncate">{{ $item['label'] }}</span>
                @isset($item['tag'])
                    <span class="ant-tag ant-tag-blue">{{ $item['tag'] }}</span>
                @endisset
            </a>
        @endforeach
    </nav>

    <div class="border-t border-ant-split p-3">
        <p class="text-[11px] leading-relaxed text-ant-secondary">
            Sistem Cepat Post &amp; Tracking<br>Kohort KIA, TBC &amp; Stunting
        </p>
    </div>
</aside>

<!-- ── Sider (Mobile Drawer) ────────────────────────────────────────── -->
<div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
    <div class="absolute inset-0 bg-black/45" @click="sidebarOpen = false"></div>

    <aside x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        class="absolute inset-y-0 left-0 flex w-64 flex-col bg-white">
        <div class="flex h-14 items-center justify-between border-b border-ant-split px-4">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="SICEPOT" class="h-8 w-8 rounded-ant object-cover">
                <span class="text-[15px] font-semibold text-ant-title">SICEPOT</span>
            </div>
            <button type="button" @click="sidebarOpen = false" class="ant-btn ant-btn-text ant-btn-sm" aria-label="Tutup menu">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-3">
            @foreach ($menu as $item)
                <a href="{{ $item['href'] }}" class="ant-menu-item {{ $item['active'] ? 'ant-menu-item-selected' : '' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="{{ $item['icon'] }}"></path>
                    </svg>
                    <span class="flex-1 truncate">{{ $item['label'] }}</span>
                    @isset($item['tag'])
                        <span class="ant-tag ant-tag-blue">{{ $item['tag'] }}</span>
                    @endisset
                </a>
            @endforeach
        </nav>
    </aside>
</div>
