<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary bg-emerald-400 hover:bg-emerald-500 active:bg-emerald-600 w-full']) }}>
    {{ $slot }}
</button>
