class Favorites {
  constructor() {
    this.ajaxUrl = (window.praktikAjax && window.praktikAjax.ajaxurl) || '/wp-admin/admin-ajax.php';
    this.nonce = (window.praktikAjax && window.praktikAjax.favoritesNonce) || '';
    this.i18n = (window.praktikAjax && window.praktikAjax.i18n) || {};
    this.favorites = [];
    this.sessionId = this.getSessionId();
    this.init();
  }

  getSessionId() {
    let sessionId = localStorage.getItem('praktik_session_id');
    if (!sessionId) {
      sessionId = this.generateUUID();
      localStorage.setItem('praktik_session_id', sessionId);
    }
    return sessionId;
  }

  generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      const r = Math.random() * 16 | 0;
      const v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

  async init() {
    await this.loadFavorites();
    
    const favoriteButtons = document.querySelectorAll('button[data-post-id], [data-favorite-toggle]');
    
    favoriteButtons.forEach(button => {
      const postId = button.getAttribute('data-post-id') || button.getAttribute('data-favorite-toggle');
      if (postId) {
        this.updateButtonState(button, postId);
        button.addEventListener('click', (e) => this.toggleFavorite(e, postId));
      }
    });

    this.updateHeaderCounter(this.favorites);
    document.addEventListener('favoritesChanged', (e) => this.updateHeaderCounter(e.detail.favorites));
  }

  async loadFavorites() {
    try {
      const formData = new FormData();
      formData.append('action', 'get_favorites');
      formData.append('session_id', this.sessionId);
      if (this.nonce) {
        formData.append('nonce', this.nonce);
      }

      const response = await fetch(this.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });

      const data = await response.json();
      
      if (data.success && data.data && data.data.favorites) {
        this.favorites = data.data.favorites;
      } else {
        this.favorites = [];
      }
    } catch (error) {
      console.error('Failed to load favorites:', error);
      this.favorites = [];
    }
  }

  updateHeaderCounter(favorites) {
    const bookmarkCounts = document.querySelectorAll('.bookmark-count');
    const count = favorites.length;
    
    bookmarkCounts.forEach(element => {
      element.textContent = count;
    });
  }

  updateButtonState(button, postId) {
    const isFavorite = this.favorites.includes(postId.toString());
    
    if (isFavorite) {
      button.classList.add('favorites-post');
      button.setAttribute('aria-pressed', 'true');
    } else {
      button.classList.remove('favorites-post');
      button.setAttribute('aria-pressed', 'false');
    }
  }

  async toggleFavorite(e, postId) {
    e.preventDefault();
    e.stopPropagation();
    
    const button = e.currentTarget;
    const postIdStr = postId.toString();
    const isFavorite = this.favorites.includes(postIdStr);
    
    button.disabled = true;
    
    try {
      const formData = new FormData();
      formData.append('action', 'toggle_favorite');
      formData.append('post_id', postId);
      formData.append('session_id', this.sessionId);
      if (this.nonce) {
        formData.append('nonce', this.nonce);
      }
      
      const response = await fetch(this.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });
      
      const data = await response.json();
      
      if (data.success && data.data) {
        this.favorites = data.data.favorites || [];
        
        const allButtons = document.querySelectorAll(`button[data-post-id="${postId}"], [data-favorite-toggle="${postId}"]`);
        allButtons.forEach(btn => {
          this.updateButtonState(btn, postId);
        });
        
        this.updateHeaderCounter(this.favorites);
        
        const event = new CustomEvent('favoritesChanged', {
          detail: {
            postId: postId,
            isFavorite: data.data.is_favorite,
            favorites: this.favorites
          }
        });
        document.dispatchEvent(event);
      } else {
        console.error(
          this.i18n.errorSavingFavorites || 'Error saving favorites:',
          data.data?.message || this.i18n.unknownError || 'Unknown error'
        );
      }
    } catch (error) {
      console.error(this.i18n.failedToSendRequest || 'Failed to send request:', error);
    } finally {
      button.disabled = false;
    }
  }
}

export default Favorites;

