class FilterPanel {
  constructor() {
    this.filterToggles = document.querySelectorAll('[data-filter-panel-toggle]');
    this.filterPanel = document.querySelector('[data-filter-panel]');
    this.filterOverlay = document.querySelector('[data-filter-panel-overlay]');
    this.filterClose = document.querySelector('[data-filter-panel-close]');
    this.filterClear = document.querySelector('[data-filter-clear]');
    this.filterApply = document.querySelector('[data-filter-apply]');
    
    this.isOpen = false;
    
    this.init();
  }

  init() {
    if (!this.filterToggles.length || !this.filterPanel || !this.filterOverlay) return;

    this.filterToggles.forEach(toggle => {
      toggle.addEventListener('click', () => this.toggle());
    });
    this.filterClose?.addEventListener('click', () => this.close());
    this.filterOverlay.addEventListener('click', () => this.close());

    this.filterClear?.addEventListener('click', () => this.clearFilters());
    this.filterApply?.addEventListener('click', () => this.applyFilters());

    this.initRoomsCheckboxes();

    const filterToggles = document.querySelectorAll('[data-filter-toggle]');
    filterToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => this.toggleFilterSection(e));
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.close();
      }
    });

    this.filterPanel.addEventListener('transitionend', () => {
      if (this.isOpen) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    });
  }

  toggle() {
    if (this.isOpen) {
      this.close();
    } else {
      this.open();
    }
  }

  open() {
    this.isOpen = true;
    this.filterPanel.classList.remove('translate-y-full');
    this.filterOverlay.classList.remove('opacity-0', 'invisible');
    this.filterToggles.forEach(toggle => {
      toggle.setAttribute('aria-expanded', 'true');
    });
    this.filterOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  close() {
    this.isOpen = false;
    this.filterPanel.classList.add('translate-y-full');
    this.filterOverlay.classList.add('opacity-0', 'invisible');
    this.filterToggles.forEach(toggle => {
      toggle.setAttribute('aria-expanded', 'false');
    });
    this.filterOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  clearFilters() {
    const inputs = this.filterPanel.querySelectorAll('input[type="radio"], input[type="checkbox"], input[type="number"]');
    inputs.forEach(input => {
      if (input.type === 'radio' || input.type === 'checkbox') {
        input.checked = false;
      } else {
        input.value = '';
      }
    });
    
    const roomsClearCheckbox = this.filterPanel.querySelector('.rooms-clear-checkbox');
    if (roomsClearCheckbox) {
      roomsClearCheckbox.checked = true;
    }
    
    const sliders = this.filterPanel.querySelectorAll('[data-slider]');
    sliders.forEach(slider => {
      const fromSlider = slider.querySelector('.from-slider');
      const toSlider = slider.querySelector('.to-slider');
      const fromValue = slider.querySelector('.from-value');
      const toValue = slider.querySelector('.to-value');
      if (fromSlider && toSlider && fromValue && toValue) {
        const min = parseInt(fromSlider.min) || 0;
        const max = parseInt(toSlider.max) || 1000;
        fromSlider.value = min;
        toSlider.value = max;
        fromValue.textContent = min;
        toValue.textContent = max;
        
        const fromPercent = 0;
        const toPercent = 100;
        const gradient = `linear-gradient(to right, #ddd ${fromPercent}%, var(--range-color, #3C589E) ${fromPercent}%, var(--range-color, #3C589E) ${toPercent}%, #ddd ${toPercent}%)`;
        fromSlider.style.background = gradient;
        toSlider.style.background = gradient;
      }
    });
  }

  initRoomsCheckboxes() {
    const roomsContainer = this.filterPanel.querySelector('[data-filter-content="rooms"]');
    if (!roomsContainer) return;

    const clearAllCheckbox = roomsContainer.querySelector('.rooms-clear-checkbox');
    const roomCheckboxes = roomsContainer.querySelectorAll('.rooms-checkbox');

    if (clearAllCheckbox) {
      clearAllCheckbox.addEventListener('change', (e) => {
        if (e.target.checked) {
          roomCheckboxes.forEach(cb => cb.checked = false);
        }
      });
    }

    roomCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        if (clearAllCheckbox) {
          clearAllCheckbox.checked = false;
        }
      });
    });
  }

  applyFilters() {
    const params = new URLSearchParams();
    const currentUrl = new URL(window.location.href);
    
    const radioInputs = this.filterPanel.querySelectorAll('input[type="radio"]:checked');
    radioInputs.forEach(input => {
      if (input.value && input.value !== 'all' && input.value !== '') {
        params.append(input.name, input.value);
      }
    });
    
    const checkboxInputs = this.filterPanel.querySelectorAll('input[type="checkbox"]:checked');
    const rooms = [];
    checkboxInputs.forEach(input => {
      if (input.name === 'rooms' && !input.classList.contains('rooms-clear-checkbox')) {
        rooms.push(input.value);
      }
    });
    if (rooms.length > 0) {
      params.append('rooms', rooms.join(','));
    }
    
    const numberInputs = this.filterPanel.querySelectorAll('input[type="number"], input[type="range"]');
    numberInputs.forEach(input => {
      if (input.name && input.value) {
        const value = parseInt(input.value);
        if (value > 0) {
          params.append(input.name, value);
        }
      }
    });
    
    const existingParams = new URLSearchParams(currentUrl.search);
    const filterKeys = ['type', 'rooms', 'area_from', 'area_to', 'plot_area_from', 'plot_area_to', 'price_from', 'price_to'];
    existingParams.forEach((value, key) => {
      if (!filterKeys.includes(key)) {
        params.append(key, value);
      }
    });
    
    this.close();
    
    const newUrl = `${currentUrl.pathname}?${params.toString()}`;
    window.location.href = newUrl;
  }

  toggleFilterSection(e) {
    const toggle = e.currentTarget;
    const sectionId = toggle.getAttribute('data-filter-toggle');
    const content = document.querySelector(`[data-filter-content="${sectionId}"]`);
    const icon = toggle.querySelector('svg');
  
    if (!content) return;
  
    const isOpen = !content.classList.contains('hidden');
  
    if (isOpen) {
      // Закриття з плавною анімацією
      const height = content.scrollHeight + 'px';
      content.style.height = height;
      content.style.overflow = 'hidden';
      content.style.transition = 'height 220ms ease';
      
      requestAnimationFrame(() => {
        content.style.height = '0px';
      });
  
      content.addEventListener('transitionend', function handler() {
        content.classList.add('hidden');
        content.style.height = '';
        content.style.overflow = '';
        content.style.transition = '';
        content.removeEventListener('transitionend', handler);
      });
  
      icon?.classList.remove('rotate-180');
      toggle.setAttribute('aria-expanded', 'false');
  
    } else {
      // Відкриття з плавною анімацією
      content.classList.remove('hidden');
      content.style.height = '0px';
      content.style.overflow = 'hidden';
      content.style.transition = 'height 220ms ease';
  
      const height = content.scrollHeight + 'px';
      requestAnimationFrame(() => {
        content.style.height = height;
      });
  
      content.addEventListener('transitionend', function handler() {
        content.style.height = 'auto';
        content.style.overflow = '';
        content.style.transition = '';
        content.removeEventListener('transitionend', handler);
      });
  
      icon?.classList.add('rotate-180');
      toggle.setAttribute('aria-expanded', 'true');
    }
  }
}

export default FilterPanel;

