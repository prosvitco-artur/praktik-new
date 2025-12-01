class ReviewFilters {
  constructor() {
    this.init();
  }

  init() {
    this.initDesktopDateFilters();
    this.initMobileDateFilters();
  }

  initDesktopDateFilters() {
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    const form = dateFrom?.closest('form');

    if (!dateFrom || !form) return;

    const submitForm = () => {
      form.submit();
    };

    dateFrom.addEventListener('change', submitForm);
    dateTo?.addEventListener('change', submitForm);
  }

  initMobileDateFilters() {
    const filterPanel = document.querySelector('[data-filter-panel]');
    if (!filterPanel) return;

    const dateFrom = filterPanel.querySelector('#review-filter-date-from');
    const dateTo = filterPanel.querySelector('#review-filter-date-to');
    const form = filterPanel.querySelector('form');

    if (!dateFrom || !form) return;

    const submitForm = () => {
      form.submit();
    };

    dateFrom.addEventListener('change', submitForm);
    dateTo?.addEventListener('change', submitForm);
  }
}

export default ReviewFilters;

