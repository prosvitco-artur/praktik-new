import Swiper from 'swiper';
import { Navigation, Thumbs, Pagination } from 'swiper/modules';
import { Fancybox } from '@fancyapps/ui';

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

    // Check if mobile device
    const isMobile = window.innerWidth < 768;

    // Initialize thumbs swiper only on desktop
    let thumbsInstance = null;
    if (!isMobile && thumbsSwiper) {
      thumbsInstance = new Swiper(thumbsSwiper, {
        modules: [Thumbs],
        spaceBetween: 8,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
        breakpoints: {
          640: {
            slidesPerView: 5,
            spaceBetween: 10,
          },
          768: {
            slidesPerView: 6,
            spaceBetween: 12,
          },
        },
      });
    }

    // Initialize main swiper
    const swiperConfig = {
      modules: [Navigation],
      spaceBetween: 10,
      navigation: {
        nextEl: mainSwiper.querySelector('.swiper-button-next'),
        prevEl: mainSwiper.querySelector('.swiper-button-prev'),
      },
      on: {
        slideChange: (swiper) => {
          this.updatePhotoCounter(swiper, photoCounter);
        },
      },
    };

    // Add Thumbs module only on desktop
    if (!isMobile && thumbsInstance) {
      swiperConfig.modules.push(Thumbs);
      swiperConfig.thumbs = {
        swiper: thumbsInstance,
      };
    }

    // Add pagination only on desktop
    if (!isMobile) {
      swiperConfig.modules.push(Pagination);
      swiperConfig.pagination = {
        el: mainSwiper.querySelector('.swiper-pagination'),
        type: 'fraction',
        formatFractionCurrent: (number) => number,
        formatFractionTotal: (number) => number,
      };
    }

    const mainInstance = new Swiper(mainSwiper, swiperConfig);

    // Update photo counter on init
    this.updatePhotoCounter(mainInstance, photoCounter);

    // Initialize Fancybox only on desktop
    if (!isMobile) {
      this.initFancybox();
    } else {
      // Remove Fancybox attributes on mobile
      this.removeFancyboxOnMobile();
    }
  }

  initFancybox() {
    const galleryContainer = document.querySelector('.property-gallery');
    if (!galleryContainer) return;

    Fancybox.bind('[data-fancybox="property-gallery"]', {
      Toolbar: {
        display: {
          left: ['infobar'],
          middle: [],
          right: ['slideshow', 'download', 'thumbs', 'close'],
        },
      },
      Thumbs: {
        autoStart: false,
      },
      Image: {
        zoom: true,
        wheel: 'slide',
      },
      Carousel: {
        infinite: false,
      },
      closeButton: 'top',
      wheel: 'slide',
      trapFocus: true,
      autoFocus: true,
      placeFocusBack: true,
    });
  }

  removeFancyboxOnMobile() {
    const galleryContainer = document.querySelector('.property-gallery');
    if (!galleryContainer) return;

    const fancyboxLinks = galleryContainer.querySelectorAll('[data-fancybox="property-gallery"]');
    fancyboxLinks.forEach(link => {
      // Remove Fancybox attributes and make link non-clickable on mobile
      link.removeAttribute('data-fancybox');
      link.removeAttribute('data-caption');
      link.style.pointerEvents = 'none';
      link.style.cursor = 'default';
    });
  }

  isMobileDevice() {
    return window.innerWidth < 768;
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

