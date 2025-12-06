import SimpleLightbox from './simple-lightbox.js';

class ReviewGallery {
  constructor() {
    this.init();
  }

  init() {
    const reviewLinks = document.querySelectorAll('.review-card [data-lightbox-src]');
    if (!reviewLinks.length) return;

    const lightbox = new SimpleLightbox();
    lightbox.init('.review-card [data-lightbox-src]', {
      isReview: true,
    });
  }
}

export default ReviewGallery;

