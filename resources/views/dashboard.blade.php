<x-app-layout>
    <x-slot name="header">
        <h2 class="ant-page-title">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="ant-card">
            <div class="ant-card-body text-ant-text">
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
</x-app-layout>
