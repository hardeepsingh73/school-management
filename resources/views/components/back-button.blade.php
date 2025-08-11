@php
    $classes = 'btn btn-outline-secondary btn-sm';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
