@php
  $property_types = get_property_post_types();
  $current_type = get_post_type();
  $property_type_label = $current_type ? get_property_type_label($current_type) : __('Unknown Type', 'praktik');
  $property_meta = get_property_meta();
  $property_gallery = get_property_gallery();
  $gallery_count = get_property_gallery_count();
  $archive_url = get_post_type_archive_link(get_post_type());
@endphp

<article @php(post_class('min-h-screen'))>
  <div class="container mx-auto py-4">
    <a href="{{ $archive_url }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-2">
      <x-icon name="chevron-left" class="w-4 h-4 stroke-current" />
      {{ __('Back to catalog', 'praktik') }}
    </a>
  </div>

  <div class="container mx-auto mb-10">
    <div class="property-single">
      <div class="property-gallery mb-6 lg:mb-0">
        <div class="relative bg-gray-200 overflow-hidden mb-4 md:mb-0" style="aspect-ratio: 3/2;">
          @if(!empty($property_gallery))
            <img src="{{ $property_gallery[0]['url'] }}" alt="{{ $property_gallery[0]['alt'] ?: get_the_title() }}"
              class="w-full h-full object-cover">
          @elseif(has_post_thumbnail())
            <img src="{{ get_the_post_thumbnail_url(get_the_ID(), 'large') }}" alt="{{ get_the_title() }}"
              class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-gray-500">
              {{ __('No image available', 'praktik') }}
            </div>
          @endif

          {{-- Navigation arrows --}}
          @if($gallery_count > 1)
            <button
              class="absolute left-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white bg-opacity-80 flex items-center justify-center hover:bg-opacity-100 transition-all">
              <x-icon name="chevron-left" class="w-4 h-4 text-gray-700 stroke-current" />
            </button>
            <button
              class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white bg-opacity-80 flex items-center justify-center hover:bg-opacity-100 transition-all">
              <x-icon name="chevron-right" class="w-4 h-4 text-gray-700 stroke-current" />
            </button>
          @endif

          {{-- Photo counter --}}
          <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white px-2 py-1 text-sm">
            {{ __('PHOTO', 'praktik') }} 1/{{ $gallery_count ?: ($property_meta['photos_count'] ?? 1) }}
          </div>
        </div>

        {{-- Thumbnail gallery --}}
        @if($gallery_count > 1)
          <div class="grid grid-cols-4 gap-2">
            @foreach($property_gallery as $index => $image)
              @if($index < 3)
                <div
                  class="relative bg-gray-200 overflow-hidden cursor-pointer {{ $index === 0 ? 'ring-2 ring-yellow-400' : '' }}"
                  style="aspect-ratio: 4/3;">
                  <img src="{{ $image['thumbnail'] }}" alt="{{ $image['alt'] ?: $image['title'] }}"
                    class="w-full h-full object-cover">
                </div>
              @endif
            @endforeach
          </div>
        @endif
      </div>

      {{-- Right column: Property details --}}
      <div class="property-details ">
        {{-- Title and actions --}}
        <div class="md:bg-white md:p-10 mb-6">
          <div class="md:flex md:items-start md:justify-between ">
            <div class="flex-1 mb-5">
              <h1 class="text-gray-900 mb-2 text-2xl">
                {{ get_the_title() ?: __('No Title', 'praktik') }}
              </h1>
              <div class="text-sm text-gray-600 mb-3">
                {{ __('ID', 'praktik') }} {{ get_the_ID() ?: __('Unknown ID', 'praktik') }}
              </div>
              @if(!empty($property_meta['price']))
                <div class="text-[24px] leading-[130%] md:text-[32px] font-bold text-secondary-500">
                  {{ format_property_price($property_meta['price']) }}
                </div>
              @endif
            </div>

            <div class="hidden md:flex gap-2 ml-4">
              <button class="w-8 h-8 flex items-center justify-center">
                <x-icon name="share" />
              </button>
              <button class="w-8 h-8 flex items-center justify-center">
                <x-icon name="bookmark" />
              </button>
            </div>
          </div>

          <div class="space-y-3">
            @if(!empty($property_meta['city']))
              <div class="property-detail">
                <div class="w-1/2 text-sm text-gray-500">{{ __('City', 'praktik') }}</div>
                <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['city'] }}</div>
              </div>
            @endif

            @if(!empty($property_meta['district']))
              <div class="property-detail">
                <div class="w-1/2 text-sm text-gray-500">{{ __('District', 'praktik') }}</div>
                <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['district'] }}</div>
              </div>
            @endif

            @if(!empty($property_meta['street']))
              <div class="property-detail">
                <div class="w-1/2 text-sm text-gray-500">{{ __('Street', 'praktik') }}</div>
                <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['street'] }}</div>
              </div>
            @endif

            @if(!empty($property_meta['rooms']))
              <div class="property-detail">
                <div class="w-1/2 text-sm text-gray-500">{{ __('Number of Rooms', 'praktik') }}</div>
                <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['rooms'] }}</div>
              </div>
            @endif

            @if(!empty($property_meta['area']))
              <div class="property-detail">
                <div class="w-1/2 text-sm text-gray-500">{{ __('Total Area', 'praktik') }}</div>
                <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['area'] }} m²</div>
              </div>
            @endif
          </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 bg-white p-5 flex items-center justify-between md:p-10 md:static">
          <div class="flex items-center gap-4">
            <!-- get attachment with ID 2835 -->
            <div class="rounded-full h-12 w-12 overflow-hidden">
              {!! wp_get_attachment_image(2835) !!}
            </div>
            <div>
              <p class="font-bold">Ім'я Фамілія</p>
              <a href="tel:+38 (067) 123-45-67">+38 (067) 123-45-67</a>
            </div>
          </div>
          <a href="/" class="md:flex md:items-center md:gap-2 md:justify-center md:border md:border-secondary-500 md:py-2 md:px-4">
            <x-icon name="telegram-message" class="w-6 h-6 text-secondary-500" />
            <span class="text-secondary-500 hidden md:block">{{ __('Write to Telegram', 'praktik') }}</span>
          </a>
        </div>
      </div>
      @if(get_the_content())
      <div class="property-description md:bg-white md:p-10">
        <div class="text-h4 text-gray-900 mb-4">{{ __('Property Description', 'praktik') }}</div>
        <div class="property-content text-p1 text-gray-700 leading-relaxed">
          @php(the_content())
        </div>
      </div>
      @endif
    </div>
  </div>
</article>