class BackToCatalog {
  constructor() {
    this.init();
  }

  init() {
    const buttons = document.querySelectorAll('.back-to-catalog-btn');
    
    buttons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        window.history.back();
      });
    });
  }
}

export default BackToCatalog;

