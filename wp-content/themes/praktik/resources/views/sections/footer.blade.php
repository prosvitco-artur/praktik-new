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

    @if(has_social_links())
      <div class="social-icons">
        @foreach(get_social_links() as $network => $url)
          @if($url)
            <a href="{{ $url }}" class="social-icon" aria-label="{{ ucfirst($network) }}" target="_blank" rel="noopener noreferrer">
              <x-icon name="{{ $network }}" class="w-6 h-6" />
            </a>
          @endif
        @endforeach
      </div>
    @endif

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
