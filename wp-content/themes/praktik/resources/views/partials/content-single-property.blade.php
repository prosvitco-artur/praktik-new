@php
  $property_types = get_property_post_types();
  $current_type = get_post_type();
  $property_type_label = $current_type ? get_property_type_label($current_type) : __('Unknown Type', 'praktik');
  $property_meta = get_property_meta();
  $property_gallery = get_property_gallery();
  $gallery_count = get_property_gallery_count();
@endphp

<article @php(post_class('property-single bg-white min-h-screen'))>
  {{-- Back to catalog link --}}
  <div class="container mx-auto py-4">
    <a href="javascript:history.back()" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
      {{ __('Back to catalog', 'praktik') }}
    </a>
  </div>

  {{-- Main content area --}}
  <div class="container mx-auto pb-6">
    {{-- Desktop layout: two columns --}}
    <div class="lg:grid lg:grid-cols-2 lg:gap-5">
      
      {{-- Left column: Image gallery --}}
      <div class="property-gallery mb-6 lg:mb-0">
        {{-- Main image --}}
        <div class="relative bg-gray-200 overflow-hidden mb-4" style="aspect-ratio: 3/2;">
          @if(!empty($property_gallery))
            <img src="{{ $property_gallery[0]['url'] }}" 
                 alt="{{ $property_gallery[0]['alt'] ?: get_the_title() }}" 
                 class="w-full h-full object-cover">
          @elseif(has_post_thumbnail())
            <img src="{{ get_the_post_thumbnail_url(get_the_ID(), 'large') }}" 
                 alt="{{ get_the_title() }}" 
                 class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-gray-500">
              {{ __('No image available', 'praktik') }}
            </div>
          @endif
          
          {{-- Navigation arrows --}}
          @if($gallery_count > 1)
            <button class="absolute left-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white bg-opacity-80 flex items-center justify-center hover:bg-opacity-100 transition-all">
              <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white bg-opacity-80 flex items-center justify-center hover:bg-opacity-100 transition-all">
              <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
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
                <div class="relative bg-gray-200 overflow-hidden cursor-pointer {{ $index === 0 ? 'ring-2 ring-yellow-400' : '' }}" style="aspect-ratio: 4/3;">
                  <img src="{{ $image['thumbnail'] }}" 
                       alt="{{ $image['alt'] ?: $image['title'] }}" 
                       class="w-full h-full object-cover">
                </div>
              @endif
            @endforeach
          </div>
        @endif
      </div>
      
      {{-- Right column: Property details --}}
      <div class="property-details">
        {{-- Title and actions --}}
        <div class="flex items-start justify-between mb-4">
          <div class="flex-1">
            <h1 class="text-h2 font-bold text-gray-900 mb-2">
              {{ get_the_title() ?: __('No Title', 'praktik') }}
            </h1>
            <div class="text-sm text-gray-600 mb-4">
              {{ __('ID', 'praktik') }} {{ get_the_ID() ?: __('Unknown ID', 'praktik') }}
            </div>
            @if(!empty($property_meta['price']))
              <div class="text-4xl font-bold text-orange-500 mb-6">
                {{ format_property_price($property_meta['price']) }}
              </div>
            @endif
          </div>
          
          {{-- Action buttons --}}
          <div class="flex gap-2 ml-4">
            <button class="w-10 h-10 bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
              <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
              </svg>
            </button>
            <button class="w-10 h-10 bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
              <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
              </svg>
            </button>
          </div>
        </div>

        {{-- Property details --}}
        <div class="space-y-3 mb-6">
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

        {{-- Agent information --}}
        <div class="bg-gray-50 p-4 flex items-center gap-4">
          <div class="w-12 h-12 bg-gray-300 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </div>
          <div class="flex-1">
            <div class="font-medium text-gray-900">{{ __('Agent Name', 'praktik') }}</div>
            <div class="text-sm text-gray-600">{{ __('Phone', 'praktik') }}: +38 (067) 123-45-67</div>
          </div>
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 flex items-center gap-2 text-sm transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            {{ __('Write to Telegram', 'praktik') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Property description - full width --}}
  @if(get_the_content())
    <section class="property-description bg-gray-50 py-6">
      <div class="container mx-auto px-4">
        <h2 class="text-h4 font-bold text-gray-900 mb-4">{{ __('Property Description', 'praktik') }}</h2>
        <div class="property-content text-p1 text-gray-700 leading-relaxed">
          @php(the_content())
        </div>
      </div>
    </section>
  @endif
</article>