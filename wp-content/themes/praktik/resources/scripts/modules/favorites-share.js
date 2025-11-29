class FavoritesShare {
  constructor() {
    this.i18n = (window.praktikAjax && window.praktikAjax.i18n) || {};
    this.ajaxurl = (window.praktikAjax && window.praktikAjax.ajaxurl) || '/wp-admin/admin-ajax.php';
    this.init();
  }

  init() {
    const shareButton = document.querySelector('[data-favorites-share]');
    if (!shareButton) return;

    shareButton.addEventListener('click', (e) => this.handleShare(e, shareButton));
  }

  async handleShare(e, button) {
    e.preventDefault();
    e.stopPropagation();

    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `<span>${this.i18n.generatingShareLink || 'Generating link...'}</span>`;

    try {
      const sessionId = this.getSessionId();
      const nonce = (window.praktikAjax && window.praktikAjax.nonce) || '';
      const formData = new FormData();
      formData.append('action', 'generate_favorites_share');
      formData.append('nonce', nonce);
      if (sessionId) {
        formData.append('session_id', sessionId);
      }

      const response = await fetch(this.ajaxurl, {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (data.success && data.data && data.data.url) {
        await this.shareUrl(data.data.url, button);
      } else {
        throw new Error(data.data?.message || this.i18n.failedToGenerateLink || 'Failed to generate link');
      }
    } catch (error) {
      console.error('Error generating share link:', error);
      this.showFeedback(button, this.i18n.failedToGenerateLink || 'Failed to generate share link', true);
    } finally {
      button.disabled = false;
      button.innerHTML = originalText;
    }
  }

  async shareUrl(url, button) {
    if (navigator.share) {
      try {
        await navigator.share({
          title: document.title,
          url: url,
        });
      } catch (error) {
        if (error.name !== 'AbortError') {
          this.copyToClipboard(url, button);
        }
      }
    } else {
      this.copyToClipboard(url, button);
    }
  }

  async copyToClipboard(text, button) {
    try {
      await navigator.clipboard.writeText(text);
      this.showFeedback(button, this.i18n.linkCopiedToClipboard || 'Link copied to clipboard');
    } catch (error) {
      const textArea = document.createElement('textarea');
      textArea.value = text;
      textArea.style.position = 'fixed';
      textArea.style.left = '-999999px';
      textArea.style.top = '-999999px';
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      
      try {
        document.execCommand('copy');
        this.showFeedback(button, this.i18n.linkCopiedToClipboard || 'Link copied to clipboard');
      } catch (err) {
        console.error('Failed to copy:', err);
        this.showFeedback(button, this.i18n.failedToCopyLink || 'Failed to copy link', true);
      } finally {
        document.body.removeChild(textArea);
      }
    }
  }

  getSessionId() {
    const cookies = document.cookie.split(';');
    for (let cookie of cookies) {
      const [name, value] = cookie.trim().split('=');
      if (name === 'praktik_session_id') {
        return value;
      }
    }
    return null;
  }

  showFeedback(button, message, isError = false) {
    const existingFeedback = button.parentElement.querySelector('.share-feedback');
    if (existingFeedback) {
      existingFeedback.remove();
    }

    const feedback = document.createElement('div');
    feedback.className = 'share-feedback absolute text-sm whitespace-nowrap bg-white px-4 py-2 shadow-lg z-50 border';
    feedback.style.color = isError ? '#E53F28' : '#3C589E';
    feedback.style.borderColor = isError ? '#E53F28' : '#3C589E';
    feedback.textContent = message;
    
    feedback.style.bottom = '100%';
    feedback.style.left = '50%';
    feedback.style.marginBottom = '8px';
    feedback.style.transform = 'translateX(-50%)';

    const buttonParent = button.parentElement;
    if (buttonParent.style.position !== 'relative' && 
        getComputedStyle(buttonParent).position === 'static') {
      buttonParent.style.position = 'relative';
    }

    buttonParent.appendChild(feedback);

    setTimeout(() => {
      if (feedback.parentElement) {
        feedback.style.opacity = '0';
        feedback.style.transition = 'opacity 0.3s';
        setTimeout(() => {
          if (feedback.parentElement) {
            feedback.remove();
          }
        }, 300);
      }
    }, 2000);
  }
}

export default FavoritesShare;

