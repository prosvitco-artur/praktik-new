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
  $total_images = count($property_gallery);
@endphp

<article @php(post_class('min-h-screen'))>
  <div class="hidden md:block container mx-auto py-4">
    <a href="{{ $archive_url }}" class="text-secondary-500 flex items-center gap-2" style="font-weight: 600;">
      <x-icon name="chevron-left" class="w-4 h-4" />
      {{ __('Back to catalog', 'praktik') }}
    </a>
  </div>


  <div class="container mx-auto md:mb-10">
    <div class="property-single">
      <div class="property-gallery relative">
        <div class="absolute top-3 left-3 z-[100] md:hidden">
          <a href="{{ $archive_url }}" class="text-secondary-500 flex items-center p-[10px] bg-white">
            <x-icon name="chevron-left" class="w-6 h-6" />
          </a>
        </div>
        <div class="flex gap-2 ml-4 absolute top-3 right-3 z-[100] md:hidden">
          <button class="p-[10px] bg-white flex items-center justify-center property-share-button text-secondary-500"
            data-share-url="{{ get_permalink() }}" data-share-title="{{ get_the_title() }}"
            aria-label="{{ __('Share', 'praktik') }}">
            <x-icon name="share" class="w-6 h-6" />
          </button>
          <button class="p-[10px] bg-white flex items-center justify-center favorite-button {{ $is_favorite ? 'favorites-post' : '' }}"
            data-post-id="{{ $post_id }}">
            <x-icon name="bookmark" class="w-6 h-6 " />
          </button>
        </div>
        {{-- Main Swiper --}}
        <div class="swiper property-gallery-main" style="aspect-ratio: 3/2;">
          <div class="swiper-wrapper">
            @if(!empty($property_gallery))
              @foreach($property_gallery as $index => $image)
                <div class="swiper-slide">
                  <a href="{{ $image['url'] }}" data-fancybox="property-gallery"
                    data-caption="{{ ($index + 1) . '/' . $total_images }}" class="block w-full h-full">
                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?: $image['title'] ?: get_the_title() }}"
                      class="w-full h-full object-cover cursor-pointer">
                  </a>
                </div>
              @endforeach
            @elseif(has_post_thumbnail())
              <div class="swiper-slide">
                <a href="{{ get_the_post_thumbnail_url($post_id, 'full') }}" data-fancybox="property-gallery"
                  data-caption="1/1" class="block w-full h-full">
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
          <div class="absolute bottom-8px right-1/2 transform translate-x-1/2 bg-white px-8px py-4px text-caption z-10">
            {{ __('PHOTO', 'praktik') }} <span
              class="property-photo-counter">1/{{ $gallery_count ?: ($property_meta['photos_count'] ?? 1) }}</span>
          </div>

          {{-- Pagination --}}
          <div class="swiper-pagination"></div>
        </div>

        {{-- Thumbnail Navigation Swiper --}}
        @if($gallery_count > 1)
          <div class="swiper property-gallery-thumbs mt-4 md:mb-5" style="height: auto;">
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

        @if(get_the_content())
        <div class="hidden md:block p-10 bg-white">
          <div class="text-h4 text-gray-900 mb-4">{{ __('Property Description', 'praktik') }}</div>
          <div class="text-p1 text-gray-700 leading-relaxed">
            @php(the_content())
          </div>
        </div>
        @endif
      </div>
      <div class="container p-4 md:p-0 bg-white md:bg-transparent flex flex-col md:flex-row gap-10 md:gap-0">
        <div class="property-details md:mb-0">
          {{-- Title and actions --}}
          <div class="md:bg-white md:p-10 md:mb-6">
            <div class="md:flex md:items-start md:justify-between ">
              <div class="flex-1 mb-5">
                <h1 class="text-2xl mb-2">
                {!! get_the_title() ?: __('No Title', 'praktik') !!}
                </h1>
                <div class="text-xs text-neutral-600 mb-3">
                  {{ __('ID', 'praktik') }} {{ $post_id ?: __('Unknown ID', 'praktik') }}
                </div>
                @if(!empty($property_meta['price']))
                  <div class="text-[24px] leading-[130%] md:text-[32px] font-bold text-primary-500">
                    {{ format_property_price($property_meta['price']) }}
                  </div>
                @endif
              </div>

              <div class="md:gap-2 md:ml-4 hidden md:flex">
                <button class="w-8 h-8 flex items-center justify-center property-share-button text-secondary-500"
                  data-share-url="{{ get_permalink() }}" data-share-title="{{ get_the_title() }}"
                  aria-label="{{ __('Share', 'praktik') }}">
                  <x-icon name="share" class="w-6 h-6" />
                </button>
                <button class="favorite-button {{ $is_favorite ? 'favorites-post' : '' }}"
                  data-post-id="{{ $post_id }}">
                  <x-icon name="bookmark" class="w-6 h-6 " />
                </button>
              </div>
            </div>

            <div class="space-y-3">
              @if(!empty($property_meta['city']))
                <div class="property-detail">
                  <div class="flex-shrink-0 w-[120px] md:w-[200px] text-neutral-600">{{ __('City', 'praktik') }}</div>
                  <div class="text-neutral-950 flex-1">{{ $property_meta['city'] }}</div>
                </div>
              @endif

              @if(!empty($property_meta['district']))
                <div class="property-detail">
                  <div class="flex-shrink-0 w-[120px] md:w-[200px] text-neutral-600">{{ __('District', 'praktik') }}</div>
                  <div class="text-neutral-950 flex-1">{{ $property_meta['district'] }}</div>
                </div>
              @endif

              @if(!empty($property_meta['street']))
                <div class="property-detail">
                  <div class="flex-shrink-0 w-[120px] md:w-[200px] text-neutral-600">{{ __('Street', 'praktik') }}</div>
                  <div class="text-neutral-950 flex-1">{{ $property_meta['street'] }}</div>
                </div>
              @endif

              @if(!empty($property_meta['rooms']))
                <div class="property-detail">
                  <div class="flex-shrink-0 w-[120px] md:w-[200px] text-neutral-600">{{ __('Number of Rooms', 'praktik') }}</div>
                  <div class="text-neutral-950 flex-1">{{ $property_meta['rooms'] }}</div>
                </div>
              @endif

              @if(!empty($property_meta['area']))
                <div class="property-detail">
                  <div class="flex-shrink-0 w-[120px] md:w-[200px] text-neutral-600">{{ __('Total Area', 'praktik') }}</div>
                  <div class="text-neutral-950 flex-1">{{ $property_meta['area'] }} {{ __('m²', 'praktik') }}</div>
                </div>
              @endif
            </div>
          </div>

          <x-agent-contact />
        </div>
        @if(get_the_content())
        <div class="md:hidden">
          <div class="text-h4 text-gray-900 mb-4">{{ __('Property Description', 'praktik') }}</div>
          <div class="text-p1 text-gray-700 leading-relaxed">
            @php(the_content())
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</article>