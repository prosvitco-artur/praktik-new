<div class="range-slider relative" data-slider>
    <input type="range" min="{{ $min ?? 0 }}" max="{{ $max ?? 1000 }}" value="{{ $from ?? $min ?? 0 }}" name="{{ $name }}"
      class="from-slider w-full h-1.5 absolute top-5 pointer-events-none" />
    <input type="range" min="{{ $min ?? 0 }}" max="{{ $max ?? 1000 }}" value="{{ $to ?? $max ?? 1000 }}" name="{{ $name }}"
      class="to-slider w-full h-1.5 absolute top-5 pointer-events-none">
    <span class="absolute left-0 bottom-0"><span class="from-value">{{ $from ?? $min }}</span> {{ $text }}</span>
    <span class="absolute right-0 bottom-0"><span class="to-value" >{{ $to ?? $max }}</span> {{ $text }}</span>
</div>