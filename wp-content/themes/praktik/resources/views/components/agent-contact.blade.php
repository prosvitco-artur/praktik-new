@php
$post_id = get_the_ID();
$authorId = carbon_get_post_meta($post_id, 'property_realtor');
$name = $authorId ? get_the_title($authorId) : '';
$phone = $authorId ? (carbon_get_post_meta($authorId, 'realtor_phone') ?: '') : '';
$phoneHref = $phone ? 'tel:' . preg_replace('/[^\d\+]/', '', $phone) : '';
$telegram = $authorId ? (carbon_get_post_meta($authorId, 'realtor_telegram') ?: '') : '';
$authorPhoto = $authorId ? (carbon_get_post_meta($authorId, 'realtor_photo') ?: '') : '';
$telegramLabel = __('Write to Telegram', 'praktik');
@endphp

@if($authorId && ($name || $phone || $telegram || $authorPhoto))
  <div class="sticky bottom-0 mt-10 md:mt-0 left-0 right-0 w-screen -mx-4 md:w-auto md:mx-0 bg-white p-5 flex items-center justify-between md:p-10 md:static shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1),0_-2px_4px_-1px_rgba(0,0,0,0.06)] md:shadow-none">
    <div class="flex items-center gap-4">
      <div class="rounded-full h-12 w-12 overflow-hidden">
        @if($authorPhoto)
          <img src="{{ $authorPhoto }}" alt="{{ $name }}" class="w-full h-full object-cover">
        @elseif($authorId)
          {!! get_avatar($authorId, 96) !!}
        @endif
      </div>
      <div>
        @if($name)
          <p class="font-bold">{{ $name }}</p>
        @endif
        @if($phone)
          <a href="{{ $phoneHref }}">{{ $phone }}</a>
        @endif
      </div>
    </div>
    <div class="flex items-center gap-2">
      @if (!empty($telegram))
        <a href="https://t.me/{{ $telegram }}" target="_blank" rel="noopener noreferrer" class="md:flex md:items-center md:gap-2 md:justify-center md:border md:border-secondary-500 md:py-2 md:px-4">
          <x-icon name="telegram-message" class="w-6 h-6 text-secondary-500" />
          <span class="text-secondary-500 hidden md:block">{{ $telegramLabel }}</span>
        </a>
      @endif
    </div>
  </div>
@endif
