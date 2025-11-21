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
      $price_from = isset($_GET['price_from']) ? intval($_GET['price_from']) : '';
      $price_to = isset($_GET['price_to']) ? intval($_GET['price_to']) : '';
    @endphp

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="property-type">
        <span class="font-bold">{{ __('Property Type', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="property-type">
        <label class="flex items-center gap-3 py-2 px-3">
          <input type="radio" name="type" value="" class="w-4 h-4" {{ empty($selected_type) ? 'checked' : '' }}>
          <span>{{ __('All', 'praktik') }}</span>
        </label>
        @foreach(\App\get_property_types() as $key => $label)
          <label class="flex items-center gap-3 py-2 px-3">
            <input type="radio" name="type" value="{{ $key }}" class="w-4 h-4" {{ $selected_type === $key ? 'checked' : '' }}>
            <span>{{ $label }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="rooms">
        <span class="font-bold">{{ __('Number of Rooms', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="rooms">
        <label class="flex items-center gap-3 py-2 px-3 cursor-pointer">
          <input type="checkbox" class="w-4 h-4 rooms-clear-checkbox" {{ empty($selected_rooms) ? 'checked' : '' }}>
          <span class="font-bold">{{ __('All', 'praktik') }}</span>
        </label>
        @foreach(\App\get_room_counts() as $key => $label)
          <label class="flex items-center gap-3 py-2 px-3 cursor-pointer">
            <input type="checkbox" name="rooms" value="{{ $key }}" class="w-4 h-4 rooms-checkbox" 
              {{ in_array($key, $selected_rooms) ? 'checked' : '' }}>
            <span>{{ $label }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="area">
        <span class="font-bold">{{ __('Total Area', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="area">
        <div class="py-2 px-3">
          <x-price-range-slider 
            :min="0" 
            :max="1000" 
            :from="$area_from ?: 0" 
            :to="$area_to ?: 1000" 
            name="area" 
            :nameFrom="'area_from'" 
            :nameTo="'area_to'" 
            :text="__('m²', 'praktik')" 
          />
        </div>
      </div>
    </div>

    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="price">
        <span class="font-bold">{{ __('Price', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="price">
        <div class="flex flex-col gap-3 py-2 px-3">
          <div>
            <label class="block text-sm text-neutral-600 mb-1">{{ __('From', 'praktik') }}</label>
            <input type="number" name="price_from" value="{{ $price_from }}" placeholder="0" class="w-full px-3 py-2 border border-neutral-300 rounded" min="0">
          </div>
          <div>
            <label class="block text-sm text-neutral-600 mb-1">{{ __('To', 'praktik') }}</label>
            <input type="number" name="price_to" value="{{ $price_to }}" placeholder="1000000" class="w-full px-3 py-2 border border-neutral-300 rounded" min="0">
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