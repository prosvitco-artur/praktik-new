import Swiper from 'swiper';
import { Navigation, Thumbs, Pagination } from 'swiper/modules';
import SimpleLightbox from './simple-lightbox.js';

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

    this.galleryContainer = galleryContainer;
    this.mainSwiper = mainSwiper;
    this.thumbsSwiper = thumbsSwiper;
    this.photoCounter = galleryContainer.querySelector('.property-photo-counter');

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

    this.thumbsInstance = thumbsInstance;

    const nextButton = mainSwiper.querySelector('.swiper-button-next');
    const prevButton = mainSwiper.querySelector('.swiper-button-prev');

    const swiperConfig = {
      modules: [Navigation],
      spaceBetween: 10,
      navigation: {
        nextEl: nextButton,
        prevEl: prevButton,
      },
      on: {
        slideChange: (swiper) => {
          if (this.photoCounter) {
            const current = swiper.activeIndex + 1;
            const total = swiper.slides.length;
            this.photoCounter.textContent = `${current}/${total}`;
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
    this.mainInstance = mainInstance;

    this.initLightbox(mainInstance);
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
      
      const links = galleryContainer.querySelectorAll('[data-lightbox-src]');
      
      if (links.length > 0) {
        links[0].click();
      }
    });
  }

  initLightbox(mainInstance) {
    const galleryContainer = this.galleryContainer;
    if (!galleryContainer) return;

    this.lightbox = new SimpleLightbox();
    this.lightbox.init('[data-lightbox-src]', {
      onSlideChange: (index) => {
        if (this.mainInstance) {
          this.mainInstance.slideTo(index, 0);
          if (this.thumbsInstance) {
            this.thumbsInstance.slideTo(index, 0);
          }
        }
      },
      onClose: (index) => {
        if (this.mainInstance && this.mainSwiper && index !== null && index !== undefined) {
          setTimeout(() => {
            this.mainInstance.slideTo(index, 0);
            
            if (this.thumbsInstance) {
              this.thumbsInstance.slideTo(index, 0);
            }
            
            const nextButton = this.mainSwiper.querySelector('.swiper-button-next');
            const prevButton = this.mainSwiper.querySelector('.swiper-button-prev');
            
            if (nextButton && prevButton && this.mainInstance.navigation) {
              if (this.mainInstance.params.navigation) {
                this.mainInstance.params.navigation.nextEl = nextButton;
                this.mainInstance.params.navigation.prevEl = prevButton;
              }
              this.mainInstance.navigation.update();
            }
            
            this.mainInstance.updateSize();
            this.mainInstance.update();
            
            if (this.thumbsInstance) {
              this.thumbsInstance.updateSize();
              this.thumbsInstance.update();
            }
            
            if (this.photoCounter && this.mainInstance.slides.length > 0) {
              const current = this.mainInstance.activeIndex + 1;
              const total = this.mainInstance.slides.length;
              this.photoCounter.textContent = `${current}/${total}`;
            }
          }, 150);
        }
      },
    });
  }
}

export default PropertyGallery;
