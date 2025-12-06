import PhotoSwipe from 'photoswipe';

class ReviewGallery {
  constructor() {
    this.init();
  }

  init() {
    const reviewCards = document.querySelectorAll('.review-card');
    if (!reviewCards.length) return;

    reviewCards.forEach((card) => {
      const links = card.querySelectorAll('[data-pswp-src]');
      if (!links.length) return;

      const galleryId = card.querySelector('[data-pswp-src]')?.closest('.review-card')?.dataset?.galleryId || `review-${Math.random().toString(36).substr(2, 9)}`;
      
      const items = Array.from(links).map((link) => {
        const img = link.querySelector('img');
        return {
          src: link.getAttribute('data-pswp-src') || link.href,
          width: link.getAttribute('data-pswp-width') || img?.naturalWidth || 1920,
          height: link.getAttribute('data-pswp-height') || img?.naturalHeight || 1080,
          alt: link.getAttribute('data-pswp-alt') || img?.alt || '',
          msrc: img?.src || '',
        };
      });

      links.forEach((link, index) => {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          this.openPhotoSwipe(items, index);
        });
      });
    });
  }

  openPhotoSwipe(items, index) {
    if (!items || !items.length) return;

    const isMobile = window.innerWidth < 768;

    const options = {
      dataSource: items,
      index: index,
      showHideAnimation: {
        type: 'fade',
        duration: 300,
      },
      zoomAnimation: {
        duration: 300,
      },
      bgOpacity: 0.9,
      spacing: 0.1,
      allowPanToNext: true,
      loop: false,
      pinchToClose: true,
      closeOnVerticalDrag: true,
      escKey: true,
      arrowKeys: true,
      returnFocus: true,
      clickToCloseNonZoomable: true,
    };

    if (!isMobile) {
      options.zoom = {
        enabled: true,
        maxSpreadZoom: 3,
      };
    }

    const pswp = new PhotoSwipe(options);
    pswp.init();
  }
}

export default ReviewGallery;

