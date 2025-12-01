@php
  $realtor = get_post();

  $fields = [
    'realtor_name',
    'realtor_phone',
    'realtor_photo',
    'realtor_telegram',
    'realtor_viber',
    'realtor_instagram',
  ];

  $realtor_meta = [];
  foreach ($fields as $field) {
    $realtor_meta[$field] = carbon_get_post_meta($realtor->ID, $field);
  }

  $photo = $realtor_meta['realtor_photo'];
  $phone = $realtor_meta['realtor_phone'];
  $name = $realtor_meta['realtor_name'] ?: get_the_title();
  
  $telegram_username = $realtor_meta['realtor_telegram'];
  $viber_username = $realtor_meta['realtor_viber'];
  $instagram_username = $realtor_meta['realtor_instagram'];
  
  $socials = [];
  if (!empty($telegram_username)) {
    $telegram_url = strpos($telegram_username, 'http') === 0 ? $telegram_username : 'https://t.me/' . ltrim($telegram_username, '@');
    $socials['author-telegram'] = $telegram_url;
  }
  if (!empty($viber_username)) {
    $viber_url = strpos($viber_username, 'http') === 0 ? $viber_username : 'viber://chat?number=' . preg_replace('/[^\d+]/', '', $viber_username);
    $socials['author-viber'] = $viber_url;
  }
  if (!empty($instagram_username)) {
    $instagram_url = strpos($instagram_username, 'http') === 0 ? $instagram_username : 'https://instagram.com/' . ltrim($instagram_username, '@');
    $socials['author-instagram'] = $instagram_url;
  }

  $chatbot_url = carbon_get_theme_option('chatbot_url') ?: null;
@endphp

<article @php(post_class('min-h-screen'))>
  <div class="bg-secondary-500 flex-1 flex justify-center items-center py-10">

    <div class="realtor-content">
      <div class="realtor-data">
        @if ($photo)
          <img class="w-[100px] h-[100px] rounded-full mb-4" src="{{ $photo }}" alt="{{ $name }}" />
        @endif

        <p class="font-bold text-2xl">{{ $name }}</p>

        @if ($phone)
          <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a>
        @endif
      </div>

      @if (array_filter($socials))
        <div class="realtor-socials">
          <p class="mb-5 font-bold">
            {{ __('My social networks', 'praktik') }}
          </p>

          @foreach($socials as $icon => $url)
            @if($url)
              <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="px-4 py-3 bg-secondary-500 w-full flex items-center gap-5">
                <x-icon name="{{ $icon }}" />
                {{ __('My', 'praktik') }} {{ ucfirst(str_replace('author-', '', $icon)) }}
              </a>
            @endif
          @endforeach
        </div>
      @endif

      <div class="realtor-bottom">
        @if(has_social_links())
          <p class="mb-6 font-bold w-[150px] mx-auto text-center">
            {{ __('Subscribe to Praktik Real Estate', "praktik") }}
          </p>

          <div class="social-icons mb-6">
            @foreach(get_social_links() as $network => $url)
              @if($url)
                <a href="{{ $url }}" class="social-icon" aria-label="{{ ucfirst($network) }}" target="_blank"
                  rel="noopener noreferrer">
                  <x-icon name="{{ $network }}" class="w-6 h-6" />
                </a>
              @endif
            @endforeach
          </div>
        @endif
        @if($chatbot_url)
          <a href="{{ $chatbot_url }}" class="chat-bot-button rounded-none justify-center w-full" target="_blank"
            rel="noopener noreferrer">
            <span class="robot-icon">🤖</span>
            <span class="button-text">{{ __('Apartment search chatbot', 'praktik') }}</span>
          </a>
        @endif
      </div>
    </div>
  </div>
</article>

