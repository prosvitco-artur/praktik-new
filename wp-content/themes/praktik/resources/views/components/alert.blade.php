@props(['type' => 'info'])

@php
$classes = match($type) {
    'warning' => 'bg-yellow-50 border border-yellow-200 text-yellow-800',
    'error' => 'bg-red-50 border border-red-200 text-red-800',
    'success' => 'bg-green-50 border border-green-200 text-green-800',
    default => 'bg-blue-50 border border-blue-200 text-blue-800',
};
@endphp

<div {{ $attributes->merge(['class' => 'p-4 rounded-lg ' . $classes]) }}>
  {!! $message ?? $slot !!}
</div>
