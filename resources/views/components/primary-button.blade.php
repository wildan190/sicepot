<button {{ $attributes->merge(['type' => 'submit', 'class' => 'ant-btn ant-btn-primary']) }}>
    {{ $slot }}
</button>
