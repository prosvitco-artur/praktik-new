import domReady from '@roots/sage/client/dom-ready';

/**
 * Filter Panel functionality
 */
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

    // Toggle filter panel
    this.filterToggles.forEach(toggle => {
      toggle.addEventListener('click', () => this.toggle());
    });
    this.filterClose?.addEventListener('click', () => this.close());
    this.filterOverlay.addEventListener('click', () => this.close());

    // Filter actions
    this.filterClear?.addEventListener('click', () => this.clearFilters());
    this.filterApply?.addEventListener('click', () => this.applyFilters());

    // Filter section toggles
    const filterToggles = document.querySelectorAll('[data-filter-toggle]');
    filterToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => this.toggleFilterSection(e));
    });

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.close();
      }
    });

    // Prevent body scroll when panel is open
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
    // Clear all form inputs
    const inputs = this.filterPanel.querySelectorAll('input[type="radio"], input[type="checkbox"], input[type="number"]');
    inputs.forEach(input => {
      if (input.type === 'radio' || input.type === 'checkbox') {
        input.checked = false;
      } else {
        input.value = '';
      }
    });
  }

  applyFilters() {
    // Collect filter values
    const params = new URLSearchParams();
    
    // Radio buttons
    const radioInputs = this.filterPanel.querySelectorAll('input[type="radio"]:checked');
    radioInputs.forEach(input => {
      if (input.value && input.value !== 'all') {
        params.append(input.name, input.value);
      }
    });
    
    // Checkboxes (collect rooms)
    const checkboxInputs = this.filterPanel.querySelectorAll('input[type="checkbox"]:checked');
    const rooms = [];
    checkboxInputs.forEach(input => {
      if (input.name.startsWith('room_')) {
        rooms.push(input.value);
      }
    });
    if (rooms.length > 0) {
      params.append('rooms', rooms.join(','));
    }
    
    // Number inputs (price range)
    const numberInputs = this.filterPanel.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
      if (input.name && input.value) {
        const value = parseInt(input.value);
        if (value > 0) {
          params.append(input.name, value);
        }
      }
    });
    
    // Close panel after applying
    this.close();
    
    // Redirect with filters
    const currentUrl = new URL(window.location.href);
    const newUrl = `${currentUrl.pathname}?${params.toString()}`;
    window.location.href = newUrl;
  }

  toggleFilterSection(e) {
    const toggle = e.currentTarget;
    const sectionId = toggle.getAttribute('data-filter-toggle');
    const content = document.querySelector(`[data-filter-content="${sectionId}"]`);
    const icon = toggle.querySelector('svg');
    
    if (!content) return;

    const isExpanded = !content.classList.contains('hidden');
    
    if (isExpanded) {
      content.classList.add('hidden');
      icon?.classList.remove('rotate-180');
      toggle.setAttribute('aria-expanded', 'false');
    } else {
      content.classList.remove('hidden');
      icon?.classList.add('rotate-180');
      toggle.setAttribute('aria-expanded', 'true');
    }
  }
}

/**
 * Mobile Menu functionality
 */
class MobileMenu {
  constructor() {
    this.menuToggle = document.querySelector('[data-mobile-menu-toggle]');
    this.menuPanel = document.querySelector('[data-mobile-menu-panel]');
    this.menuOverlay = document.querySelector('[data-mobile-menu-overlay]');
    this.menuClose = document.querySelector('[data-mobile-menu-close]');
    this.submenuToggles = document.querySelectorAll('[data-submenu-toggle]');
    
    this.isOpen = false;
    
    this.init();
  }

