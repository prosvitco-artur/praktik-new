import Swiper from 'swiper';
import { Navigation, Thumbs, Pagination } from 'swiper/modules';

class PropertyGallery {
  constructor() {
    this.init();
  }

  init() {
    const galleryContainer = document.querySelector('.property-gallery');
    if (!galleryContainer) return;

    const mainSwiper = galleryContainer.querySelector('.property-gallery-main');
    const thumbsSwiper = galleryContainer.querySelector('.property-gallery-thumbs');

    if (!mainSwiper) return;

    const photoCounter = galleryContainer.querySelector('.property-photo-counter');

    // Initialize thumbs swiper if exists
    let thumbsInstance = null;
    if (thumbsSwiper) {
      thumbsInstance = new Swiper(thumbsSwiper, {
        modules: [Thumbs],
        spaceBetween: 8,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
        breakpoints: {
          768: {
            spaceBetween: 12,
          },
        },
      });
    }

    // Initialize main swiper
    const mainInstance = new Swiper(mainSwiper, {
      modules: [Navigation, Thumbs, Pagination],
      spaceBetween: 10,
      navigation: {
        nextEl: mainSwiper.querySelector('.swiper-button-next'),
        prevEl: mainSwiper.querySelector('.swiper-button-prev'),
      },
      pagination: {
        el: mainSwiper.querySelector('.swiper-pagination'),
        type: 'fraction',
        formatFractionCurrent: (number) => number,
        formatFractionTotal: (number) => number,
      },
      thumbs: thumbsInstance ? {
        swiper: thumbsInstance,
      } : null,
      on: {
        slideChange: (swiper) => {
          this.updatePhotoCounter(swiper, photoCounter);
        },
      },
    });

    // Update photo counter on init
    this.updatePhotoCounter(mainInstance, photoCounter);
  }

  updatePhotoCounter(swiper, counter) {
    if (counter && swiper) {
      const current = swiper.activeIndex + 1;
      const total = swiper.slides.length;
      counter.textContent = `${current}/${total}`;
    }
  }
}

export default PropertyGallery;

