class SortPanel {
  constructor() {
    this.sortToggles = document.querySelectorAll('[data-sort-panel-toggle]');
    this.sortPanel = document.querySelector('[data-sort-panel]');
    this.sortOverlay = document.querySelector('[data-sort-panel-overlay]');
    this.sortClose = document.querySelector('[data-sort-panel-close]');
    
    this.isOpen = false;
    
    this.init();
  }

  init() {
    if (!this.sortToggles.length || !this.sortPanel || !this.sortOverlay) return;

    this.sortToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        this.toggle();
      });
    });
    this.sortClose?.addEventListener('click', () => this.close());
    this.sortOverlay.addEventListener('click', () => this.close());

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.close();
      }
    });

    this.sortPanel.addEventListener('transitionend', () => {
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
    const filterPanel = document.querySelector('[data-filter-panel]');
    const filterOverlay = document.querySelector('[data-filter-panel-overlay]');
    
    if (filterPanel && filterOverlay && !filterPanel.classList.contains('translate-y-full')) {
      filterPanel.classList.add('translate-y-full');
      filterOverlay.classList.add('opacity-0', 'invisible');
      filterOverlay.setAttribute('aria-hidden', 'true');
      const filterToggles = document.querySelectorAll('[data-filter-panel-toggle]');
      filterToggles.forEach(toggle => {
        toggle.setAttribute('aria-expanded', 'false');
      });
    }

    this.isOpen = true;
    this.sortPanel.classList.remove('translate-y-full');
    this.sortOverlay.classList.remove('opacity-0', 'invisible');
    this.sortToggles.forEach(toggle => {
      toggle.setAttribute('aria-expanded', 'true');
    });
    this.sortOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  close() {
    this.isOpen = false;
    this.sortPanel.classList.add('translate-y-full');
    this.sortOverlay.classList.add('opacity-0', 'invisible');
    this.sortToggles.forEach(toggle => {
      toggle.setAttribute('aria-expanded', 'false');
    });
    this.sortOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
}

export default SortPanel;
