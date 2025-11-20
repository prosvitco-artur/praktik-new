<div class="bg-white p-6 md:pb-10 md:px-[80px] max-w-[800px]">
  <form role="search" method="get" class="property-search-form" id="property-search-form">
    <div class="flex flex-col md:flex-row items-end gap-5 w-full">
      <div class="block relative w-full text-neutral-950">
        <label class="text-sm text-neutral-600 mb-2">{{ __('Category', 'praktik') }}</label>
        <button type="button"
          class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-secondary-50 p-2.5 w-full"
          id="category-dropdown" data-dropdown-toggle="category">
          <span class="text-p1">{{ __('Apartments', 'praktik') }}</span>
          <x-icon name="chevron" class="w-4 h-4" />
        </button>
        <div class="dropdown-menu bg-secondary-50" data-dropdown-content="category">
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

      <div class="block relative w-full text-neutral-950">
        <label class="text-sm text-neutral-600 mb-2">{{ __('Category', 'praktik') }}</label>
        <button type="button"
          class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-secondary-50 p-2.5 w-full"
          id="category-dropdown" data-dropdown-toggle="type">
          <span class="text-p1">{{ __('Apartments', 'praktik') }}</span>
          <x-icon name="chevron" class="w-4 h-4" />
        </button>

        <div class="dropdown-menu bg-secondary-50" data-dropdown-content="type">
          <div class="py-2">
            @foreach(\App\get_property_types() as $key => $label)
              <button type="button"
                class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left w-full"
                data-value="{{ $key }}" data-label="{{ $label }}">
                {{ $label }}
              </button>
            @endforeach
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn--primary w-full md:w-auto">
        <span>{{ __('Search', 'praktik') }}</span>
        <x-icon name="search" class="w-5 h-5 stroke-current" />
      </button>
    </div>

    <input type="hidden" name="s" value="">
  </form>
</div>
<script>
  const propertyForm = document.getElementById('property-search-form');

  if (propertyForm) {
    propertyForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const category = document.getElementById('property-category').value;
      const type = document.getElementById('property-type').value;

      const url = `${window.location.origin}/${encodeURIComponent(category)}?type=${encodeURIComponent(type)}`;
      window.location.href = url;
    });
  }
</script>