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
        <div class="block">
          <label class="block text-sm text-neutral-600 mb-2">Категорія</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="filter-dropdown"
          >
            <span class="text-p1">Квартири</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
        </div>

        <div class="block">
          <label class="block text-sm text-neutral-600 mb-2">Тип об’єкту</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="filter-dropdown"
          >
            <span class="text-p1">Новобудова</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
        </div>

        <div class="block">
          <label class="block text-sm text-neutral-600 mb-2">Кількість кімнат</label>
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="filter-dropdown"
          >
            <span class="text-p1">Всі</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
        </div>

        <div class="block">
          <label class="block text-sm text-neutral-600 mb-2">Загальна площа</label>
          <div class="flex items-center gap-2">
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="filter-dropdown"
          >
            <span class="text-p1">Від:</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>

          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="filter-dropdown"
          >
            <span class="text-p1">до:</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
          </div>
        </div>

        <div class="block">
          <label class="block text-sm text-neutral-600 mb-2">Загальна площа</label>
          <div class="flex items-center gap-2">
          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="filter-dropdown"
          >
            <span class="text-p1">Від:</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>

          <button 
            type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 hover:text-neutral-600 transition-colors bg-white p-2.5"
            id="filter-dropdown"
          >
            <span class="text-p1">до:</span>
            <x-icon name="chevron-down" class="w-4 h-4 text-neutral-400" />
          </button>
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
    
    @include('components.filter-panel')
  </div>
</header>
