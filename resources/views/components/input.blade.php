
@props(['disabled' => false, 'label' => false, 'errors' => null])


<?php
$errors = $errors ?? session('errors') ?? new \Illuminate\Support\ViewErrorBag;

$errorClasses = 'border-red-400 focus:border-red-400 ring-1 ring-red-400 focus:ring-red-400';
$defaultClasses = 'border-gray-300 focus:border-pink-400 focus:outline-none focus:ring-0 rounded-md w-full';
$successClasses = 'border-emerald-500 focus:border-emerald-500 ring-1 ring-emerald-500 focus:ring-emerald-500';

$name = $attributes->get('name');
?>
@if ($label)
    <label for="{{ $attributes->get('id') ?? $name }}">{{ $label }}</label>
@endif

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => $defaultClasses . ' ' . ($errors->has($name)
            ? $errorClasses
            : (old($name) ? $successClasses : ''))
    ]) !!}
>

@error($name)
<small class="text-red-600"> {{ $message }}</small>
@enderror

