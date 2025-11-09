@php
  $property_types = get_property_post_types();
  $current_type = get_post_type();
  $property_type_label = $current_type ? get_property_type_label($current_type) : __('Unknown Type', 'praktik');
  $property_meta = get_property_meta();
  $property_gallery = get_property_gallery();
  $gallery_count = get_property_gallery_count();
  $archive_url = get_post_type_archive_link(get_post_type());

  $post_id = get_the_ID();
  $favorites = get_user_favorites();
  $is_favorite = in_array((string) $post_id, $favorites);
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
        <div class="property-gallery">
          {{-- Main Swiper --}}
          <div class="swiper property-gallery-main" style="aspect-ratio: 3/2;">
            <div class="swiper-wrapper">
              @if(!empty($property_gallery))
                @foreach($property_gallery as $index => $image)
                  <div class="swiper-slide">
                    <a href="{{ $image['url'] }}" data-fancybox="property-gallery"
                      data-caption="{{ $image['alt'] ?: $image['title'] ?: get_the_title() }}" class="block w-full h-full">
                      <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?: $image['title'] ?: get_the_title() }}"
                        class="w-full h-full object-cover cursor-pointer">
                    </a>
                  </div>
                @endforeach
              @elseif(has_post_thumbnail())
                <div class="swiper-slide">
                  <a href="{{ get_the_post_thumbnail_url($post_id, 'full') }}" data-fancybox="property-gallery"
                    data-caption="{{ get_the_title() }}" class="block w-full h-full">
                    <img src="{{ get_the_post_thumbnail_url($post_id, 'large') }}" alt="{{ get_the_title() }}"
                      class="w-full h-full object-cover cursor-pointer">
                  </a>
                </div>
              @else
                <div class="swiper-slide">
                  <div class="w-full h-full flex items-center justify-center text-gray-500">
                    {{ __('No image available', 'praktik') }}
                  </div>
                </div>
              @endif
            </div>

            {{-- Navigation buttons --}}
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            {{-- Photo counter --}}
            <div
              class="absolute bottom-8px right-8px bg-black bg-opacity-60 text-white px-8px py-4px text-caption rounded-4px font-semibold z-10">
              {{ __('PHOTO', 'praktik') }} <span
                class="property-photo-counter">1/{{ $gallery_count ?: ($property_meta['photos_count'] ?? 1) }}</span>
            </div>

            {{-- Pagination --}}
            <div class="swiper-pagination"></div>
          </div>

          {{-- Thumbnail Navigation Swiper --}}
          @if($gallery_count > 1)
            <div class="swiper property-gallery-thumbs mt-4" style="height: auto;">
              <div class="swiper-wrapper">
                @foreach($property_gallery as $image)
                  <div class="swiper-slide" style="aspect-ratio: 4/3;">
                    <img src="{{ $image['thumbnail'] }}" alt="{{ $image['alt'] ?: $image['title'] }}"
                      class="w-full h-full object-cover">
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      <div class="property-details container px-4 md:px-0">
        {{-- Title and actions --}}
        <div class="md:bg-white md:p-10 mb-6">
          <div class="md:flex md:items-start md:justify-between ">
            <div class="flex-1 mb-5">
              <h1 class="text-gray-900 mb-2 text-2xl">
                {{ get_the_title() ?: __('No Title', 'praktik') }}
              </h1>
              <div class="text-sm text-gray-600 mb-3">
                {{ __('ID', 'praktik') }} {{ $post_id ?: __('Unknown ID', 'praktik') }}
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
              <button class="hidden md:block text-secondary-500 {{ $is_favorite ? 'favorites-post' : '' }}"
                data-post-id="{{ $post_id }}">
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

        <x-agent-contact />
      </div>
    </div>
</article>