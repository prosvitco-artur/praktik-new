import domReady from '@roots/sage/client/dom-ready';
import MobileMenu from './modules/mobile-menu.js';
import FilterPanel from './modules/filter-panel.js';
import CustomDropdown from './modules/custom-dropdown.js';
import DesktopFilters from './modules/desktop-filters.js';

domReady(async () => {
  new MobileMenu();
  new FilterPanel();
  new CustomDropdown();
  new DesktopFilters();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
