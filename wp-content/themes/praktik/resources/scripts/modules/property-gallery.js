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

    const isMobile = window.innerWidth < 768;

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

    const swiperConfig = {
      modules: [Navigation],
      spaceBetween: 10,
      navigation: {
        nextEl: mainSwiper.querySelector('.swiper-button-next'),
        prevEl: mainSwiper.querySelector('.swiper-button-prev'),
      },
      on: {
        slideChange: (swiper) => {
          if (photoCounter) {
            const current = swiper.activeIndex + 1;
            const total = swiper.slides.length;
            photoCounter.textContent = `${current}/${total}`;
          }
        },
      },
    };

    if (!isMobile && thumbsInstance) {
      swiperConfig.modules.push(Thumbs);
      swiperConfig.thumbs = {
        swiper: thumbsInstance,
      };
    }

    const mainInstance = new Swiper(mainSwiper, swiperConfig);

    this.initFancybox(mainInstance);
    this.initGalleryButtons();
  }

  initGalleryButtons() {
    const galleryContainer = document.querySelector('.property-gallery');
    if (!galleryContainer) return;

    const openButton = galleryContainer.querySelector('.property-gallery-open-btn');
    if (!openButton) return;
    
    openButton.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      
      const links = galleryContainer.querySelectorAll('[data-fancybox="property-gallery"]');
      
      if (links.length > 0) {
        links[0].click();
      }
    });
  }

  initFancybox(mainInstance) {
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
      on: {
        close: () => {
          if (mainInstance) {
            setTimeout(() => {
              mainInstance.updateSize();
              if (mainInstance.navigation) {
                mainInstance.navigation.update();
              }
            }, 100);
          }
        },
      },
    });
  }
}

export default PropertyGallery;
