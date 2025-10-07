@php
  $property_types = get_property_post_types();
  $current_type = get_post_type();
  $property_type_label = $current_type ? get_property_type_label($current_type) : __('Unknown Type', 'praktik');
  $property_meta = get_property_meta();
@endphp

<article @php(post_class('property-single bg-white min-h-screen'))>
  {{-- Main property information --}}
  <section class="property-overview px-4 py-6">
    {{-- Title --}}
    <h1 class="text-h3 font-bold text-gray-900 mb-2">
      {{ get_the_title() ?: __('No Title', 'praktik') }}
    </h1>

    {{-- ID --}}
    <div class="text-sm text-gray-600 mb-4">
      {{ __('ID', 'praktik') }} {{ get_the_ID() ?: __('Unknown ID', 'praktik') }}
    </div>

    {{-- Price --}}
    @if(!empty($property_meta['price']))
      <div class="text-4xl font-bold text-orange-500 mb-6">
        {{ format_property_price($property_meta['price']) }}
      </div>
    @endif

    {{-- Property details --}}
    <div class="grid gap-4">
      @if(!empty($property_meta['city']))
        <div class="property-detail">
          <div class="w-1/2 text-sm text-gray-500 mb-1">{{ __('City', 'praktik') }}</div>
          <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['city'] }}</div>
        </div>
      @endif

      @if(!empty($property_meta['district']))
        <div class="property-detail">
          <div class="w-1/2 text-sm text-gray-500 mb-1">{{ __('District', 'praktik') }}</div>
          <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['district'] }}</div>
        </div>
      @endif

      @if(!empty($property_meta['street']))
        <div class="property-detail">
          <div class="w-1/2 text-sm text-gray-500 mb-1">{{ __('Street', 'praktik') }}</div>
          <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['street'] }}</div>
        </div>
      @endif

      @if(!empty($property_meta['rooms']))
        <div class="property-detail">
          <div class="w-1/2 text-sm text-gray-500 mb-1">{{ __('Number of Rooms', 'praktik') }}</div>
          <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['rooms'] }}</div>
        </div>
      @endif

      @if(!empty($property_meta['area']))
        <div class="property-detail">
          <div class="w-1/2 text-sm text-gray-500 mb-1">{{ __('Total Area', 'praktik') }}</div>
          <div class="w-1/2 text-base font-medium text-gray-900">{{ $property_meta['area'] }} m²</div>
        </div>
      @endif
    </div>
  </section>

  @if(get_the_content())
  <section class="property-description bg-gray-50 px-4 py-6">
    <h2 class="text-h4 font-bold text-gray-900 mb-4">{{ __('Property Description', 'praktik') }}</h2>
    <div class="property-content text-p1 text-gray-700 leading-relaxed">
      @php(the_content())
    </div>
  </section>
  @endif
</article>