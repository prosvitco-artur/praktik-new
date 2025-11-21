<header class="py-5">
  <div class="container px-5">
    @php
      $current_post_type = get_post_type();
      $property_types = get_property_post_types();
      $current_type_label = isset($property_types[$current_post_type])
        ? $property_types[$current_post_type]
        : ($current_post_type ? ucfirst($current_post_type) : __('All Properties', 'praktik'));
      $archive_link = $current_post_type && get_post_type_archive_link($current_post_type)
        ? get_post_type_archive_link($current_post_type)
        : home_url('/');


      $count = $GLOBALS['wp_query']->found_posts;
    @endphp
    <form role="search" method="get" class="lg:flex gap-[8px]" action="{{ $archive_link }}">
      <div class="w-full relative">
        <label for="property-search-input" class="sr-only">{{ __('Search', 'praktik') }}</label>
        <input type="search" id="property-search-input" placeholder="{{ __('Search', 'praktik') }}" value="{{ $_GET['search'] ?? '' }}"
          name="search" class="w-full h-[44px] pr-4 pl-[44px] border-0 focus:outline-none" aria-label="{{ __('Search', 'praktik') }}">
        <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" aria-label="{{ __('Search', 'praktik') }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>
      </div>

      <button type="submit" class="btn btn--primary hidden lg:flex">
        <span>{{ __('Search', 'praktik') }}</span>
        <x-icon name="search" class="w-5 h-5 stroke-current text-transparent" />
      </button>
    </form>

    <div class="filter-buttons mt-4">
      <div class="flex items-center justify-between gap-[71px] flex-wrap text-sm">
        <div class="relative">
          <label class="hidden md:block text-neutral-600 mb-2">{{ __('Category', 'praktik') }}</label>
          <button type="button"
            class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-white px-4 py-3"
            id="category-dropdown" data-dropdown-toggle="category">
            <span>{{ $current_type_label }}</span>
            <x-icon name="chevron" class="w-4 h-4" />
          </button>
          <div class="dropdown-menu" data-dropdown-content="category">
            <div class="py-1">
              @foreach(get_property_post_types() as $key => $label)
                <a href="{{ get_post_type_archive_link($key) }}"
                  class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium">
                  {{ $label }}
                </a>
              @endforeach
            </div>
          </div>
        </div>

        <div class="hidden md:block relative">
          <label class="block text-sm text-neutral-600 mb-2">{{ __('Property Type', 'praktik') }}</label>
          @php
            $property_types = \App\get_property_types();
            $selected_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
            $type_label = $selected_type && isset($property_types[$selected_type]) 
              ? $property_types[$selected_type] 
              : __('All', 'praktik');
          @endphp
          <button type="button"
            class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-white p-2.5 w-full"
            id="type-dropdown" data-dropdown-toggle="type">
            <span id="type-label">{{ $type_label }}</span>
            <x-icon name="chevron" class="w-4 h-4" />
          </button>
          <div class="dropdown-menu" data-dropdown-content="type">
            <div class="py-2">
              <button type="button"
                class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left w-full {{ empty($selected_type) ? 'text-secondary-500 font-bold' : '' }}"
                data-value="" data-label="{{ __('All', 'praktik') }}">
                {{ __('All', 'praktik') }}
              </button>
              @foreach($property_types as $key => $label)
                <button type="button"
                  class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left w-full {{ $selected_type === $key ? 'text-secondary-500 font-bold' : '' }}"
                  data-value="{{ $key }}" data-label="{{ $label }}">
                  {{ $label }}
                </button>
              @endforeach
            </div>
          </div>
        </div>

        <div class="hidden md:block relative">
          <label class="block text-sm text-neutral-600 mb-2">{{ __('Number of Rooms', 'praktik') }}</label>
          @php
            $selected_rooms = isset($_GET['rooms']) ? explode(',', sanitize_text_field($_GET['rooms'])) : [];
            $selected_rooms = array_map('trim', $selected_rooms);
            $rooms_label = empty($selected_rooms) ? __('All', 'praktik') : implode(', ', array_map(function($key) use ($selected_rooms) {
              $room_counts = \App\get_room_counts();
              return $room_counts[$key] ?? $key;
            }, $selected_rooms));
          @endphp
          <button type="button"
            class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-white p-2.5 w-full"
            id="rooms-dropdown" data-dropdown-toggle="rooms">
            <span id="rooms-label">{{ $rooms_label }}</span>
            <x-icon name="chevron" class="w-4 h-4" />
          </button>

          {{-- Dropdown Menu --}}
          <div class="dropdown-menu" data-dropdown-content="rooms">
            <div class="py-2">
              <label class="flex items-center gap-3 py-2 px-3 font-bold text-secondary-500 cursor-pointer" data-rooms-clear>
                <input type="checkbox" class="w-4 h-4 rooms-clear-checkbox" {{ empty($selected_rooms) ? 'checked' : '' }}>
                <span>{{ __('All', 'praktik') }}</span>
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
        </div>

        <div class="hidden md:block">
          <label class="mb-2">{{ __('Total Area', 'praktik') }}</label>
          <div class="flex gap-2">
            <div class="relative">
              <label for="area-from-input" class="sr-only">{{ __('Total Area From', 'praktik') }}</label>
              @php
                $area_from = isset($_GET['area_from']) ? intval($_GET['area_from']) : '';
              @endphp
              <button type="button"
                class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-white p-2.5 w-full"
                id="area-from-dropdown" data-dropdown-toggle="area_from">
                <span class="text-neutral-500">{{ __('From: ', 'praktik') }}</span>
                <span><input type="number" id="area-from-input" name="area_from" value="{{ $area_from }}" class="w-10 border-0 focus:outline-none" aria-label="{{ __('Total Area From', 'praktik') }}" />{{ __('m²', 'praktik') }}</span>
                <x-icon name="chevron" class="w-4 h-4" />
              </button>

              <div class="dropdown-menu" data-dropdown-content="area_from">
                <div class="p-4 space-y-3 flex flex-col items-start">
                  @foreach (\App\get_area_ranges() as $value)
                    <button type="button" class="w-full text-left {{ $area_from == $value ? 'text-secondary-500 font-bold' : '' }}" 
                      data-value="{{ $value }}" data-label="{{ $value }}">{{ $value }}</button>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="relative">
              <label for="area-to-input" class="sr-only">{{ __('Total Area To', 'praktik') }}</label>
              @php
                $area_to = isset($_GET['area_to']) ? intval($_GET['area_to']) : '';
              @endphp
              <button type="button"
                class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-white p-2.5 w-full"
                id="area-to-dropdown" data-dropdown-toggle="area_to">
                <span class="text-neutral-500">{{ __('To: ', 'praktik') }}</span>
                <span>
                  <input type="number" id="area-to-input" name="area_to" value="{{ $area_to }}" class="w-10 border-0 focus:outline-none" aria-label="{{ __('Total Area To', 'praktik') }}" />
                  {{ __('m²', 'praktik') }}
                </span>
                <x-icon name="chevron" class="w-4 h-4" />
              </button>

              <div class="dropdown-menu" data-dropdown-content="area_to">
                <div class="p-4 space-y-3 flex flex-col items-start">
                  @foreach (\App\get_area_ranges() as $value)
                    <button type="button" class="dropdown-item w-full text-left text-neutral-700 {{ $area_to == $value ? 'text-secondary-500 font-bold' : '' }}" 
                      data-value="{{ $value }}" data-label="{{ $value }}">
                      {{ $value }}
                    </button>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="hidden md:block">
          <label class="mb-2">{{ __('Price', 'praktik') }}</label>
          <div class="flex gap-2">
            <div class="bg-white px-4 py-3">
              <label for="price-from-input" class="sr-only">{{ __('Price From', 'praktik') }}</label>
              <span class="text-neutral-500">{{ __('From: ', 'praktik') }}</span>
              <input type="number" id="price-from-input" name="price-from" class="w-10" aria-label="{{ __('Price From', 'praktik') }}" />
            </div>
            <div class="bg-white px-4 py-3">
              <label for="price-to-input" class="sr-only">{{ __('Price To', 'praktik') }}</label>
              <span class="text-neutral-500">{{ __('To: ', 'praktik') }}</span>
              <input type="number" id="price-to-input" name="price-to" class="w-10" aria-label="{{ __('Price To', 'praktik') }}" />
            </div>
          </div>
        </div>

        <div class="flex items-center gap-4 md:hidden">
          <button type="button"
            class="flex items-center gap-2 text-info-600 hover:text-info-700 transition-colors relative bg-white p-2.5"
            id="filter-button" data-filter-panel-toggle aria-expanded="false">
            <x-icon name="filter" class="w-6 h-6" />
            <div class="absolute top-2.5 right-2.5 w-2 h-2 bg-warning-500 rounded-full border border-white"></div>
          </button>
          <button
            class="flex items-center gap-2 text-info-600 hover:text-info-700 transition-colors relative bg-white p-2.5"
            id="sort-button">
            <x-icon name="sort" class="w-6 h-6" />
          </button>
        </div>
      </div>
    </div>
    <div class="sort-buttons mt-4 flex items-center justify-between">
      <div>
        {!! 
          sprintf(__('Found <strong>%s properties</strong>', 'praktik'), $count)
        !!}
      </div>
      <div class="relative hidden md:flex items-center gap-2">
        <label class="block text-sm text-neutral-600">{{ __('Sort by', 'praktik') }}</label>
        <button type="button"
          class="filter-dropdown flex items-center gap-2 text-neutral-800 transition-colors bg-white p-2.5"
          id="sort-dropdown" data-dropdown-toggle="sort">
          <span>{{ get_sort_label(get_current_sort()) }}</span>
          <x-icon name="chevron" class="w-4 h-4" />
        </button>

        <div class="dropdown-menu" data-dropdown-content="sort">
          <div class="py-2">
            @foreach(get_sort_options() as $key => $label)
              @php
                $sort_url = add_query_arg(['sort' => $key], remove_query_arg(['paged', 'sort']));
              @endphp
              <a href="{{ $sort_url }}"
                class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium {{ get_current_sort() === $key ? 'text-secondary-500 font-bold' : '' }}">
                {{ $label }}
              </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    @include('components.filter-panel')
  </div>
</header>