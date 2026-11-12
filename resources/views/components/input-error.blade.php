@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1 space-y-0.5 text-[12px] text-danger-500']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
