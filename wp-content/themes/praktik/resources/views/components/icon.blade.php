@props([
    'name' => '',
    'class' => ''
])

@php
$classes = 'icon icon-' . $name;
if ($class) {
    $classes .= ' ' . $class;
}
@endphp

<svg {{ $attributes->merge(['class' => $classes, 'aria-hidden' => 'true', 'focusable' => 'false']) }}>
    <use href="#icon-{{ $name }}"></use>
</svg>

