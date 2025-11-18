<div class="bg-white p-6 max-w-[800px]">
  <form role="search" method="get" class="property-search-form" id="property-search-form">
    <div class="flex flex-col md:flex-row items-end gap-5 w-full">
      <div class="select-field">
        <label for="property-category">Категорія</label>
        <select id="property-category" name="property_category">
          @foreach(get_property_post_types() as $post_type => $label)
            <option value="{{ $post_type }}">{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="select-field">
        <label for="property-type">{{ __("Property type", "praktik") }}</label>
        <select id="property-type" name="property_type">
          <option value="">Вторинний ринок</option>
          <option value="new">Новобудова</option>
        </select>
      </div>

      <button type="submit" class="btn btn--primary">
        <span>{{ __('Search', 'praktik') }}</span>
        <x-icon name="search" class="w-5 h-5 stroke-current text-color-transparent" />
      </button>
    </div>

    <input type="hidden" name="s" value="">
  </form>
</div>
<script>
  const propertyForm = document.getElementById('property-search-form');

  if (propertyForm) {
    propertyForm.addEventListener('submit', function(e) {
      e.preventDefault();

      const category = document.getElementById('property-category').value;
      const type = document.getElementById('property-type').value;

      const url = `${window.location.origin}/${encodeURIComponent(category)}?type=${encodeURIComponent(type)}`;
      window.location.href = url;
    });
  }
</script>