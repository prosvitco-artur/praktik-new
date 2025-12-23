@php
  $chatbot_url = carbon_get_theme_option('chatbot_url') ?: null;
@endphp

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
        <a href="/privacy-policy" class="footer-link">{{ __('Privacy policy', 'praktik') }}</a>
        <a href="/rules" class="footer-link">{{ __('Terms of use', 'praktik') }}</a>
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

    @if($chatbot_url)
      <div class="footer-cta">
        <div class="footer-text">{{ __('Fresh picks for you:', 'praktik') }}</div>
        <a href="{{ $chatbot_url }}" class="chat-bot-button" target="_blank" rel="noopener noreferrer">
          <span class="robot-icon">🤖</span>
          <span class="button-text">{{ __('Apartment search chatbot', 'praktik') }}</span>
        </a>
      </div>
    @endif
    <div class="footer-links-mobile">
      <a href="/privacy-policy" class="footer-link">{{ __('Privacy policy', 'praktik') }}</a>
      <a href="/rules" class="footer-link">{{ __('Terms of use', 'praktik') }}</a>
    </div>
  </div>
</footer>
