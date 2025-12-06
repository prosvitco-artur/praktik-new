class SimpleLightbox {
  constructor() {
    this.isOpen = false;
    this.currentIndex = 0;
    this.items = [];
    this.config = {};
    this.container = null;
    this.imageContainer = null;
    this.zoomLevel = 1;
    this.isDragging = false;
    this.dragStartX = 0;
    this.dragStartY = 0;
    this.translateX = 0;
    this.translateY = 0;
    this.onCloseCallback = null;
  }

  init(selector, options = {}) {
    this.config = {
      selector,
      onClose: options.onClose || null,
      onSlideChange: options.onSlideChange || null,
      isReview: options.isReview || false,
      ...options,
    };

    const links = document.querySelectorAll(selector);
    if (!links.length) return;

    this.items = Array.from(links).map((link) => ({
      src: link.href || link.getAttribute('data-src'),
      title: link.getAttribute('data-title') || link.title || '',
    }));

    links.forEach((link, index) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        this.open(index);
      });
    });
  }

  open(index) {
    if (this.items.length === 0) return;

    this.currentIndex = index;
    this.isOpen = true;
    this.zoomLevel = 1;
    this.translateX = 0;
    this.translateY = 0;

    this.createContainer();
    this.showImage(this.currentIndex);
    this.bindEvents();

    document.body.style.overflow = 'hidden';
  }

  createContainer() {
    this.container = document.createElement('div');
    this.container.className = 'simple-lightbox';
    this.container.innerHTML = `
      <div class="simple-lightbox-overlay"></div>
      <div class="simple-lightbox-content">
        <button class="simple-lightbox-close" aria-label="Close">&times;</button>
        <button class="simple-lightbox-zoom" aria-label="Zoom">+</button>
        <button class="simple-lightbox-prev" aria-label="Previous">‹</button>
        <button class="simple-lightbox-next" aria-label="Next">›</button>
        <div class="simple-lightbox-image-wrapper">
          <img class="simple-lightbox-image" src="" alt="" />
        </div>
        <div class="simple-lightbox-counter"></div>
      </div>
    `;

    document.body.appendChild(this.container);

    if (this.config.isReview) {
      this.container.classList.add('simple-lightbox-review');
    }

    this.imageContainer = this.container.querySelector('.simple-lightbox-image-wrapper');
    this.image = this.container.querySelector('.simple-lightbox-image');
    this.counter = this.container.querySelector('.simple-lightbox-counter');
    this.closeBtn = this.container.querySelector('.simple-lightbox-close');
    this.zoomBtn = this.container.querySelector('.simple-lightbox-zoom');
    this.prevBtn = this.container.querySelector('.simple-lightbox-prev');
    this.nextBtn = this.container.querySelector('.simple-lightbox-next');
    this.overlay = this.container.querySelector('.simple-lightbox-overlay');

    setTimeout(() => {
      this.container.classList.add('active');
    }, 10);
  }

  showImage(index) {
    if (index < 0 || index >= this.items.length) return;

    this.currentIndex = index;
    const item = this.items[index];

    this.image.src = item.src;
    this.image.alt = item.title;

    if (this.counter) {
      this.counter.textContent = `${index + 1} / ${this.items.length}`;
    }

    this.image.onload = () => {
      this.image.style.height = '100vh';
      this.image.style.width = 'auto';
      this.image.style.maxWidth = 'none';
    };

    this.resetZoom();
    this.updateNavigation();
    this.updateCursor();

    if (this.config.onSlideChange) {
      this.config.onSlideChange(index);
    }
  }

  updateNavigation() {
    if (this.prevBtn) {
      this.prevBtn.style.display = this.items.length > 1 ? 'block' : 'none';
    }
    if (this.nextBtn) {
      this.nextBtn.style.display = this.items.length > 1 ? 'block' : 'none';
    }
  }

  resetZoom() {
    this.zoomLevel = 1;
    this.translateX = 0;
    this.translateY = 0;
    this.updateTransform();
    this.updateCursor();
  }

  updateTransform() {
    if (!this.image) return;
    this.image.style.transform = `scale(${this.zoomLevel}) translate(${this.translateX}px, ${this.translateY}px)`;
    this.updateCursor();
  }

  updateCursor() {
    if (!this.imageContainer) return;
    if (this.zoomLevel > 1) {
      this.imageContainer.style.cursor = 'grab';
      this.image.style.cursor = 'grab';
      if (this.zoomBtn) {
        this.zoomBtn.textContent = '−';
        this.zoomBtn.setAttribute('aria-label', 'Zoom Out');
      }
    } else {
      this.imageContainer.style.cursor = 'default';
      this.image.style.cursor = 'default';
      if (this.zoomBtn) {
        this.zoomBtn.textContent = '+';
        this.zoomBtn.setAttribute('aria-label', 'Zoom In');
      }
    }
  }

  zoom(delta) {
    const newZoom = Math.max(1, Math.min(3, this.zoomLevel + delta));
    if (newZoom === this.zoomLevel) return;

    this.zoomLevel = newZoom;
    
    if (this.zoomLevel === 1) {
      this.translateX = 0;
      this.translateY = 0;
    }
    
    this.updateTransform();
  }

  next() {
    if (this.currentIndex < this.items.length - 1) {
      this.showImage(this.currentIndex + 1);
    }
  }

  prev() {
    if (this.currentIndex > 0) {
      this.showImage(this.currentIndex - 1);
    }
  }

  close() {
    if (!this.isOpen) return;

    this.container.classList.remove('active');
    
    setTimeout(() => {
      if (this.container && this.container.parentNode) {
        this.container.parentNode.removeChild(this.container);
      }
      this.container = null;
      this.image = null;
      this.isOpen = false;
      
      document.body.style.overflow = '';
      
      if (this.config.onClose) {
        this.config.onClose(this.currentIndex);
      }
    }, 300);
  }

  bindEvents() {
    if (this.closeBtn) {
      this.closeBtn.addEventListener('click', () => this.close());
    }

    if (this.overlay) {
      this.overlay.addEventListener('click', () => this.close());
    }

    const content = this.container.querySelector('.simple-lightbox-content');
    if (content) {
      content.addEventListener('click', (e) => {
        if (e.target === content || e.target.classList.contains('simple-lightbox-content')) {
          this.close();
        }
      });
    }

    if (this.prevBtn) {
      this.prevBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.prev();
      });
    }

    if (this.nextBtn) {
      this.nextBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.next();
      });
    }

    if (this.zoomBtn) {
      this.zoomBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (this.zoomLevel > 1) {
          this.resetZoom();
        } else {
          this.zoom(1);
        }
      });
    }

    document.addEventListener('keydown', this.handleKeydown = (e) => {
      if (!this.isOpen) return;

      switch (e.key) {
        case 'Escape':
          this.close();
          break;
        case 'ArrowLeft':
          this.prev();
          break;
        case 'ArrowRight':
          this.next();
          break;
      }
    });

    if (this.imageContainer) {
      this.imageContainer.addEventListener('wheel', this.handleWheel = (e) => {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.1 : 0.1;
        this.zoom(delta);
      }, { passive: false });


      this.image.addEventListener('mousedown', this.handleMouseDown = (e) => {
        if (this.zoomLevel > 1) {
          e.preventDefault();
          this.isDragging = true;
          this.dragStartX = e.clientX - this.translateX;
          this.dragStartY = e.clientY - this.translateY;
          if (this.image) {
            this.image.style.cursor = 'grabbing';
          }
        }
      });

      document.addEventListener('mousemove', this.handleMouseMove = (e) => {
        if (this.isDragging && this.zoomLevel > 1) {
          this.translateX = e.clientX - this.dragStartX;
          this.translateY = e.clientY - this.dragStartY;
          this.updateTransform();
        }
      });

      document.addEventListener('mouseup', this.handleMouseUp = () => {
        if (this.isDragging) {
          this.isDragging = false;
          this.updateCursor();
        }
      });

      let touchStartDistance = 0;
      let touchStartZoom = 1;

      this.imageContainer.addEventListener('touchstart', (e) => {
        if (e.touches.length === 2) {
          const touch1 = e.touches[0];
          const touch2 = e.touches[1];
          touchStartDistance = Math.hypot(
            touch2.clientX - touch1.clientX,
            touch2.clientY - touch1.clientY
          );
          touchStartZoom = this.zoomLevel;
        }
      });

      this.imageContainer.addEventListener('touchmove', (e) => {
        if (e.touches.length === 2) {
          e.preventDefault();
          const touch1 = e.touches[0];
          const touch2 = e.touches[1];
          const currentDistance = Math.hypot(
            touch2.clientX - touch1.clientX,
            touch2.clientY - touch1.clientY
          );
          
          const scale = currentDistance / touchStartDistance;
          this.zoomLevel = Math.max(1, Math.min(3, touchStartZoom * scale));
          this.updateTransform();
        }
      });
    }

    window.addEventListener('resize', this.handleResize = () => {
      if (this.isOpen && this.image) {
        this.image.style.height = '100vh';
        this.image.style.width = 'auto';
        this.image.style.maxWidth = 'none';
        this.updateTransform();
      }
    });
  }

  cleanup() {
    document.removeEventListener('keydown', this.handleKeydown);
    document.removeEventListener('mousemove', this.handleMouseMove);
    document.removeEventListener('mouseup', this.handleMouseUp);
    window.removeEventListener('resize', this.handleResize);
    
    if (this.imageContainer) {
      this.imageContainer.removeEventListener('wheel', this.handleWheel);
    }
  }
}

export default SimpleLightbox;

