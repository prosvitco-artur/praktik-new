<header class="py-5">
  <div class="container">
    <form role="search" method="get" class="lg:flex gap-[8px]" action="{{ get_post_type_archive_link(get_post_type()) }}">
      <div class="w-full relative">
        <input
          type="search"
          placeholder="{{ __('Search', 'praktik') }}"
          value="{{ $_GET['search'] ?? '' }}"
          name="search"
          class="w-full h-[44px] pr-4 pl-[44px] border-0 focus:outline-none"
        >
        <button class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>
      </div>

      <button type="submit" class="btn btn--primary hidden lg:flex">
        <span>{{ __('Search', 'praktik') }}</span>
        <x-icon name="search" class="w-5 h-5 stroke-current" />
      </button>
    </form>

    <!-- Filter Buttons -->
    <div class="filter-buttons mt-4">
      <div class="flex items-center justify-between">
        <div class="block relative">
          <label class="hidden md:block text-sm text-neutral-600 mb-2">{{ __('Category', 'praktik') }}</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5 w-full"
            id="category-dropdown"
            data-dropdown-toggle="category"
          >
            <span class="text-p1">{{ get_property_post_types()[get_post_type()] }}</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
          <div class="dropdown-menu" data-dropdown-content="category">
            <div class="py-1">
              @foreach(get_property_post_types() as $key => $label)
                <a 
                  href="{{ get_post_type_archive_link($key) }}"
                  class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium"
                >
                  {{ $label }}
                </a>
              @endforeach
            </div>
          </div>
        </div>

        <div class="hidden md:block relative">
          <label class="block text-sm text-neutral-600 mb-2">{{ __('Property Type', 'praktik') }}</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="type-dropdown"
            data-dropdown-toggle="type"
          >
            <span class="text-p1">{{ __('New Building', 'praktik') }}</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
          
          {{-- Dropdown Menu --}}
          <div class="dropdown-menu" data-dropdown-content="type">
            <div class="py-2">
              @foreach(\App\get_property_types() as $key => $label)
                <button 
                  type="button"
                  class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left"
                  data-value="{{ $key }}"
                  data-label="{{ $label }}"
                >
                  {{ $label }}
                </button>
              @endforeach
            </div>
          </div>
        </div>

        <div class="hidden md:block relative">
          <label class="block text-sm text-neutral-600 mb-2">{{ __('Number of Rooms', 'praktik') }}</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="rooms-dropdown"
            data-dropdown-toggle="rooms"
          >
            <span class="text-p1">{{ __('All', 'praktik') }}</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
          
          {{-- Dropdown Menu --}}
          <div class="dropdown-menu" data-dropdown-content="rooms">
            <div class="py-2">
              @foreach(\App\get_room_counts() as $key => $label)
                <button 
                  type="button"
                  class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left"
                  data-value="{{ $key }}"
                  data-label="{{ $label }}"
                >
                  {{ $label }}
                </button>
              @endforeach
            </div>
          </div>
        </div>

        <div class="hidden md:block relative">
          <label class="block text-sm text-neutral-600 mb-2">{{ __('Total Area', 'praktik') }}</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5 w-full"
            id="area-dropdown"
            data-dropdown-toggle="area"
          >
            <span class="text-p1">{{ __('Select Area', 'praktik') }}</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400 ml-auto" />
          </button>
          
          {{-- Dropdown Menu --}}
          <div class="dropdown-menu" data-dropdown-content="area">
            <div class="p-4 space-y-3">
              <div>
                <label class="block text-xs text-neutral-600 mb-1">{{ __('From (m²):', 'praktik') }}</label>
                <input 
                  type="number" 
                  name="area_from" 
                  placeholder="20" 
                  class="w-full px-3 py-2 border border-neutral-300 text-sm focus:outline-none focus:ring-2 focus:ring-info-500"
                  min="0"
                  step="1"
                >
              </div>
              <div>
                <label class="block text-xs text-neutral-600 mb-1">{{ __('To (m²):', 'praktik') }}</label>
                <input 
                  type="number" 
                  name="area_to" 
                  placeholder="100" 
                  class="w-full px-3 py-2 border border-neutral-300 text-sm focus:outline-none focus:ring-2 focus:ring-info-500"
                  min="0"
                  step="1"
                >
              </div>
            </div>
          </div>
        </div>

        <div class="hidden md:block relative">
          <label class="block text-sm text-neutral-600 mb-2">{{ __('Price', 'praktik') }}</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5 w-full"
            id="price-dropdown"
            data-dropdown-toggle="price"
          >
            <span class="text-p1">{{ __('Select Price', 'praktik') }}</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400 ml-auto" />
          </button>
          
          {{-- Dropdown Menu --}}
          <div class="dropdown-menu" data-dropdown-content="price">
            <div class="p-4 space-y-3">
              <div>
                <label class="block text-xs text-neutral-600 mb-1">{{ __('From ($):', 'praktik') }}</label>
                <input 
                  type="number" 
                  name="price_from" 
                  placeholder="0" 
                  class="w-full px-3 py-2 border border-neutral-300 text-sm focus:outline-none focus:ring-2 focus:ring-info-500"
                  min="0"
                  step="1000"
                >
              </div>
              <div>
                <label class="block text-xs text-neutral-600 mb-1">{{ __('To ($):', 'praktik') }}</label>
                <input 
                  type="number" 
                  name="price_to" 
                  placeholder="150000" 
                  class="w-full px-3 py-2 border border-neutral-300 text-sm focus:outline-none focus:ring-2 focus:ring-info-500"
                  min="0"
                  step="1000"
                >
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex items-center gap-4 md:hidden">
           <button 
             type="button"
             class="flex items-center gap-2 text-info-600 hover:text-info-700 transition-colors relative bg-white p-2.5" 
             id="filter-button"
             data-filter-panel-toggle
             aria-expanded="false"
           >
             <x-icon name="filter" class="w-6 h-6" />
             <!-- <span class="text-p1">Фільтр</span> -->
             <div class="absolute top-2.5 right-2.5 w-2 h-2 bg-warning-500 rounded-full border border-white"></div>
           </button>
          <button class="flex items-center gap-2 text-info-600 hover:text-info-700 transition-colors relative bg-white p-2.5" id="sort-button">
            <x-icon name="sort" class="w-6 h-6" />
            <!-- <span class="text-p1">Фільтр</span> -->
          </button>
        </div>
      </div>
    </div>
    <div class="sort-buttons mt-4 hidden md:block">
      <div class="relative inline-block">
        <label class="block text-sm text-neutral-600 mb-2">{{ __('Sort by', 'praktik') }}</label>
        <button 
          type="button"
          class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
          id="sort-dropdown"
          data-dropdown-toggle="sort"
        >
          <span class="text-p1">{{ get_sort_label(get_current_sort()) }}</span>
          <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
        </button>
        
        <div class="dropdown-menu" data-dropdown-content="sort">
          <div class="py-2">
            @foreach(get_sort_options() as $key => $label)
              @php
                $sort_url = add_query_arg(['sort' => $key], remove_query_arg(['paged', 'sort']));
              @endphp
              <a 
                href="{{ $sort_url }}"
                class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium {{ get_current_sort() === $key ? 'text-secondary-500 font-bold' : '' }}"
              >
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