  init() {
    if (!this.menuToggle || !this.menuPanel || !this.menuOverlay) return;

    // Toggle menu
    this.menuToggle.addEventListener('click', () => this.toggle());
    this.menuClose?.addEventListener('click', () => this.close());
    this.menuOverlay.addEventListener('click', () => this.close());

    // Submenu toggles
    this.submenuToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => this.toggleSubmenu(e));
    });

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.close();
      }
    });

    // Prevent body scroll when menu is open
    this.menuPanel.addEventListener('transitionend', () => {
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
    this.menuPanel.classList.remove('translate-y-full');
    this.menuOverlay.classList.remove('opacity-0', 'invisible');
    this.menuToggle.setAttribute('aria-expanded', 'true');
    this.menuOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  close() {
    this.isOpen = false;
    this.menuPanel.classList.add('translate-y-full');
    this.menuOverlay.classList.add('opacity-0', 'invisible');
    this.menuToggle.setAttribute('aria-expanded', 'false');
    this.menuOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  toggleSubmenu(e) {
    const toggle = e.currentTarget;
    const submenu = toggle.parentElement.querySelector('.mobile-submenu');
    const icon = toggle.querySelector('svg');
    
    if (!submenu) return;

    const isExpanded = !submenu.classList.contains('hidden');
    
    if (isExpanded) {
      submenu.classList.add('hidden');
      icon?.classList.remove('rotate-180');
      toggle.setAttribute('aria-expanded', 'false');
    } else {
      submenu.classList.remove('hidden');
      icon?.classList.add('rotate-180');
      toggle.setAttribute('aria-expanded', 'true');
    }
  }
}

/**
 * Custom Dropdown functionality
 */
class CustomDropdown {
  constructor() {
    this.dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
    this.init();
  }

  init() {
    this.dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => this.toggleDropdown(e));
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('[data-dropdown-toggle]') && !e.target.closest('[data-dropdown-content]')) {
        this.closeAllDropdowns();
      }
    });

    // Handle dropdown item selection
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('dropdown-item')) {
        this.selectItem(e);
      }
    });
  }

  toggleDropdown(e) {
    e.stopPropagation();
    const toggle = e.currentTarget;
    const dropdownId = toggle.getAttribute('data-dropdown-toggle');
    const content = document.querySelector(`[data-dropdown-content="${dropdownId}"]`);
    const icon = toggle.querySelector('svg');

    if (!content) return;

    // Check if current dropdown is open BEFORE closing others
    const isCurrentlyOpen = content.classList.contains('dropdown-open');

    // Close all dropdowns (including current one)
    this.closeAllDropdowns();

    // If it was closed, open it. If it was open, keep it closed (toggle behavior)
    if (!isCurrentlyOpen) {
      content.classList.add('dropdown-open');
      icon?.classList.add('rotate-180');
    }
  }

  closeAllDropdowns() {
    const allDropdowns = document.querySelectorAll('[data-dropdown-content]');
    const allIcons = document.querySelectorAll('[data-dropdown-toggle] svg');
    
    allDropdowns.forEach(dropdown => {
      dropdown.classList.remove('dropdown-open');
    });
    
    allIcons.forEach(icon => {
      icon.classList.remove('rotate-180');
    });
  }

  selectItem(e) {
    const item = e.currentTarget;
    const value = item.getAttribute('data-value');
    const label = item.getAttribute('data-label');
    const dropdownId = item.closest('[data-dropdown-content]').getAttribute('data-dropdown-content');
    const toggle = document.querySelector(`[data-dropdown-toggle="${dropdownId}"]`);
    const toggleText = toggle.querySelector('span');

    // Update toggle text
    if (toggleText) {
      toggleText.textContent = label;
    }

    // Update active state
    const dropdownContent = item.closest('[data-dropdown-content]');
    const allItems = dropdownContent.querySelectorAll('.dropdown-item');
    
    allItems.forEach(dropdownItem => {
      dropdownItem.classList.remove('text-info-600');
      dropdownItem.classList.add('text-neutral-700');
    });
    
    item.classList.remove('text-neutral-700');
    item.classList.add('text-info-600');

    // Close dropdown
    this.closeAllDropdowns();

    // Trigger custom event
    const customEvent = new CustomEvent('dropdownChange', {
      detail: {
        dropdownId,
        value,
        label
      }
    });
    document.dispatchEvent(customEvent);
  }
}

/**
 * Desktop Filters Manager
 */
class DesktopFilters {
  constructor() {
    this.filters = {};
    this.init();
  }

  init() {
    // Listen to dropdown changes
    document.addEventListener('dropdownChange', (e) => {
      const { dropdownId, value } = e.detail;
      this.filters[dropdownId] = value;
    });

    // Listen to input changes in dropdowns
    document.addEventListener('change', (e) => {
      if (e.target.matches('[data-dropdown-content] input[type="number"]')) {
        this.filters[e.target.name] = e.target.value;
      }
    });

    // Apply filters when inputs lose focus (optional, for better UX)
    document.addEventListener('blur', (e) => {
      if (e.target.matches('[data-dropdown-content] input[type="number"]')) {
        // Auto-apply after input change (debounced)
        clearTimeout(this.applyTimeout);
        this.applyTimeout = setTimeout(() => {
          if (Object.keys(this.filters).length > 0) {
            this.applyFilters();
          }
        }, 500);
      }
    }, true);

    // Apply filters on Enter key in input fields
    document.addEventListener('keypress', (e) => {
      if (e.target.matches('[data-dropdown-content] input[type="number"]') && e.key === 'Enter') {
        this.applyFilters();
      }
    });
  }

  applyFilters() {
    const params = new URLSearchParams();

    // Add all filter values to params
    for (const [key, value] of Object.entries(this.filters)) {
      if (value && value !== 'all' && value !== '') {
        params.append(key, value);
      }
    }

    // Redirect with filters
    const currentUrl = new URL(window.location.href);
    const newUrl = `${currentUrl.pathname}?${params.toString()}`;
    window.location.href = newUrl;
  }

  clearFilters() {
    this.filters = {};
    // Redirect to clean URL
    const currentUrl = new URL(window.location.href);
    window.location.href = currentUrl.pathname;
  }
}

/**
 * Application entrypoint
 */
domReady(async () => {
  // Initialize mobile menu
  new MobileMenu();
  
  // Initialize filter panel
  new FilterPanel();

  // Initialize custom dropdowns
  new CustomDropdown();

  // Initialize desktop filters
  new DesktopFilters();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
