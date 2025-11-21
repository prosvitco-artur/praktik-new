class Favorites {
  constructor() {
    this.ajaxUrl = (window.praktikAjax && window.praktikAjax.ajaxurl) || '/wp-admin/admin-ajax.php';
    this.nonce = (window.praktikAjax && window.praktikAjax.favoritesNonce) || '';
    this.i18n = (window.praktikAjax && window.praktikAjax.i18n) || {};
    this.init();
  }

  init() {
    const favoriteButtons = document.querySelectorAll('button[data-post-id], [data-favorite-toggle]');
    
    favoriteButtons.forEach(button => {
      const postId = button.getAttribute('data-post-id') || button.getAttribute('data-favorite-toggle');
      if (postId) {
        this.updateButtonState(button, postId);
        button.addEventListener('click', (e) => this.toggleFavorite(e, postId));
      }
    });

    this.initHeaderCounter();
    document.addEventListener('favoritesChanged', (e) => this.updateHeaderCounter(e.detail.favorites));
  }

  initHeaderCounter() {
    this.updateHeaderCounter(this.getFavorites());
  }

  updateHeaderCounter(favorites) {
    const bookmarkCounts = document.querySelectorAll('.bookmark-count');
    const count = favorites.length;
    
    bookmarkCounts.forEach(element => {
      element.textContent = count;
    });
  }

  updateButtonState(button, postId) {
    const favorites = this.getFavorites();
    const isFavorite = favorites.includes(postId.toString());
    
    if (isFavorite) {
      button.classList.add('favorites-post');
      button.setAttribute('aria-pressed', 'true');
    } else {
      button.classList.remove('favorites-post');
      button.setAttribute('aria-pressed', 'false');
    }
  }

  getFavorites() {
    const cookie = this.getCookie('praktik_favorites');
    if (!cookie) return [];
    
    try {
      return JSON.parse(decodeURIComponent(cookie));
    } catch (e) {
      return [];
    }
  }

  setFavorites(favorites) {
    const expires = new Date();
    expires.setTime(expires.getTime() + 365 * 24 * 60 * 60 * 1000);
    document.cookie = `praktik_favorites=${encodeURIComponent(JSON.stringify(favorites))};expires=${expires.toUTCString()};path=/`;
  }

  getCookie(name) {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }

  async toggleFavorite(e, postId) {
    e.preventDefault();
    e.stopPropagation();
    
    const button = e.currentTarget;
    const favorites = this.getFavorites();
    const postIdStr = postId.toString();
    const isFavorite = favorites.includes(postIdStr);
    
    button.disabled = true;
    
    try {
      const formData = new FormData();
      formData.append('action', 'toggle_favorite');
      formData.append('post_id', postId);
      formData.append('nonce', this.nonce);
      
      const response = await fetch(this.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      });
      
      const data = await response.json();
      
      if (data.success) {
        let newFavorites;
        if (isFavorite) {
          newFavorites = favorites.filter(id => id !== postIdStr);
        } else {
          newFavorites = [...favorites, postIdStr];
        }
        
        this.setFavorites(newFavorites);
        this.updateButtonState(button, postId);
        this.updateHeaderCounter(newFavorites);
        
        const event = new CustomEvent('favoritesChanged', {
          detail: {
            postId: postId,
            isFavorite: !isFavorite,
            favorites: newFavorites
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

