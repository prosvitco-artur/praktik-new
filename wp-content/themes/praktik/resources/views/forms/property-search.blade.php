<div class="bg-white p-6 md:pb-10 md:px-[80px] max-w-[800px]">
  <form role="search" method="get" class="property-search-form" id="property-search-form">
    <div class="flex flex-col md:flex-row items-end gap-5 w-full">
      <div class="block relative w-full text-neutral-950">
        <label class="text-sm text-neutral-600 mb-2">{{ __('Category', 'praktik') }}</label>
        <button type="button"
          class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-secondary-50 p-2.5 w-full"
          id="category-dropdown" 
          data-dropdown-toggle="category"
          aria-label="{{ __('Select Category', 'praktik') }}">
          <span class="text-p1" id="category-label">{{ __('Select Category', 'praktik') }}</span>
          <x-icon name="chevron" class="w-4 h-4" />
        </button>
        <div class="dropdown-menu bg-secondary-50 min-w-full" data-dropdown-content="category">
          <div class="py-1">
            @php
              $property_post_types = get_property_post_types();
              $non_empty_types = [];
              foreach ($property_post_types as $key => $label) {
                $count = wp_count_posts($key);
                if ($count && $count->publish > 0) {
                  $non_empty_types[$key] = $label;
                }
              }
            @endphp
            @foreach($non_empty_types as $key => $label)
              <button type="button"
                class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left"
                data-value="{{ $key }}" data-label="{{ $label }}">
                {{ $label }}
              </button>
            @endforeach
          </div>
        </div>
      </div>

      <div class="block relative w-full text-neutral-950">
        <label class="text-sm text-neutral-600 mb-2">{{ __('Property Type', 'praktik') }}</label>
        <button type="button"
          class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-secondary-50 p-2.5 w-full"
          id="type-dropdown" 
          data-dropdown-toggle="type"
          aria-label="{{ __('Select Property Type', 'praktik') }}">
          <span class="text-p1" id="type-label">{{ __('Select Property Type', 'praktik') }}</span>
          <x-icon name="chevron" class="w-4 h-4" />
        </button>

        <div class="dropdown-menu bg-secondary-50 min-w-full" data-dropdown-content="type">
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

      <button type="submit" class="btn btn--primary w-full md:w-auto" aria-label="{{ __('Search properties', 'praktik') }}">
        <span>{{ __('Search', 'praktik') }}</span>
        <x-icon name="search" class="w-5 h-5 stroke-current" />
      </button>
    </div>

    <input type="hidden" name="s" value="">
    <input type="hidden" id="property-category" name="category" value="">
    <input type="hidden" id="property-type" name="type" value="">
  </form>
</div>
<script>
  const propertyForm = document.getElementById('property-search-form');

  if (propertyForm) {
    document.addEventListener('dropdownChange', function(e) {
      const { dropdownId, value, label } = e.detail;
      
      if (dropdownId === 'category') {
        document.getElementById('property-category').value = value;
        document.getElementById('category-label').textContent = label;
      } else if (dropdownId === 'type') {
        document.getElementById('property-type').value = value;
        document.getElementById('type-label').textContent = label;
      }
    });

    propertyForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const category = document.getElementById('property-category').value;
      const type = document.getElementById('property-type').value;

      if (!category) {
        return;
      }

      let url = `${window.location.origin}/${encodeURIComponent(category)}`;
      const params = new URLSearchParams();
      
      if (type) {
        params.append('type', type);
      }
      
      const queryString = params.toString();
      if (queryString) {
        url += '?' + queryString;
      }
      
      window.location.href = url;
    });
  }
</script>