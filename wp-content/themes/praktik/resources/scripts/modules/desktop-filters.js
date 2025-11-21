class DesktopFilters {
  constructor() {
    this.filters = {};
    this.roomCounts = {};
    this.roomsTimeout = null;
    this.init();
  }

  init() {
    this.initRoomCounts();
    this.initRoomsCheckboxes();
    this.initPropertyType();
    this.initAreaFilters();
    this.initPlotAreaFilters();
    this.initPriceFilters();

    document.addEventListener('dropdownChange', (e) => {
      const { dropdownId, value, label } = e.detail;
      this.filters[dropdownId] = value;
      
      if (dropdownId === 'type') {
        const typeLabel = document.getElementById('type-label');
        if (typeLabel) {
          typeLabel.textContent = label || 'All';
        }
        
        if (!value || value === '') {
          delete this.filters.type;
        }
        
        this.applyFilters();
      } else if (dropdownId === 'area_from' || dropdownId === 'area_to') {
        const inputId = dropdownId === 'area_from' ? 'area-from-input' : 'area-to-input';
        const input = document.getElementById(inputId);
        if (input) {
          input.value = value || '';
          if (value) {
            this.filters[dropdownId] = value;
          } else {
            delete this.filters[dropdownId];
          }
        }
        this.applyFilters();
      } else if (dropdownId === 'plot_area_from' || dropdownId === 'plot_area_to') {
        const inputId = dropdownId === 'plot_area_from' ? 'plot-area-from-input' : 'plot-area-to-input';
        const input = document.getElementById(inputId);
        if (input) {
          input.value = value || '';
          if (value) {
            this.filters[dropdownId] = value;
          } else {
            delete this.filters[dropdownId];
          }
        }
        this.applyFilters();
      }
    });

    document.addEventListener('change', (e) => {
      if (e.target.matches('#area-from-input, #area-to-input, #plot-area-from-input, #plot-area-to-input, #price-from-input, #price-to-input')) {
        const name = e.target.name;
        if (e.target.value && parseInt(e.target.value) > 0) {
          this.filters[name] = e.target.value;
        } else {
          delete this.filters[name];
        }
      }
    });

    document.addEventListener('blur', (e) => {
      if (e.target.matches('#area-from-input, #area-to-input, #price-from-input, #price-to-input')) {
        clearTimeout(this.applyTimeout);
        this.applyTimeout = setTimeout(() => {
          const name = e.target.name;
          if (e.target.value && parseInt(e.target.value) > 0) {
            this.filters[name] = e.target.value;
          } else {
            delete this.filters[name];
          }
          this.applyFilters();
        }, 500);
      }
    }, true);

    document.addEventListener('keypress', (e) => {
      if (e.target.matches('#area-from-input, #area-to-input, #price-from-input, #price-to-input') && e.key === 'Enter') {
        const name = e.target.name;
        if (e.target.value && parseInt(e.target.value) > 0) {
          this.filters[name] = e.target.value;
        } else {
          delete this.filters[name];
        }
        this.applyFilters();
      }
    });
  }

  initRoomCounts() {
    const checkboxes = document.querySelectorAll('.rooms-checkbox');
    checkboxes.forEach(checkbox => {
      this.roomCounts[checkbox.value] = checkbox.nextElementSibling?.textContent?.trim() || checkbox.value;
    });
  }

  initRoomsCheckboxes() {
    const roomsContainer = document.querySelector('[data-dropdown-content="rooms"]');
    if (!roomsContainer) return;

    const clearAllCheckbox = roomsContainer.querySelector('.rooms-clear-checkbox');
    const roomCheckboxes = roomsContainer.querySelectorAll('.rooms-checkbox');
    const roomsLabel = document.getElementById('rooms-label');

    const updateRoomsLabel = () => {
      const selectedRooms = Array.from(roomCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => this.roomCounts[cb.value] || cb.value);

      if (selectedRooms.length === 0) {
        if (roomsLabel) {
          roomsLabel.textContent = roomsLabel.dataset.allText || 'All';
        }
        if (clearAllCheckbox) {
          clearAllCheckbox.checked = true;
        }
      } else {
        if (roomsLabel) {
          roomsLabel.textContent = selectedRooms.join(', ');
        }
        if (clearAllCheckbox) {
          clearAllCheckbox.checked = false;
        }
      }
    };

    const applyRoomsFilter = () => {
      clearTimeout(this.roomsTimeout);
      
      this.roomsTimeout = setTimeout(() => {
        const selectedRooms = Array.from(roomCheckboxes)
          .filter(cb => cb.checked)
          .map(cb => cb.value);

        if (selectedRooms.length > 0) {
          this.filters.rooms = selectedRooms.join(',');
        } else {
          delete this.filters.rooms;
        }

        this.applyFilters();
      }, 500);
    };

    if (clearAllCheckbox) {
      clearAllCheckbox.addEventListener('change', (e) => {
        if (e.target.checked) {
          clearTimeout(this.roomsTimeout);
          roomCheckboxes.forEach(cb => cb.checked = false);
          updateRoomsLabel();
          delete this.filters.rooms;
          this.roomsTimeout = setTimeout(() => {
            this.applyFilters();
          }, 500);
        } else {
          updateRoomsLabel();
        }
      });
    }

    roomCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        if (clearAllCheckbox && clearAllCheckbox.checked) {
          clearAllCheckbox.checked = false;
        }
        updateRoomsLabel();
        applyRoomsFilter();
      });
    });

    if (roomsLabel && !roomsLabel.dataset.allText) {
      roomsLabel.dataset.allText = roomsLabel.textContent;
    }
  }

  initPropertyType() {
    const typeLabel = document.getElementById('type-label');
    if (typeLabel && !typeLabel.dataset.allText) {
      typeLabel.dataset.allText = typeLabel.textContent;
    }
  }

  initAreaFilters() {
    const areaFromInput = document.getElementById('area-from-input');
    const areaToInput = document.getElementById('area-to-input');
    
    if (areaFromInput && areaFromInput.value) {
      this.filters.area_from = areaFromInput.value;
    }
    
    if (areaToInput && areaToInput.value) {
      this.filters.area_to = areaToInput.value;
    }
  }

  initPlotAreaFilters() {
    const plotAreaFromInput = document.getElementById('plot-area-from-input');
    const plotAreaToInput = document.getElementById('plot-area-to-input');
    
    if (plotAreaFromInput && plotAreaFromInput.value) {
      this.filters.plot_area_from = plotAreaFromInput.value;
    }
    
    if (plotAreaToInput && plotAreaToInput.value) {
      this.filters.plot_area_to = plotAreaToInput.value;
    }
  }

  initPriceFilters() {
    const priceFromInput = document.getElementById('price-from-input');
    const priceToInput = document.getElementById('price-to-input');
    
    if (priceFromInput && priceFromInput.value) {
      this.filters.price_from = priceFromInput.value;
    }
    
    if (priceToInput && priceToInput.value) {
      this.filters.price_to = priceToInput.value;
    }
  }

  applyFilters() {
    const params = new URLSearchParams();
    const currentUrl = new URL(window.location.href);

    for (const [key, value] of Object.entries(this.filters)) {
      if (value && value !== 'all' && value !== '') {
        params.append(key, value);
      }
    }

    const existingParams = new URLSearchParams(currentUrl.search);
    const filterKeys = Object.keys(this.filters);
    existingParams.forEach((value, key) => {
      if (!filterKeys.includes(key) && key !== 'rooms' && key !== 'type' && key !== 'area_from' && key !== 'area_to' && key !== 'plot_area_from' && key !== 'plot_area_to' && key !== 'price_from' && key !== 'price_to') {
        params.append(key, value);
      }
    });

    const newUrl = `${currentUrl.pathname}?${params.toString()}`;
    window.location.href = newUrl;
  }

  clearFilters() {
    this.filters = {};
    const currentUrl = new URL(window.location.href);
    window.location.href = currentUrl.pathname;
  }
}

export default DesktopFilters;

