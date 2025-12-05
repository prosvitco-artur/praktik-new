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
            spaceBetween: 12,
          },
          768: {
            spaceBetween: 16,
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

    const mainInstance = new Swiper(mainSwiper, swiperConfig);

    // Update photo counter on init
    this.updatePhotoCounter(mainInstance, photoCounter);

    // Initialize Fancybox for all devices
    this.initFancybox();
    
    // Handle gallery open buttons
    this.initGalleryButtons(mainInstance);
  }

  initGalleryButtons(swiperInstance) {
    const galleryContainer = document.querySelector('.property-gallery');
    if (!galleryContainer) return;

    const openButton = galleryContainer.querySelector('.property-gallery-open-btn');
    if (!openButton) return;
    
    openButton.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      
      const currentIndex = swiperInstance ? swiperInstance.activeIndex : 0;
      const links = galleryContainer.querySelectorAll('[data-fancybox="property-gallery"]');
      
      if (links[currentIndex]) {
        links[currentIndex].click();
      }
    });
  }

  initFancybox() {
    const galleryContainer = document.querySelector('.property-gallery');
    if (!galleryContainer) return;

    const isMobile = window.innerWidth < 768;

    Fancybox.bind('[data-fancybox="property-gallery"]', {
      Toolbar: {
        display: {
          left: ['infobar'],
          middle: [],
          right: isMobile ? ['close'] : ['slideshow', 'download', 'thumbs'],
        },
      },
      Thumbs: {
        autoStart: false,
      },
      Image: {
        zoom: !isMobile,
        wheel: 'slide',
        fit: 'none',
      },
      Carousel: {
        infinite: false,
      },
      closeButton: isMobile ? false : 'top',
      wheel: 'slide',
      trapFocus: true,
      autoFocus: true,
      placeFocusBack: true,
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

