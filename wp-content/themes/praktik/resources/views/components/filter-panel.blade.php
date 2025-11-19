{{-- Filter Panel Overlay --}}
<div 
  class="filter-panel-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-filter-panel-overlay
  aria-hidden="true"
></div>

{{-- Filter Panel --}}
<div 
  class="filter-panel p-5 fixed left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 rounded-t-lg"
  data-filter-panel
  role="dialog"
  aria-modal="true"
  aria-label="Фільтри"
>

  {{-- Filter Header --}}
  <div class="flex items-center justify-between">
    <h2 class="text-p1 font-bold text-neutral-900">Фільтрувати</h2>
    <button 
      type="button"
      class="filter-panel-close"
      aria-label="Закрити фільтри"
      data-filter-panel-close
    >
      <x-icon name="close" class="w-4 h-4" />
    </button>
  </div>

  {{-- Filter Content --}}
  <!-- <div class="filter-panel-content">
    <div class="space-y-0">
      {{-- Object Type Filter --}}
      <div class="filter-section border-b border-neutral-200">
        <button class="w-full flex items-center justify-between py-4 px-4 transition-colors" data-filter-toggle="object-type">
          <span class="text-p1 text-neutral-900">Тип об'єкту</span>
          <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" />
        </button>
        <div class="filter-content px-4 pb-4 space-y-3 hidden" data-filter-content="object-type">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="object_type" value="new" class="w-4 h-4 text-info-600" checked>
            <span class="text-p1 text-neutral-700">Новобудова</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="object_type" value="secondary" class="w-4 h-4 text-info-600">
            <span class="text-p1 text-neutral-700">Вторинний ринок</span>
          </label>
        </div>
      </div>

      {{-- Number of Rooms Filter --}}
      <div class="filter-section border-b border-neutral-200">
        <button class="w-full flex items-center justify-between py-4 px-4 transition-colors" data-filter-toggle="rooms">
          <span class="text-p1 text-neutral-900">Кількість кімнат</span>
          <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" />
        </button>
        <div class="filter-content px-4 pb-4 space-y-3 hidden" data-filter-content="rooms">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="rooms" value="all" class="w-4 h-4 text-info-600" checked>
            <span class="text-p1 text-neutral-700">Всі</span>
          </label>
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" name="room_1" value="1" class="w-4 h-4 text-info-600">
              <span class="text-p1 text-neutral-700">1</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" name="room_2" value="2" class="w-4 h-4 text-info-600">
              <span class="text-p1 text-neutral-700">2</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" name="room_3" value="3" class="w-4 h-4 text-info-600">
              <span class="text-p1 text-neutral-700">3</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" name="room_4" value="4+" class="w-4 h-4 text-info-600">
              <span class="text-p1 text-neutral-700">4+</span>
            </label>
          </div>
        </div>
      </div>

      {{-- Total Area Filter --}}
      <div class="filter-section border-b border-neutral-200">
        <button class="w-full flex items-center justify-between py-4 px-4 transition-colors" data-filter-toggle="area">
          <span class="text-p1 text-neutral-900">Загальна площа</span>
          <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" />
        </button>
        <div class="filter-content px-4 pb-4 hidden" data-filter-content="area">
          <div class="py-4">
            <div class="relative">
              <div class="h-2 bg-neutral-200 rounded-full">
                <div class="h-2 bg-info-600 rounded-full" style="width: 100%"></div>
              </div>
              <div class="flex justify-between mt-2">
                <span class="text-sm text-neutral-600">20 м²</span>
                <span class="text-sm text-neutral-600">100 м²</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Price Filter --}}
      <div class="filter-section border-b border-neutral-200">
        <button class="w-full flex items-center justify-between py-4 px-4 transition-colors" data-filter-toggle="price">
          <span class="text-p1 text-neutral-900">Ціна</span>
          <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" />
        </button>
        <div class="filter-content px-4 pb-4 hidden" data-filter-content="price">
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-sm text-neutral-600 mb-1">Від:</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-500">$</span>
                  <input type="number" name="price_from" value="0" class="w-full pl-8 pr-3 py-2 border border-neutral-300 rounded-lg text-p1" placeholder="0">
                </div>
              </div>
              <div>
                <label class="block text-sm text-neutral-600 mb-1">до:</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-500">$</span>
                  <input type="number" name="price_to" value="150000" class="w-full pl-8 pr-3 py-2 border border-neutral-300 rounded-lg text-p1" placeholder="150 000">
                </div>
              </div>
            </div>
            <div class="py-4">
              <div class="relative">
                <div class="h-2 bg-neutral-200 rounded-full">
                  <div class="h-2 bg-info-600 rounded-full" style="width: 100%"></div>
                </div>
                <div class="flex justify-between mt-2">
                  <span class="text-sm text-neutral-600">$ 0</span>
                  <span class="text-sm text-neutral-600">$ 150 000</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  {{-- Filter Actions --}}
  <!-- <div class="mt-auto border-t px-4 py-4">
    <div class="flex gap-3">
      <button 
        type="button"
        class="flex-1 px-4 py-3 text-p1 text-info-600 border border-info-600 rounded-lg hover:bg-info-50 transition-colors"
        data-filter-clear
      >
        Скинути
      </button>
      <button 
        type="button"
        class="flex-1 px-4 py-3 text-p1 text-white bg-info-600 rounded-lg hover:bg-info-700 transition-colors"
        data-filter-apply
      >
        Застосувати
      </button>
    </div>
  </div> -->
  <button class="btn btn--primary">
    скинути
  </button>
  <button class="btn btn--primary">
    Застосувати
  </button>
</div>
