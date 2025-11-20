@php
  $author = get_user_by('slug', get_query_var('author_name'));

  $fields = [
    'author_phone',
    'author_photo',
    'author_telegram',
    'author_viber',
    'author_instagram',
  ];

  $author_meta = [];
  foreach ($fields as $field) {
    $author_meta[$field] = carbon_get_user_meta($author->ID, $field);
  }

  $photo     = $author_meta['author_photo'];
  $phone     = $author_meta['author_phone'];

  $socials = [
    'author-telegram'  => $author_meta['author_telegram'],
    'author-viber'     => $author_meta['author_viber'],
    'author-instagram' => $author_meta['author_instagram'],
  ];
@endphp

<div class="bg-secondary-500 flex-1 flex justify-center items-center">
  <div class="author-content">

    <div class="author-data">
      @if ($photo)
        <img class="w-[100px] h-[100px] rounded-full mb-4" src="{{ $photo }}" />
      @endif

      @php $fullName = trim($author->first_name . ' ' . $author->last_name); @endphp
      @if ($fullName)
        <p class="font-bold text-2xl">{{ $fullName }}</p>
      @endif

      @if ($phone)
        <a href="tel:{{ $phone }}">{{ $phone }}</a>
      @endif
    </div>

    @if (array_filter($socials))
      <div class="author-socials">
        <p class="mb-5 font-bold">
          {{ __('My social networks', 'praktik') }}
        </p>

        @foreach($socials as $icon => $url)
          @if($url)
            <a href="{{ $url }}" class="px-4 py-3 bg-secondary-500 w-full flex items-center gap-5">
              <x-icon name="{{ $icon }}" />
              {{ __('My', 'praktik') }} {{ ucfirst(str_replace('author-', '', $icon)) }}
            </a>
          @endif
        @endforeach
      </div>
    @endif

    <div class="author-bottom">
      @if(has_social_links())
        <p class="mb-6 font-bold w-[150px] mx-auto text-center">
          {{ __('Subscribe to Praktik Real Estate', "praktik") }}
        </p>

        <div class="social-icons mb-6">
          @foreach(get_social_links() as $network => $url)
            @if($url)
              <a 
                href="{{ $url }}" 
                class="social-icon" 
                aria-label="{{ ucfirst($network) }}" 
                target="_blank"
                rel="noopener noreferrer"
              >
                <x-icon name="{{ $network }}" class="w-6 h-6" />
              </a>
            @endif
          @endforeach
        </div>
      @endif

      <button class="chat-bot-button rounded-none justify-center w-full">
        <span class="robot-icon">🤖</span>
        <span class="button-text">{{ __('Apartment search chatbot', 'praktik') }}</span>
      </button>
    </div>

  </div>
</div>
