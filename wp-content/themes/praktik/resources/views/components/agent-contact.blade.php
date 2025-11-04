@php
$authorId = get_post_field('post_author', get_the_ID());
$name = get_the_author_meta('display_name', $authorId) ?: '';
$rawPhone = get_user_meta($authorId, 'phone', true) ?: get_user_meta($authorId, 'user_phone', true) ?: '';
$phoneHref = $rawPhone ? 'tel:' . preg_replace('/[^\d\+]/', '', $rawPhone) : '';
$telegramHref = get_user_meta($authorId, 'telegram', true);
$telegramLabel = __('Write to Telegram', 'praktik');
@endphp

<div class="fixed bottom-0 left-0 right-0 bg-white p-5 flex items-center justify-between md:p-10 md:static">
  <div class="flex items-center gap-4">
    <div class="rounded-full h-12 w-12 overflow-hidden">
      {!! get_avatar($authorId, 96) !!}
    </div>
    <div>
      <p class="font-bold">{{ $name }}</p>
      @if($rawPhone)
        <a href="{{ $phoneHref }}">{{ $rawPhone }}</a>
      @endif
    </div>
  </div>
  @if (!empty($telegramHref))
  <a href="{{ $telegramHref }}" class="md:flex md:items-center md:gap-2 md:justify-center md:border md:border-secondary-500 md:py-2 md:px-4">
    <x-icon name="telegram-message" class="w-6 h-6 text-secondary-500" />
    <span class="text-secondary-500 hidden md:block">{{ $telegramLabel }}</span>
  </a>
  @endif
</div>


