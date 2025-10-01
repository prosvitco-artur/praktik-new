<header class="banner fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
  <div class="container mx-auto px-16px">
    <div class="flex items-center justify-between h-60px">
      {{-- Logo --}}
      <a class="brand flex items-center" href="{{ home_url('/') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="{!! $siteName !!}" class="h-32px" />
      </a>

      {{-- Desktop Navigation --}}
      @if (has_nav_menu('primary_navigation'))
        <nav class="nav-primary hidden lg:block" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
          {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav flex gap-32px items-center', 'echo' => false]) !!}
        </nav>
      @endif

      {{-- Mobile Menu Toggle --}}
      <button 
        type="button"
        class="mobile-menu-toggle lg:hidden p-8px -mr-8px"
        aria-label="Відкрити меню"
        aria-expanded="false"
        data-mobile-menu-toggle
      >
        <svg class="w-24px h-24px text-neutral-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>

  {{-- Mobile Menu --}}
  @if (has_nav_menu('primary_navigation'))
    @include('components.mobile-menu')
  @endif
</header>
