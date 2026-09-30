{{--
    ┌─────────────────────────────────────────────────────────────────────────┐
    │  TB DASHBOARD — Entry Point                                              │
    │  File ini hanya sebagai orchestrator. Logika & UI ada di partials/.      │
    │                                                                          │
    │  Struktur:                                                               │
    │    partials/_header.blade.php        → Page header & action buttons      │
    │    partials/_filter.blade.php        → Filter bar (kabupaten, dll)       │
    │    partials/_kpi-cards.blade.php     → KPI summary cards                 │
    │    partials/_charts.blade.php        → Visualisasi charts & peta         │
    │    partials/_table.blade.php         → Data table & pagination           │
    │    partials/_modal-clear.blade.php   → Modal konfirmasi clear data       │
    │    partials/_modal-import.blade.php  → Modal import Excel/CSV            │
    │    partials/_modal-edit.blade.php    → Modal edit pasien                 │
    │    partials/_modal-add.blade.php     → Modal tambah pasien (AI/manual)   │
    │    partials/_modal-whatsapp.blade.php→ Modal WhatsApp reminder           │
    │    partials/_modal-duplikasi.blade.php → Modal skrining duplikasi        │
    │    partials/_modal-timeline.blade.php  → Modal rekam jejak pengobatan    │
    │    partials/_modal-ai.blade.php      → Modal AI Triage (Gemini)          │
    │    partials/_modal-detail.blade.php  → Modal detail/view pasien          │
    │    partials/_toast.blade.php         → Toast notification                │
    │    partials/_scripts.blade.php       → JavaScript & Alpine controller    │
    └─────────────────────────────────────────────────────────────────────────┘
--}}
<x-app-layout>
<div x-data="tbDashboard()" x-init="initDashboard()">

    @include('tb.partials._header')
    @include('tb.partials._filter')
    @include('tb.partials._kpi-cards')
    @include('tb.partials._charts')
    @include('tb.partials._table')
    @include('tb.partials._modal-clear')
    @include('tb.partials._modal-import')
    @include('tb.partials._modal-edit')
    @include('tb.partials._modal-add')
    @include('tb.partials._modal-whatsapp')
    @include('tb.partials._modal-duplikasi')
    @include('tb.partials._modal-timeline')
    @include('tb.partials._modal-ai')
    @include('tb.partials._modal-detail')
    @include('tb.partials._toast')
    @include('tb.partials._scripts')

</div>
</x-app-layout>