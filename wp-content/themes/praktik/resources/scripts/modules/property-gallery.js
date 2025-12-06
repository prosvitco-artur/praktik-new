import Swiper from 'swiper';
import { Navigation, Thumbs, Pagination } from 'swiper/modules';
import PhotoSwipe from 'photoswipe';

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

    this.initPhotoSwipe(mainInstance);
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
      
      const links = galleryContainer.querySelectorAll('[data-pswp-src]');
      
      if (links.length > 0 && this.pswpItems && this.pswpItems.length > 0) {
        this.openPhotoSwipe(0);
      }
    });
  }

  initPhotoSwipe(mainInstance) {
    const galleryContainer = this.galleryContainer;
    if (!galleryContainer) return;

    const isMobile = window.innerWidth < 768;
    const links = galleryContainer.querySelectorAll('[data-pswp-src]');
    
    if (!links.length) return;

    this.pswpItems = Array.from(links).map((link, index) => {
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
        this.openPhotoSwipe(index);
      });
    });
  }

  openPhotoSwipe(index) {
    if (!this.pswpItems || !this.pswpItems.length) return;

    const isMobile = window.innerWidth < 768;

    const options = {
      dataSource: this.pswpItems,
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

    pswp.on('change', () => {
      const currentIndex = pswp.currIndex;
      if (this.mainInstance) {
        this.mainInstance.slideTo(currentIndex, 0);
        if (this.thumbsInstance) {
          this.thumbsInstance.slideTo(currentIndex, 0);
        }
      }
    });

    pswp.on('close', () => {
      const currentIndex = pswp.currIndex;
      
      if (this.mainInstance && this.mainSwiper) {
        setTimeout(() => {
          if (currentIndex !== null && currentIndex !== undefined) {
            this.mainInstance.slideTo(currentIndex, 0);
            
            if (this.thumbsInstance) {
              this.thumbsInstance.slideTo(currentIndex, 0);
            }
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
    });
  }
}

export default PropertyGallery;
