{{-- Mobile Menu Overlay --}}
<div class="mobile-menu-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-mobile-menu-overlay aria-hidden="true"></div>

{{-- Mobile Menu Panel --}}
<div
  class="mobile-menu-panel fixed p-5 left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 rounded-t-lg"
  data-mobile-menu-panel role="dialog" aria-modal="true" aria-label="{{ __('Mobile menu', 'praktik') }}">

  {{-- Menu Header --}}
  <div class="flex items-center justify-between mb-5">
    <div class="font-bold text-neutral-900">{{ __('Menu', 'praktik') }}</div>
    <button type="button" class="mobile-menu-close" aria-label="{{ __('Close menu', 'praktik') }}"
      data-mobile-menu-close>
      <x-icon name="close" class="w-24px h-24px text-neutral-900 stroke-current" />
    </button>
  </div>

  {{-- Menu Content --}}
  <nav class="mobile-menu-nav" aria-label="{{ __('Mobile navigation', 'praktik') }}">
    @if (has_nav_menu('mobile_navigation'))
      {!! wp_nav_menu([
        'theme_location' => 'mobile_navigation',
        'container' => false,
        'menu_class' => 'mobile-nav-menu',
        'fallback_cb' => false,
        'walker' => new \App\View\Components\MobileMenuWalker(),
        'echo' => false
      ]) !!}
    @elseif (has_nav_menu('primary_navigation'))
      {!! wp_nav_menu([
        'theme_location' => 'primary_navigation',
        'container' => false,
        'menu_class' => 'mobile-nav-menu',
        'fallback_cb' => false,
        'walker' => new \App\View\Components\MobileMenuWalker(),
        'echo' => false
      ]) !!}
    @endif
  </nav>

  {{-- Favorites --}}
  <div class="mt-3 px-3">
    <a href="{{ home_url('/favorites') }}" class="bookmark-button flex items-center justify-between py-2"
      aria-label="{{ __('Favorites', 'praktik') }}">
      <span class="text-neutral-900">{{ __('Favorites', 'praktik') }}</span>
      <div class="flex items-center gap-2">
        <x-icon name="bookmark" />
        <span class="bookmark-count">{{ get_user_favorites_count() }}</span>
      </div>
    </a>
  </div>
</div>