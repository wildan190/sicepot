{{--
    ┌─────────────────────────────────────────────────────────────────────────┐
    │  STUNTING DASHBOARD — Entry Point                                        │
    │  File ini hanya sebagai orchestrator. Logika & UI ada di partials/.      │
    │                                                                          │
    │  Struktur:                                                               │
    │    partials/_header.blade.php         → Page header & action buttons     │
    │    partials/_kpi-primary.blade.php    → KPI status pertumbuhan & stunting│
    │    partials/_kpi-secondary.blade.php  → KPI intervensi klinis            │
    │    partials/_charts.blade.php         → Visualisasi charts               │
    │    partials/_table.blade.php          → Data table & pagination          │
    │    partials/_modal-edit.blade.php     → Modal edit balita                │
    │    partials/_modal-add.blade.php      → Modal tambah balita (AI/manual)  │
    │    partials/_modal-detail.blade.php   → Modal detail/view balita         │
    │    partials/_toast.blade.php          → Toast notification               │
    │    partials/_modal-panduan.blade.php  → Modal panduan indikator & upsert │
    │    partials/_modal-whatsapp.blade.php → Modal WhatsApp reminder          │
    │    partials/_scripts.blade.php        → JavaScript & Alpine controller   │
    └─────────────────────────────────────────────────────────────────────────┘
--}}
<x-app-layout>
<div x-data="stuntingDashboard()" x-init="initDashboard()">

    @include('stunting.partials._header')
    @include('stunting.partials._kpi-primary')
    @include('stunting.partials._kpi-secondary')
    @include('stunting.partials._charts')
    @include('stunting.partials._table')
    @include('stunting.partials._modal-edit')
    @include('stunting.partials._modal-add')
    @include('stunting.partials._modal-detail')
    @include('stunting.partials._toast')
    @include('stunting.partials._modal-panduan')
    @include('stunting.partials._modal-whatsapp')
    @include('stunting.partials._scripts')

</div>
</x-app-layout>