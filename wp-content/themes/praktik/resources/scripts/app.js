import domReady from '@roots/sage/client/dom-ready';
import MobileMenu from './modules/mobile-menu.js';
import FilterPanel from './modules/filter-panel.js';
import SortPanel from './modules/sort-panel.js';
import CustomDropdown from './modules/custom-dropdown.js';
import DesktopFilters from './modules/desktop-filters.js';
import Favorites from './modules/favorites.js';
import PropertyGallery from './modules/property-gallery.js';
import Share from './modules/share.js';
import PriceRangeSlider from './modules/price-range-slider.js';
import ReviewGallery from './modules/review-gallery.js';
import FavoritesShare from './modules/favorites-share.js';
import ReviewFilters from './modules/review-filters.js';
import BackToCatalog from './modules/back-to-catalog.js';

domReady(async () => {
  new MobileMenu();
  new FilterPanel();
  new SortPanel();
  new CustomDropdown();
  new DesktopFilters();
  new Favorites();
  new PropertyGallery();
  new Share();
  new PriceRangeSlider();
  new ReviewGallery();
  new FavoritesShare();
  new ReviewFilters();
  new BackToCatalog();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
