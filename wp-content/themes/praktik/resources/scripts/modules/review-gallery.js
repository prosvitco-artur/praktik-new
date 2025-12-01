import { Fancybox } from '@fancyapps/ui';

class ReviewGallery {
  constructor() {
    this.init();
  }

  init() {
    const reviewLinks = document.querySelectorAll('.review-card [data-fancybox^="review-"]');
    if (!reviewLinks.length) return;

    const isMobile = window.innerWidth < 768;

    Fancybox.bind('.review-card [data-fancybox^="review-"]', {
      Toolbar: {
        display: {
          left: ['infobar'],
          middle: [],
          right: isMobile ? ['close'] : ['download'],
        },
      },
      Image: {
        zoom: !isMobile,
        wheel: 'slide',
        fit: 'none',
      },
      closeButton: isMobile ? false : 'top',
      wheel: 'slide',
      trapFocus: true,
      autoFocus: true,
      placeFocusBack: true,
    });
  }
}

export default ReviewGallery;

