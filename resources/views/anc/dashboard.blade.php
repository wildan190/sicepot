{{--
    ┌─────────────────────────────────────────────────────────────────────────┐
    │  ANC DASHBOARD — Entry Point                                             │
    │  File ini hanya sebagai orchestrator. Logika & UI ada di partials/.      │
    │                                                                          │
    │  Struktur:                                                               │
    │    partials/_header.blade.php          → Page header & action buttons    │
    │    partials/_alerts.blade.php          → Peringatan H-1 & H-30          │
    │    partials/_filter.blade.php          → Filter bar                      │
    │    partials/_kpi-cards.blade.php       → KPI summary cards               │
    │    partials/_charts.blade.php          → Charts & Skor Poedji Rochjati   │
    │    partials/_table.blade.php           → Data table & pagination         │
    │    partials/_modal-import.blade.php    → Modal import Excel/CSV          │
    │    partials/_modal-edit.blade.php      → Modal edit ibu hamil            │
    │    partials/_modal-add.blade.php       → Modal tambah ibu hamil          │
    │    partials/_modal-whatsapp.blade.php  → Modal WhatsApp sender           │
    │    partials/_modal-duplikasi.blade.php → Modal skrining duplikasi        │
    │    partials/_modal-timeline.blade.php  → Modal cohort timeline           │
    │    partials/_modal-ai.blade.php        → Modal AI Triage (Gemini)        │
    │    partials/_modal-detail.blade.php    → Modal detail ibu hamil          │
    │    partials/_modal-clear.blade.php     → Modal konfirmasi clear data     │
    │    partials/_modal-kelahiran.blade.php → Modal catat kelahiran realtime  │
    │    partials/_scripts.blade.php         → JavaScript & Alpine controller  │
    └─────────────────────────────────────────────────────────────────────────┘
--}}
<x-app-layout>
<div x-data="ancDashboard()" x-init="initDashboard()">

    @include('anc.partials._header')
    @include('anc.partials._alerts')
    @include('anc.partials._filter')
    @include('anc.partials._kpi-cards')
    @include('anc.partials._charts')
    @include('anc.partials._table')
    @include('anc.partials._modal-import')
    @include('anc.partials._modal-edit')
    @include('anc.partials._modal-add')
    @include('anc.partials._modal-whatsapp')
    @include('anc.partials._modal-duplikasi')
    @include('anc.partials._modal-timeline')
    @include('anc.partials._modal-ai')
    @include('anc.partials._modal-detail')
    @include('anc.partials._modal-clear')
    @include('anc.partials._modal-kelahiran')
    @include('anc.partials._scripts')

</div>
</x-app-layout>