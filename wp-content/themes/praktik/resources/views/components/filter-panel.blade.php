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
    <div class="filter-section border-b border-neutral-200">
      <button class="w-full flex items-center justify-between py-2 px-3 mb-2" data-filter-toggle="object-type">
        <span class="font-bold">{{ __('Property Type', 'praktik') }}</span>
        <x-icon name="chevron" class="w-5 h-5" />
      </button>
      <div class="filter-content hidden mb-2" data-filter-content="object-type">
        @foreach(\App\get_property_types() as $key => $label)
          <label class="flex items-center gap-3 py-2 px-3">
            <input type="radio" name="object_type" value="{{ $key }}" class="w-4 h-4">
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
        <label class="flex items-center gap-3 py-2 px-3">
          <input type="radio" name="rooms" value="{{ $key }}" class="w-4 h-4">
          <span>{{ __('All', 'praktik') }}</span>
        </label>
        @foreach(\App\get_room_counts() as $key => $label)
          <label class="flex items-center gap-3 py-2 px-3">
            <input type="checkbox" name="rooms" value="{{ $key }}" class="w-4 h-4">
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
        <x-price-range-slider :min="0" :max="1000" :from="100" :to="900" :name="'area'" :text="__('m²', 'praktik')" />
      </div>
    </div>

    <div class="flex gap pt-5 gap-5">
      <button class="btn btn--primary w-full" data-filter-clear>
        {{ __('Clear', 'praktik') }}
      </button>
      <button class="btn btn--primary w-full" data-filter-apply>
        {{ __('Apply', 'praktik') }}
      </button>
    </div>
  </div>