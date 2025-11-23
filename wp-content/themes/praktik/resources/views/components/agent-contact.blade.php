@php
$authorId = get_post_field('post_author', get_the_ID());
$name = get_the_author_meta('display_name', $authorId) ?: '';
$rawPhone = carbon_get_user_meta($authorId, 'author_phone') ?: '';
$phoneHref = $rawPhone ? 'tel:' . preg_replace('/[^\d\+]/', '', $rawPhone) : '';
$telegramHref = carbon_get_user_meta($authorId, 'author_telegram') ?: '';
$authorPhoto = carbon_get_user_meta($authorId, 'author_photo') ?: '';
$telegramLabel = __('Write to Telegram', 'praktik');
@endphp

<div class="fixed bottom-0 left-0 right-0 bg-white p-5 flex items-center justify-between md:p-10 md:static">
  <div class="flex items-center gap-4">
    <div class="rounded-full h-12 w-12 overflow-hidden">
      @if($authorPhoto)
        <img src="{{ $authorPhoto }}" alt="{{ $name }}" class="w-full h-full object-cover">
      @else
        {!! get_avatar($authorId, 96) !!}
      @endif
    </div>
    <div>
      <p class="font-bold">{{ $name }}</p>
      @if($rawPhone)
        <a href="{{ $phoneHref }}">{{ $rawPhone }}</a>
      @endif
    </div>
  </div>
  <div class="flex items-center gap-2">
    @if (!empty($telegramHref))
    <a href="{{ $telegramHref }}" target="_blank" rel="noopener noreferrer" class="md:flex md:items-center md:gap-2 md:justify-center md:border md:border-secondary-500 md:py-2 md:px-4">
      <x-icon name="telegram-message" class="w-6 h-6 text-secondary-500" />
      <span class="text-secondary-500 hidden md:block">{{ $telegramLabel }}</span>
    </a>
    @endif
  </div>
</div>


