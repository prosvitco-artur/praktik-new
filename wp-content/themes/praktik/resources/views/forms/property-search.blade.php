<div class="bg-white p-6 md:pb-10 md:px-[80px] max-w-[800px]">
  <form role="search" method="get" class="property-search-form" id="property-search-form">
    @php
      $property_post_types = get_property_post_types();
      $non_empty_types = [];
      foreach ($property_post_types as $key => $label) {
        $count = wp_count_posts($key);
        if ($count && $count->publish > 0) {
          $non_empty_types[$key] = $label;
        }
      }
      $first_category = !empty($non_empty_types) ? array_key_first($non_empty_types) : null;
      $first_category_label = $first_category ? $non_empty_types[$first_category] : __('Select Category', 'praktik');
      
      $apartment_types = \App\Fields\PropertyFieldOptions::get_property_type_options();
      unset($apartment_types['']);
      $first_apartment_type = !empty($apartment_types) ? array_key_first($apartment_types) : null;
      $first_apartment_label = $first_apartment_type ? $apartment_types[$first_apartment_type] : __('Select Property Type', 'praktik');
      
      $house_types = \App\Fields\PropertyFieldOptions::get_house_type_options();
      unset($house_types['']);
      $first_house_type = !empty($house_types) ? array_key_first($house_types) : null;
      $first_house_label = $first_house_type ? $house_types[$first_house_type] : __('Select House Type', 'praktik');
    @endphp
    <div class="flex flex-col md:flex-row items-end gap-5 w-full">
      <div class="block relative w-full text-neutral-950">
        <label class="text-sm text-neutral-600 mb-2">{{ __('Category', 'praktik') }}</label>
        <button type="button"
          class="filter-dropdown flex items-center justify-between gap-2 transition-colors bg-secondary-50 p-2.5 w-full"
          id="category-dropdown" 
          data-dropdown-toggle="category"
          aria-label="{{ __('Select Category', 'praktik') }}">
          <span class="text-p1" id="category-label">{{ $first_category_label }}</span>
          <x-icon name="chevron" class="w-4 h-4" />
        </button>
        <div class="dropdown-menu bg-secondary-50 min-w-full" data-dropdown-content="category">
          <div class="py-1">
            @foreach($non_empty_types as $key => $label)
              <button type="button"
                class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left {{ $key === $first_category ? 'text-secondary-500 font-bold' : '' }}"
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
          <span class="text-p1" id="type-label">{{ $first_apartment_label }}</span>
          <x-icon name="chevron" class="w-4 h-4" />
        </button>
        <div class="dropdown-menu bg-secondary-50 min-w-full" data-dropdown-content="type">
          <div class="py-1" id="type-dropdown-content">
            @foreach($apartment_types as $key => $label)
              <button type="button"
                class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left min-w-full {{ $key === $first_apartment_type ? 'text-secondary-500 font-bold' : '' }}"
                data-value="{{ $key }}" data-label="{{ $label }}" data-special>
                {{ $label }}
              </button>
            @endforeach
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn--primary w-full md:w-auto text-transparent" aria-label="{{ __('Search properties', 'praktik') }}">
        <span class="text-white">{{ __('Search', 'praktik') }}</span>
        <x-icon name="search" class="w-5 h-5" />
      </button>
    </div>

    <input type="hidden" name="s" value="">
    <input type="hidden" id="property-category" name="category" value="{{ $first_category ?? '' }}">
    <input type="hidden" id="property-type" name="type" value="{{ $first_apartment_type ?? '' }}">
  </form>
</div>
<script>
  const propertyForm = document.getElementById('property-search-form');

  if (propertyForm) {
    const typeOptions = {
      apartment: @json($apartment_types),
      house: @json($house_types)
    };

    function updateTypeDropdown(category) {
      const typeContent = document.getElementById('type-dropdown-content');
      const typeLabel = document.getElementById('type-label');
      const typeInput = document.getElementById('property-type');
      
      if (!typeContent || !typeOptions[category]) return;
      
      const options = typeOptions[category];
      const firstKey = Object.keys(options)[0];
      const firstLabel = options[firstKey];
      
      typeContent.innerHTML = '';
      Object.entries(options).forEach(([key, label]) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium text-left min-w-full ${key === firstKey ? 'text-secondary-500 font-bold' : ''}`;
        button.setAttribute('data-value', key);
        button.setAttribute('data-label', label);
        button.setAttribute('data-special', '');
        button.textContent = label;
        typeContent.appendChild(button);
      });
      
      typeLabel.textContent = firstLabel;
      typeInput.value = firstKey;
    }

    document.addEventListener('dropdownChange', function(e) {
      const { dropdownId, value, label } = e.detail;
      
      if (dropdownId === 'category') {
        document.getElementById('property-category').value = value;
        document.getElementById('category-label').textContent = label;
        updateTypeDropdown(value);
      } else if (dropdownId === 'type') {
        document.getElementById('property-type').value = value;
        document.getElementById('type-label').textContent = label;
      }
    });

    @if($first_category)
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        updateTypeDropdown('{{ $first_category }}');
      });
    } else {
      updateTypeDropdown('{{ $first_category }}');
    }
    @endif

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