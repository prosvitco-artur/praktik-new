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
      const toggleClicked = e.target.closest('[data-dropdown-toggle]');
      const dropdownContent = e.target.closest('[data-dropdown-content]');
      
      if (!toggleClicked && !dropdownContent) {
        this.closeAllDropdowns();
        return;
      }

      if (dropdownContent) {
        const item = e.target.closest('button, a, .dropdown-item');
        
        if (!item || item.hasAttribute('data-dropdown-toggle')) return;
        if (item.hasAttribute('data-value') && item.hasAttribute('data-label')) {
          this.selectItem(item);
          return;
        }

        this.closeAllDropdowns();
      }
    });
  }

  toggleDropdown(e) {
    e.stopPropagation();
    const toggle = e.currentTarget;
    const dropdownId = toggle.dataset.dropdownToggle;
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
    document.querySelectorAll('[data-dropdown-content]').forEach(dropdown => {
      dropdown.classList.remove('dropdown-open');
    });
    document.querySelectorAll('[data-dropdown-toggle] svg').forEach(icon => {
      icon.classList.remove('rotate-180');
    });
  }

  selectItem(item) {
    const value = item.dataset.value;
    const label = item.dataset.label;
    const dropdownContent = item.closest('[data-dropdown-content]');
    const dropdownId = dropdownContent.dataset.dropdownContent;
    const toggle = document.querySelector(`[data-dropdown-toggle="${dropdownId}"]`);
    const input = toggle.querySelector('input');

    if (input) input.value = label;

    dropdownContent.querySelectorAll('.dropdown-item').forEach(i => {
      i.classList.remove('text-info-600');
      i.classList.add('text-neutral-700');
    });

    item.classList.remove('text-neutral-700');
    item.classList.add('text-info-600');

    this.closeAllDropdowns();

    const customEvent = new CustomEvent('dropdownChange', {
      detail: { dropdownId, value, label }
    });
    document.dispatchEvent(customEvent);
  }
}

export default CustomDropdown;
