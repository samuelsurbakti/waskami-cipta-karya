@props([
    'label' => null,
    'type' => 'password',
    'id' => $attributes->get('id') ?? $attributes->get('name'),
    'placeholder' => '',
    'container_class' => 'form-password-toggle form-control-validation', // default tetap mendukung JS template
])

@php
    $name = $attributes->get('name');
    $hasError = $name && $errors->has($name);
    $inputId = $id ?? Str::uuid();
@endphp

<div class="{{ $container_class }} {{ $attributes->get('class') }}">
    @if ($label)
        <label for="{{ $inputId }}" class="form-label">{{ $label }}</label>
    @endif

    <div class="input-group input-group-merge">
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'form-control' . ($hasError ? ' is-invalid' : '')
            ]) }}
        >

        @if ($type === 'password')
            <span class="input-group-text cursor-pointer">
                <i class="icon-base bx bx-hide"></i>
            </span>
        @endif
    </div>

    @if ($hasError)
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
