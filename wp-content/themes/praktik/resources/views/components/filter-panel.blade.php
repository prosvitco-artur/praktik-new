{{-- Filter Panel Overlay --}}
<div class="filter-panel-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-filter-panel-overlay aria-hidden="true"></div>

{{-- Filter Panel --}}
<div
  class="filter-panel fixed left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 rounded-t-lg text-neutral-950 overflow-y-auto"
  data-filter-panel role="dialog" aria-modal="true" aria-label="{{ __('Filters', 'praktik') }}">

  <div class="p-5 flex items-center justify-between sticky top-0 bg-white z-50">
    <div class="font-bold">{{ __('Filter', 'praktik') }}</div>
    <button type="button" class="filter-panel-close" aria-label="{{ __('Close filters', 'praktik') }}" data-filter-panel-close>
      <x-icon name="close" class="w-4 h-4" />
    </button>
  </div>

  <div class="filter-panel-content px-5">
    @php
      $selected_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
      $selected_rooms = isset($_GET['rooms']) ? explode(',', sanitize_text_field($_GET['rooms'])) : [];
      $selected_rooms = array_map('trim', $selected_rooms);
      $area_from = isset($_GET['area_from']) ? intval($_GET['area_from']) : '';
      $area_to = isset($_GET['area_to']) ? intval($_GET['area_to']) : '';
      $plot_area_from = isset($_GET['plot_area_from']) ? intval($_GET['plot_area_from']) : '';
      $plot_area_to = isset($_GET['plot_area_to']) ? intval($_GET['plot_area_to']) : '';
      $price_from = isset($_GET['price_from']) ? intval($_GET['price_from']) : '';
      $price_to = isset($_GET['price_to']) ? intval($_GET['price_to']) : '';
      
      $current_post_type = get_post_type();
      if (!$current_post_type && is_post_type_archive()) {
        $queried_object = get_queried_object();
        $current_post_type = $queried_object ? $queried_object->name : null;
      }
      
      $area_range = get_property_area_range($current_post_type);
      $plot_area_range = get_property_plot_area_range();
      $price_range = get_property_price_range($current_post_type);
      $is_house = $current_post_type === 'house';
    @endphp

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="property-type">
        <span class="font-bold">
          @if($is_house)
            {{ __('House Type', 'praktik') }}
          @else
            {{ __('Property Type', 'praktik') }}
          @endif
        </span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="property-type">
        <label class="flex items-center gap-3 py-2 px-3" for="filter-type-all">
          <input type="radio" name="type" id="filter-type-all" value="" class="w-4 h-4" {{ empty($selected_type) ? 'checked' : '' }}>
          <span>{{ __('All', 'praktik') }}</span>
        </label>
        @if($is_house)
          @foreach(\App\get_house_types() as $key => $label)
            <label class="flex items-center gap-3 py-2 px-3" for="filter-type-{{ $key }}">
              <input type="radio" name="type" id="filter-type-{{ $key }}" value="{{ $key }}" class="w-4 h-4" {{ $selected_type === $key ? 'checked' : '' }}>
              <span>{{ $label }}</span>
            </label>
          @endforeach
        @else
          @foreach(\App\get_property_types() as $key => $label)
            <label class="flex items-center gap-3 py-2 px-3" for="filter-type-{{ $key }}">
              <input type="radio" name="type" id="filter-type-{{ $key }}" value="{{ $key }}" class="w-4 h-4" {{ $selected_type === $key ? 'checked' : '' }}>
              <span>{{ $label }}</span>
            </label>
          @endforeach
        @endif
      </div>
    </div>

    @if(!$is_house)
    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="rooms">
        <span class="font-bold">{{ __('Number of Rooms', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="rooms">
        <label class="flex items-center gap-3 py-2 px-3 cursor-pointer" for="filter-rooms-all">
          <input type="checkbox" id="filter-rooms-all" class="w-4 h-4 rooms-clear-checkbox" {{ empty($selected_rooms) ? 'checked' : '' }}>
          <span class="font-bold">{{ __('All', 'praktik') }}</span>
        </label>
        @foreach(\App\get_room_counts() as $key => $label)
          <label class="flex items-center gap-3 py-2 px-3 cursor-pointer" for="filter-rooms-{{ $key }}">
            <input type="checkbox" name="rooms" id="filter-rooms-{{ $key }}" value="{{ $key }}" class="w-4 h-4 rooms-checkbox" 
              {{ in_array($key, $selected_rooms) ? 'checked' : '' }}>
            <span>{{ $label }}</span>
          </label>
        @endforeach
      </div>
    </div>
    @endif

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="area">
        <span class="font-bold">{{ __('Total Area', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="area">
        <div class="py-2 px-3">
          <x-range-inputs 
            name="area" 
            :nameFrom="'area_from'" 
            :nameTo="'area_to'" 
            :from="$area_from !== '' ? $area_from : 0" 
            :to="$area_to !== '' ? $area_to : $area_range['max']" 
            :min="$area_range['min']" 
            :max="$area_range['max']" 
          />
          <x-range-slider 
            :min="$area_range['min']" 
            :max="$area_range['max']" 
            :from="$area_from !== '' ? $area_from : 0" 
            :to="$area_to !== '' ? $area_to : $area_range['max']" 
            name="area" 
            :nameFrom="'area_from'" 
            :nameTo="'area_to'" 
            :text="__('m²', 'praktik')" 
          />
        </div>
      </div>
    </div>

    @if($is_house)
    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="plot-area">
        <span class="font-bold">{{ __('Plot Area', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="plot-area">
        <div class="py-2 px-3">
          <x-range-inputs 
            name="plot_area" 
            :nameFrom="'plot_area_from'" 
            :nameTo="'plot_area_to'" 
            :from="$plot_area_from !== '' ? $plot_area_from : 0" 
            :to="$plot_area_to !== '' ? $plot_area_to : $plot_area_range['max']" 
            :min="$plot_area_range['min']" 
            :max="$plot_area_range['max']" 
          />
          <x-range-slider 
            :min="$plot_area_range['min']" 
            :max="$plot_area_range['max']" 
            :from="$plot_area_from !== '' ? $plot_area_from : 0" 
            :to="$plot_area_to !== '' ? $plot_area_to : $plot_area_range['max']" 
            name="plot_area" 
            :nameFrom="'plot_area_from'" 
            :nameTo="'plot_area_to'" 
          />
        </div>
      </div>
    </div>
    @endif

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="price">
        <span class="font-bold">{{ __('Price', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="price">
        <div class="py-2 px-3">
          <x-range-inputs 
            name="price" 
            :nameFrom="'price_from'" 
            :nameTo="'price_to'" 
            :from="$price_from !== '' ? $price_from : 0" 
            :to="$price_to !== '' ? $price_to : $price_range['max']" 
            :min="$price_range['min']" 
            :max="$price_range['max']" 
          />
          <x-range-slider 
            :min="$price_range['min']" 
            :max="$price_range['max']" 
            :from="$price_from !== '' ? $price_from : 0" 
            :to="$price_to !== '' ? $price_to : $price_range['max']" 
            name="price" 
            :nameFrom="'price_from'" 
            :nameTo="'price_to'" 
            :text="__('$', 'praktik')" 
          />
        </div>
      </div>
    </div>

    <div class="flex gap-5 pt-5 sticky bottom-0 bg-white z-50 p-5">
      <button class="btn btn--second w-full font-bold" data-filter-clear>
        {{ __('Clear', 'praktik') }}
      </button>
      <button class="btn btn--primary w-full font-bold" data-filter-apply>
        {{ __('Apply', 'praktik') }}
      </button>
    </div>
  </div>