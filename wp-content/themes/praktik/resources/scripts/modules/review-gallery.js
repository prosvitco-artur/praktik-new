import { Fancybox } from '@fancyapps/ui';

class ReviewGallery {
  constructor() {
    this.init();
  }

  init() {
    const reviewLinks = document.querySelectorAll('.review-card [data-fancybox^="review-"]');
    if (!reviewLinks.length) return;

    Fancybox.bind('.review-card [data-fancybox^="review-"]', {
      Toolbar: {
        display: {
          left: ['infobar'],
          middle: [],
          right: ['download', 'close'],
        },
      },
      Image: {
        zoom: true,
        wheel: 'slide',
      },
      closeButton: 'top',
      wheel: 'slide',
      trapFocus: true,
      autoFocus: true,
      placeFocusBack: true,
    });
  }
}

export default ReviewGallery;

