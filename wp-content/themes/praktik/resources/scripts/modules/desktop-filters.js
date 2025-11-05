class DesktopFilters {
  constructor() {
    this.filters = {};
    this.init();
  }

  init() {
    document.addEventListener('dropdownChange', (e) => {
      const { dropdownId, value } = e.detail;
      this.filters[dropdownId] = value;
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

  applyFilters() {
    const params = new URLSearchParams();

    for (const [key, value] of Object.entries(this.filters)) {
      if (value && value !== 'all' && value !== '') {
        params.append(key, value);
      }
    }

    const currentUrl = new URL(window.location.href);
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

