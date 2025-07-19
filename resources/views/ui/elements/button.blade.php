@props([
    'type' => 'submit',
    'class' => '',
])

<button type="{{ $type }}" class="btn {{$class}}">
    {{ $slot }}
</button>
