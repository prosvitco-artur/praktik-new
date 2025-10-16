{{-- Filter Panel Overlay --}}
<div 
  class="filter-panel-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-filter-panel-overlay
  aria-hidden="true"
></div>

{{-- Filter Panel --}}
<div 
  class="filter-panel fixed left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 overflow-y-auto rounded-t-16px"
  data-filter-panel
  role="dialog"
  aria-modal="true"
  aria-label="Фільтри"
>
  {{-- Top Indicator (drag handle) --}}
  <div class="flex justify-center pt-8px pb-4px">
    <div class="w-32px h-4px bg-neutral-300 rounded-full"></div>
  </div>

  {{-- Filter Header --}}
  <div class="flex items-center justify-between px-16px py-12px border-b border-neutral-200">
    <h2 class="text-p1 font-bold text-neutral-900">Фільтри</h2>
    <button 
      type="button"
      class="filter-panel-close p-8px -mr-8px"
      aria-label="Закрити фільтри"
      data-filter-panel-close
    >
      <x-icon name="close" class="w-24px h-24px text-neutral-900 stroke-current" />
    </button>
  </div>

  {{-- Filter Content --}}
  <div class="filter-panel-content py-16px px-16px">
    <div class="space-y-6">
      {{-- Property Type Filter --}}
      <div class="filter-section">
        <h3 class="text-p1 font-semibold text-neutral-900 mb-3">Тип нерухомості</h3>
        <div class="space-y-2">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="property_type" value="apartment" class="w-4 h-4 text-primary-600">
            <span class="text-p1 text-neutral-700">Квартири</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="property_type" value="house" class="w-4 h-4 text-primary-600">
            <span class="text-p1 text-neutral-700">Будинки</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="radio" name="property_type" value="commercial" class="w-4 h-4 text-primary-600">
            <span class="text-p1 text-neutral-700">Комерційна нерухомість</span>
          </label>
        </div>
      </div>

      {{-- Price Range Filter --}}
      <div class="filter-section">
        <h3 class="text-p1 font-semibold text-neutral-900 mb-3">Ціна</h3>
        <div class="space-y-3">
          <div class="flex gap-2">
            <input 
              type="number" 
              placeholder="Від" 
              class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg text-p1"
            >
            <input 
              type="number" 
              placeholder="До" 
              class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg text-p1"
            >
          </div>
        </div>
      </div>

      {{-- Area Filter --}}
      <div class="filter-section">
        <h3 class="text-p1 font-semibold text-neutral-900 mb-3">Площа</h3>
        <div class="space-y-2">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="area" value="0-50" class="w-4 h-4 text-primary-600">
            <span class="text-p1 text-neutral-700">До 50 м²</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="area" value="50-100" class="w-4 h-4 text-primary-600">
            <span class="text-p1 text-neutral-700">50-100 м²</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="area" value="100+" class="w-4 h-4 text-primary-600">
            <span class="text-p1 text-neutral-700">Понад 100 м²</span>
          </label>
        </div>
      </div>
    </div>
  </div>

  {{-- Filter Actions --}}
  <div class="mt-auto border-t border-neutral-200 px-16px py-16px">
    <div class="flex gap-3">
      <button 
        type="button"
        class="flex-1 px-4 py-3 text-p1 text-neutral-600 border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors"
        data-filter-clear
      >
        Очистити
      </button>
      <button 
        type="button"
        class="flex-1 px-4 py-3 text-p1 text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors"
        data-filter-apply
      >
        Застосувати
      </button>
    </div>
  </div>
</div>
