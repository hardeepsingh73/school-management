@props(['eid','onClickFunction'])
@php
$classes = 'btn bg-danger-light btn-rounded btn-sm my-0';
$onclick = ($eid || $onClickFunction) ? "onClick=$onClickFunction($eid)" : false;
@endphp
<a {{ $attributes->merge(['class' => $classes]) }} {{ $onclick }}>
    {{ $slot }}
</a>
