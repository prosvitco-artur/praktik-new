<div class="range-slider relative" data-slider>
    <label for="{{ $nameFrom ?? ($name . '_from') }}-slider" class="sr-only">{{ __('From', 'praktik') }} {{ $text ?? '' }}</label>
    <input type="range" id="{{ $nameFrom ?? ($name . '_from') }}-slider" min="{{ $min ?? 0 }}" max="{{ $max ?? 1000 }}" value="{{ $from ?? $min ?? 0 }}" name="{{ $nameFrom ?? ($name . '_from') }}"
      class="from-slider w-full h-1.5 absolute top-5 pointer-events-none" aria-label="{{ __('From', 'praktik') }} {{ $text ?? '' }}" />
    <label for="{{ $nameTo ?? ($name . '_to') }}-slider" class="sr-only">{{ __('To', 'praktik') }} {{ $text ?? '' }}</label>
    <input type="range" id="{{ $nameTo ?? ($name . '_to') }}-slider" min="{{ $min ?? 0 }}" max="{{ $max ?? 1000 }}" value="{{ $to ?? $max ?? 1000 }}" name="{{ $nameTo ?? ($name . '_to') }}"
      class="to-slider w-full h-1.5 absolute top-5 pointer-events-none" aria-label="{{ __('To', 'praktik') }} {{ $text ?? '' }}">
    <span class="absolute left-0 bottom-0"><span class="from-value">{{ $from ?? $min }}</span> {{ $text }}</span>
    <span class="absolute right-0 bottom-0"><span class="to-value" >{{ $to ?? $max }}</span> {{ $text }}</span>
</div>