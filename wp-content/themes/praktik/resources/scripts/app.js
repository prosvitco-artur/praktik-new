import domReady from '@roots/sage/client/dom-ready';

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
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
