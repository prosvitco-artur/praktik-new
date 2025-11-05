class CustomDropdown {
  constructor() {
    this.dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
    this.init();
  }

  init() {
    this.dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => this.toggleDropdown(e));
    });

    document.addEventListener('click', (e) => {
      if (!e.target.closest('[data-dropdown-toggle]') && !e.target.closest('[data-dropdown-content]')) {
        this.closeAllDropdowns();
      }
    });

    document.addEventListener('click', (e) => {
      const dropdownContent = e.target.closest('[data-dropdown-content]');
      if (!dropdownContent) return;

      const item = e.target.closest('button, a, .dropdown-item');
      if (!item) return;

      if (item.hasAttribute('data-dropdown-toggle')) return;

      const dropdownId = dropdownContent.getAttribute('data-dropdown-content');
      const toggle = document.querySelector(`[data-dropdown-toggle="${dropdownId}"]`);
      
      if (!toggle) return;

      const toggleText = toggle.querySelector('span');
      const itemText = item.textContent?.trim();
      
      if (toggleText && itemText) {
        toggleText.textContent = itemText;
      }

      if (item.hasAttribute('data-value') && item.hasAttribute('data-label')) {
        this.selectItem(e);
        return;
      }

      this.closeAllDropdowns();
    });
  }

  toggleDropdown(e) {
    e.stopPropagation();
    const toggle = e.currentTarget;
    const dropdownId = toggle.getAttribute('data-dropdown-toggle');
    const content = document.querySelector(`[data-dropdown-content="${dropdownId}"]`);
    const icon = toggle.querySelector('svg');

    if (!content) return;

    const isCurrentlyOpen = content.classList.contains('dropdown-open');

    this.closeAllDropdowns();

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

    if (toggleText) {
      toggleText.textContent = label;
    }

    const dropdownContent = item.closest('[data-dropdown-content]');
    const allItems = dropdownContent.querySelectorAll('.dropdown-item');
    
    allItems.forEach(dropdownItem => {
      dropdownItem.classList.remove('text-info-600');
      dropdownItem.classList.add('text-neutral-700');
    });
    
    item.classList.remove('text-neutral-700');
    item.classList.add('text-info-600');

    this.closeAllDropdowns();

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

export default CustomDropdown;

