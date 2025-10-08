<div class="property-search-container">
  <form role="search" method="get" class="property-search-form" id="property-search-form">
    <div class="property-search-form__wrapper">
      <div class="property-search-form__field">
        <label for="property-category" class="property-search-form__label">Категорія</label>
        <select 
          id="property-category" 
          name="property_category" 
          class="property-search-form__select"
        >
          <option value="">Всі категорії</option>
          @foreach(get_property_post_types() as $post_type => $label)
            <option value="{{ $post_type }}">{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="property-search-form__field">
        <label for="property-type" class="property-search-form__label">Тип об'єкту</label>
        <select 
          id="property-type" 
          name="property_type" 
          class="property-search-form__select"
        >
          <option value="">Вторинний ринок</option>
          <option value="new">Новобудова</option>
        </select>
      </div>

      <button type="submit" class="property-search-form__submit">
        <span>Шукати</span>
        <x-icon name="search" class="w-5 h-5 stroke-current" />
      </button>
    </div>

    <input type="hidden" name="s" value="">
  </form>
</div>

<style>
.property-search-container {
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
  padding: 1.5rem;
  background-color: #ffffff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.property-search-form {
  width: 100%;
}

.property-search-form__wrapper {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  width: 100%;
}

.property-search-form__field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  width: 100%;
}

.property-search-form__label {
  font-size: 0.875rem;
  font-weight: 400;
  color: #1a1a1a;
}

.property-search-form__select {
  width: 100%;
  padding: 0.875rem 2.5rem 0.875rem 1rem;
  font-size: 1rem;
  color: #1a1a1a;
  background-color: #f5f5f5;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='%231a1a1a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M1 1L6 6L11 1'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 12px;
  border: none;
  appearance: none;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.property-search-form__select:hover {
  background-color: #ebebeb;
}

.property-search-form__select:focus {
  outline: 2px solid #4a68ad;
  outline-offset: 2px;
}

.property-search-form__submit {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  width: 100%;
  padding: 0.875rem 1.5rem;
  font-size: 1rem;
  font-weight: 500;
  color: #ffffff;
  background-color: #4a68ad;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.property-search-form__submit:hover {
  background-color: #3d5691;
}

.property-search-form__submit:active {
  background-color: #334776;
}

.property-search-form__submit svg {
  width: 20px;
  height: 20px;
}

@media (min-width: 768px) {
  .property-search-form__wrapper {
    display: grid;
    grid-template-columns: 220px 220px 160px;
    align-items: end;
  }
}

@media (min-width: 1024px) {
  .property-search-container {
    padding: 28px 80px 40px;
  }

  .property-search-form__submit {
    padding: 0.875rem 2rem;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('property-search-form');
  const categorySelect = document.getElementById('property-category');
  const baseUrl = '{{ home_url("/") }}';
  
  // Зміна action форми при зміні категорії
  categorySelect.addEventListener('change', function() {
    const selectedCategory = this.value;
    
    if (selectedCategory) {
      // Якщо обрано конкретну категорію, перенаправляємо на URL цієї категорії
      form.action = baseUrl + selectedCategory + '/';
    } else {
      // Якщо обрано "Всі категорії", залишаємо базовий URL
      form.action = baseUrl;
    }
  });
  
  // Встановлення початкового action при завантаженні
  const initialCategory = categorySelect.value;
  if (initialCategory) {
    form.action = baseUrl + initialCategory + '/';
  } else {
    form.action = baseUrl;
  }
});
</script>

