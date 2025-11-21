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
      }
    });

    document.addEventListener('change', (e) => {
      if (e.target.matches('[data-dropdown-content] input[type="number"]')) {
        this.filters[e.target.name] = e.target.value;
      }
    });

    document.addEventListener('blur', (e) => {
      if (e.target.matches('[data-dropdown-content] input[type="number"]')) {
        clearTimeout(this.applyTimeout);
        this.applyTimeout = setTimeout(() => {
          if (Object.keys(this.filters).length > 0) {
            this.applyFilters();
          }
        }, 500);
      }
    }, true);

    document.addEventListener('keypress', (e) => {
      if (e.target.matches('[data-dropdown-content] input[type="number"]') && e.key === 'Enter') {
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
      if (!filterKeys.includes(key) && key !== 'rooms' && key !== 'type') {
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

