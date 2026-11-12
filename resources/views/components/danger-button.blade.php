<button {{ $attributes->merge(['type' => 'submit', 'class' => 'ant-btn ant-btn-dangerous']) }}>
    {{ $slot }}
</button>
