@props(['active'])

@php
$classes = ($active ?? false) ? 'ant-menu-item ant-menu-item-selected' : 'ant-menu-item';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
