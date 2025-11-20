import { __ } from '@wordpress/i18n';

class Share {
  constructor() {
    this.init();
  }

  init() {
    const shareButtons = document.querySelectorAll('.property-share-button');
    
    shareButtons.forEach(button => {
      button.addEventListener('click', (e) => this.handleShare(e, button));
    });
  }

  async handleShare(e, button) {
    e.preventDefault();
    e.stopPropagation();

    const url = button.getAttribute('data-share-url') || window.location.href;
    const title = button.getAttribute('data-share-title') || document.title;

    // Check if Web Share API is available (mobile devices)
    if (navigator.share) {
      try {
        await navigator.share({
          title: title,
          url: url,
        });
      } catch (error) {
        // User cancelled or error occurred
        if (error.name !== 'AbortError') {
          console.error('Error sharing:', error);
          // Fallback to copy on error
          this.copyToClipboard(url, button);
        }
      }
    } else {
      // Desktop: copy URL to clipboard
      this.copyToClipboard(url, button);
    }
  }

  async copyToClipboard(text, button) {
    try {
      await navigator.clipboard.writeText(text);
      this.showFeedback(button, __('Link copied to clipboard', 'praktik'));
    } catch (error) {
      // Fallback for older browsers
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
        this.showFeedback(button, __('Link copied to clipboard', 'praktik'));
      } catch (err) {
        console.error('Failed to copy:', err);
        this.showFeedback(button, __('Failed to copy link', 'praktik'), true);
      } finally {
        document.body.removeChild(textArea);
      }
    }
  }

  showFeedback(button, message, isError = false) {
    // Remove existing feedback if any
    const existingFeedback = button.parentElement.querySelector('.share-feedback');
    if (existingFeedback) {
      existingFeedback.remove();
    }

    // Get button position
    const buttonRect = button.getBoundingClientRect();
    const buttonParent = button.parentElement;
    const parentRect = buttonParent.getBoundingClientRect();

    // Create feedback element
    const feedback = document.createElement('div');
    feedback.className = 'share-feedback absolute text-sm whitespace-nowrap bg-white px-4 py-2 shadow-lg z-50 border';
    feedback.style.color = '#3C589E';
    feedback.style.borderColor = '#3C589E';
    feedback.textContent = message;
    
    // Position above the button
    feedback.style.bottom = '100%';
    feedback.style.left = '50%';
    feedback.style.marginBottom = '8px';
    feedback.style.transform = 'translateX(-50%)';
    feedback.style.animation = 'fadeInOut 2s ease-in-out';

    // Ensure parent has relative positioning
    if (buttonParent.style.position !== 'relative' && 
        getComputedStyle(buttonParent).position === 'static') {
      buttonParent.style.position = 'relative';
    }

    buttonParent.appendChild(feedback);

    // Remove feedback after 2 seconds
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

export default Share;

