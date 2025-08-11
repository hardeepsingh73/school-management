@php
$classes = 'btn bg-primary btn-rounded btn-sm my-0';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
