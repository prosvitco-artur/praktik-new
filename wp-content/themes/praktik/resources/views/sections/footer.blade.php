<footer class="footer">
  <div class="footer-container">
    <div class="footer-logo-section">
      <div class="footer-logo">
        @php
          $footer_logo_id = get_theme_mod('footer_logo');
        @endphp
        
        @if($footer_logo_id)
          <a href="{{ home_url() }}">
            {!! wp_get_attachment_image($footer_logo_id, 'full', false, ['alt' => get_bloginfo('name'), 'class' => 'footer-logo-image']) !!}
          </a>
        @endif
      </div>
      <div class="footer-links">
        <a href="/privacy-policy" class="footer-link">Політика конфіденційності</a>
        <a href="/terms-of-use" class="footer-link">Умови використання</a>
      </div>
    </div>

    <div class="social-icons">
      <a href="#" class="social-icon" aria-label="Telegram">
        <x-icon name="telegram" class="w-6 h-6" />
      </a>
      <a href="#" class="social-icon" aria-label="YouTube">
        <x-icon name="youtube" class="w-6 h-6" />
      </a>
      <a href="#" class="social-icon" aria-label="Instagram">
        <x-icon name="instagram" class="w-6 h-6" />
      </a>
      <a href="#" class="social-icon" aria-label="TikTok">
        <x-icon name="tiktok" class="w-6 h-6" />
      </a>
      <a href="#" class="social-icon" aria-label="Facebook">
        <x-icon name="facebook" class="w-6 h-6" />
      </a>
    </div>

    <div class="footer-cta">
      <div class="footer-text">Актуальні підбірки для вас:</div>
      <button class="chat-bot-button">
        <span class="robot-icon">🤖</span>
        <span class="button-text">Чат бот пошуку квартир</span>
      </button>
    </div>

    <div class="footer-links-mobile">
      <a href="/privacy-policy" class="footer-link">Політика конфіденційності</a>
      <a href="/terms-of-use" class="footer-link">Умови використання</a>
    </div>
  </div>
</footer>
