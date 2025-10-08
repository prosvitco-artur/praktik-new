{{-- Mobile Menu Overlay --}}
<div 
  class="mobile-menu-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-mobile-menu-overlay
  aria-hidden="true"
></div>

{{-- Mobile Menu Panel --}}
<div 
  class="mobile-menu-panel fixed left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 overflow-y-auto rounded-t-16px"
  data-mobile-menu-panel
  role="dialog"
  aria-modal="true"
  aria-label="Мобільне меню"
>
  {{-- Top Indicator (drag handle) --}}
  <div class="flex justify-center pt-8px pb-4px">
    <div class="w-32px h-4px bg-neutral-300 rounded-full"></div>
  </div>

  {{-- Menu Header --}}
  <div class="flex items-center justify-between px-16px py-12px border-b border-neutral-200">
    <h2 class="text-p1 font-bold text-neutral-900">Меню</h2>
    <button 
      type="button"
      class="mobile-menu-close p-8px -mr-8px"
      aria-label="Закрити меню"
      data-mobile-menu-close
    >
      <x-icon name="close" class="w-24px h-24px text-neutral-900 stroke-current" />
    </button>
  </div>

  {{-- Menu Content --}}
  <nav class="mobile-menu-nav py-16px" aria-label="Мобільна навігація">
    {!! wp_nav_menu([
      'theme_location' => 'primary_navigation',
      'container' => false,
      'menu_class' => 'mobile-nav-menu',
      'fallback_cb' => false,
      'walker' => new \App\View\Components\MobileMenuWalker(),
      'echo' => false
    ]) !!}
  </nav>

  {{-- Saved Items (Optional) --}}
  @if (is_user_logged_in())
  <div class="mt-auto border-t border-neutral-200 px-16px py-16px">
    <a href="{{ home_url('/saved') }}" class="flex items-center justify-between py-12px">
      <span class="text-p1 text-neutral-900">Збережені</span>
      <div class="flex items-center gap-8px">
        <x-icon name="bookmark" class="w-20px h-20px text-neutral-600 stroke-current" />
        <span class="text-p2 text-neutral-600">0</span>
      </div>
    </a>
  </div>
  @endif
</div>

