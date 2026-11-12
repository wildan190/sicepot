@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-[13px] font-medium text-success-600']) }}>
        {{ $status }}
    </div>
@endif
