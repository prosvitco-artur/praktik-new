import domReady from '@roots/sage/client/dom-ready';

/**
 * Filter Panel functionality
 */
class FilterPanel {
  constructor() {
    this.filterToggle = document.querySelector('[data-filter-panel-toggle]');
    this.filterPanel = document.querySelector('[data-filter-panel]');
    this.filterOverlay = document.querySelector('[data-filter-panel-overlay]');
    this.filterClose = document.querySelector('[data-filter-panel-close]');
    this.filterClear = document.querySelector('[data-filter-clear]');
    this.filterApply = document.querySelector('[data-filter-apply]');
    
    this.isOpen = false;
    
    this.init();
  }

  init() {
    if (!this.filterToggle || !this.filterPanel || !this.filterOverlay) return;

    // Toggle filter panel
    this.filterToggle.addEventListener('click', () => this.toggle());
    this.filterClose?.addEventListener('click', () => this.close());
    this.filterOverlay.addEventListener('click', () => this.close());

    // Filter actions
    this.filterClear?.addEventListener('click', () => this.clearFilters());
    this.filterApply?.addEventListener('click', () => this.applyFilters());

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
    this.filterToggle.setAttribute('aria-expanded', 'true');
    this.filterOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  close() {
    this.isOpen = false;
    this.filterPanel.classList.add('translate-y-full');
    this.filterOverlay.classList.add('opacity-0', 'invisible');
    this.filterToggle.setAttribute('aria-expanded', 'false');
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
    const filters = {};
    
    // Radio buttons
    const radioInputs = this.filterPanel.querySelectorAll('input[type="radio"]:checked');
    radioInputs.forEach(input => {
      filters[input.name] = input.value;
    });
    
    // Checkboxes
    const checkboxInputs = this.filterPanel.querySelectorAll('input[type="checkbox"]:checked');
    checkboxInputs.forEach(input => {
      if (!filters[input.name]) {
        filters[input.name] = [];
      }
      filters[input.name].push(input.value);
    });
    
    // Number inputs
    const numberInputs = this.filterPanel.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
      if (input.value) {
        filters[input.name || input.placeholder.toLowerCase()] = input.value;
      }
    });
    
    console.log('Applied filters:', filters);
    
    // Close panel after applying
    this.close();
    
    // Here you would typically trigger a search/filter request
    // For now, just log the filters
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
 * Application entrypoint
 */
domReady(async () => {
  // Initialize mobile menu
  new MobileMenu();
  
  // Initialize filter panel
  new FilterPanel();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
