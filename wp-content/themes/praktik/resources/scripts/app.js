import domReady from '@roots/sage/client/dom-ready';
import MobileMenu from './modules/mobile-menu.js';
import FilterPanel from './modules/filter-panel.js';
import CustomDropdown from './modules/custom-dropdown.js';
import DesktopFilters from './modules/desktop-filters.js';
import Favorites from './modules/favorites.js';
import PropertyGallery from './modules/property-gallery.js';
import Share from './modules/share.js';
import PriceRangeSlider from './modules/price-range-slider.js';
import ReviewGallery from './modules/review-gallery.js';
import FavoritesShare from './modules/favorites-share.js';

domReady(async () => {
  new MobileMenu();
  new FilterPanel();
  new CustomDropdown();
  new DesktopFilters();
  new Favorites();
  new PropertyGallery();
  new Share();
  new PriceRangeSlider();
  new ReviewGallery();
  new FavoritesShare();
});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
