<header class="banner fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
  <div class="container mx-auto px-16px">
    <div class="flex items-center h-60px">
      
      <a class="brand flex items-center flex-shrink-0" href="{{ home_url('/') }}">
         {!! wp_get_attachment_image(get_theme_mod('custom_logo'), 'full') !!}
      </a>

      {{-- Desktop Navigation - Centered --}}
      @if (has_nav_menu('primary_navigation'))
        <nav class="nav-primary hidden lg:block flex-1 flex justify-center" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation', 
            'container' => false,
            'menu_class' => 'nav flex gap-32px items-center justify-center',
            'walker' => new \App\View\Components\DesktopMenuWalker(),
            'echo' => false
          ]) !!}
        </nav>
      @endif

      <div class="hidden lg:flex items-center gap-2 flex-shrink-0">
        <a class="bookmark-button flex items-center gap-2 p-2 hover:bg-neutral-50 rounded-lg transition-colors" aria-label="{{ __('Favorites', 'sage') }}" href="{{ home_url('/favorites') }}">
          <x-icon name="bookmark" class="w-20px h-20px text-neutral-900 stroke-current" />
          <span class="bookmark-count text-sm font-medium text-neutral-900">0</span>
          </a>
      </div>

      {{-- Mobile Menu Toggle --}}
      <button 
        type="button"
        class="mobile-menu-toggle lg:hidden p-8px -mr-8px ml-auto"
        aria-label="Відкрити меню"
        aria-expanded="false"
        data-mobile-menu-toggle
      >
        <x-icon name="menu" class="w-24px h-24px text-neutral-900 stroke-current" />
      </button>
    </div>
  </div>

  {{-- Mobile Menu --}}
  @if (has_nav_menu('primary_navigation'))
    @include('components.mobile-menu')
  @endif
</header>
