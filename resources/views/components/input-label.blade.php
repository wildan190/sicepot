@props(['value'])

<label {{ $attributes->merge(['class' => 'ant-label']) }}>
    {{ $value ?? $slot }}
</label>
