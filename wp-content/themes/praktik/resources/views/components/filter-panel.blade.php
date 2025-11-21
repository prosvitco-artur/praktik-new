{{-- Filter Panel Overlay --}}
<div class="filter-panel-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-filter-panel-overlay aria-hidden="true"></div>

{{-- Filter Panel --}}
<div
  class="filter-panel p-5 fixed left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 rounded-t-lg text-neutral-950"
  data-filter-panel role="dialog" aria-modal="true" aria-label="{{ __('Filters', 'praktik') }}">

  <div class="flex items-center justify-between mb-5">
    <div class="font-bold">{{ __('Filter', 'praktik') }}</div>
    <button type="button" class="filter-panel-close" aria-label="{{ __('Close filters', 'praktik') }}" data-filter-panel-close>
      <x-icon name="close" class="w-4 h-4" />
    </button>
  </div>

  <div class="filter-panel-content">
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
      
      $area_range = function_exists('get_property_area_range') ? get_property_area_range($current_post_type) : ['min' => 0, 'max' => 1000];
      $plot_area_range = function_exists('get_property_plot_area_range') ? get_property_plot_area_range() : ['min' => 0, 'max' => 1000];
      $price_range = function_exists('get_property_price_range') ? get_property_price_range($current_post_type) : ['min' => 0, 'max' => 1000000];
      $is_house = $current_post_type === 'house';
    @endphp

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="property-type">
        <span class="font-bold">{{ __('Property Type', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="property-type">
        <label class="flex items-center gap-3 py-2 px-3" for="filter-type-all">
          <input type="radio" name="type" id="filter-type-all" value="" class="w-4 h-4" {{ empty($selected_type) ? 'checked' : '' }}>
          <span>{{ __('All', 'praktik') }}</span>
        </label>
        @foreach(\App\get_property_types() as $key => $label)
          <label class="flex items-center gap-3 py-2 px-3" for="filter-type-{{ $key }}">
            <input type="radio" name="type" id="filter-type-{{ $key }}" value="{{ $key }}" class="w-4 h-4" {{ $selected_type === $key ? 'checked' : '' }}>
            <span>{{ $label }}</span>
          </label>
        @endforeach
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
          <x-price-range-slider 
            :min="$area_range['min']" 
            :max="$area_range['max']" 
            :from="$area_from !== '' ? $area_from : $area_range['min']" 
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
          <x-price-range-slider 
            :min="$plot_area_range['min']" 
            :max="$plot_area_range['max']" 
            :from="$plot_area_from !== '' ? $plot_area_from : $plot_area_range['min']" 
            :to="$plot_area_to !== '' ? $plot_area_to : $plot_area_range['max']" 
            name="plot_area" 
            :nameFrom="'plot_area_from'" 
            :nameTo="'plot_area_to'" 
            :text="__('m²', 'praktik')" 
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
        <div class="flex flex-col gap-3 py-2 px-3">
          <div>
            <label for="filter-price-from" class="block text-sm text-neutral-600 mb-1">{{ __('From', 'praktik') }}</label>
            <input type="number" id="filter-price-from" name="price_from" value="{{ $price_from }}" placeholder="0" class="w-full px-3 py-2 border border-neutral-300 rounded" min="0">
          </div>
          <div>
            <label for="filter-price-to" class="block text-sm text-neutral-600 mb-1">{{ __('To', 'praktik') }}</label>
            <input type="number" id="filter-price-to" name="price_to" value="{{ $price_to }}" placeholder="1000000" class="w-full px-3 py-2 border border-neutral-300 rounded" min="0">
          </div>
        </div>
      </div>
    </div>

    <div class="flex gap-5 pt-5">
      <button class="btn btn--primary w-full" data-filter-clear>
        {{ __('Clear', 'praktik') }}
      </button>
      <button class="btn btn--primary w-full" data-filter-apply>
        {{ __('Apply', 'praktik') }}
      </button>
    </div>
  </div>