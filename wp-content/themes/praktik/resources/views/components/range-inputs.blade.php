@props([
    'name' => 'range',
    'nameFrom' => null,
    'nameTo' => null,
    'from' => 0,
    'to' => null,
    'min' => 0,
    'max' => 1000,
    'inputPrefix' => 'filter',
])

@php
    $nameFrom = $nameFrom ?? ($name . '_from');
    $nameTo = $nameTo ?? ($name . '_to');
    $inputIdFrom = $inputPrefix . '-' . str_replace('_', '-', $nameFrom);
    $inputIdTo = $inputPrefix . '-' . str_replace('_', '-', $nameTo);
    $dataAttrName = str_replace('_', '-', $name);
@endphp

<div class="flex gap-3 mb-4">
    <div class="flex-1">
        <label for="{{ $inputIdFrom }}" class="block text-sm text-neutral-600 mb-1">{{ __('From', 'praktik') }}</label>
        <input type="number" 
            id="{{ $inputIdFrom }}" 
            name="{{ $nameFrom }}" 
            value="{{ $from !== '' ? $from : 0 }}" 
            placeholder="0" 
            class="w-full px-3 py-2 border border-neutral-300 rounded" 
            min="{{ $min }}" 
            max="{{ $max }}"
            data-{{ $dataAttrName }}-input="from">
    </div>
    <div class="flex-1">
        <label for="{{ $inputIdTo }}" class="block text-sm text-neutral-600 mb-1">{{ __('To', 'praktik') }}</label>
        <input type="number" 
            id="{{ $inputIdTo }}" 
            name="{{ $nameTo }}" 
            value="{{ $to !== '' ? $to : $max }}" 
            placeholder="{{ $max }}" 
            class="w-full px-3 py-2 border border-neutral-300 rounded" 
            min="{{ $min }}" 
            max="{{ $max }}"
            data-{{ $dataAttrName }}-input="to">
    </div>
</div>
