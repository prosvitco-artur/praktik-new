<header class="bg-white shadow-md sticky top-0 z-50">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between h-16">
      
      <!-- Logo -->
      <div class="flex items-center space-x-3">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
          </svg>
        </div>
        <a href="{{ home_url('/') }}" class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
          {!! $siteName !!}
        </a>
      </div>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex items-center space-x-8">
        @if (has_nav_menu('primary_navigation'))
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation', 
            'menu_class' => 'flex space-x-6', 
            'container' => false,
            'echo' => false,
            'walker' => new \App\View\Components\NavigationWalker()
          ]) !!}
        @else
          <div class="flex space-x-6">
            <a href="{{ home_url('/') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
              Головна
            </a>
            <a href="{{ home_url('/rooms/') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
              Кімнати
            </a>
            <a href="{{ home_url('/apartments/') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
              Квартири
            </a>
            <a href="{{ home_url('/houses/') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
              Будинки
            </a>
            <a href="{{ home_url('/plots/') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
              Ділянки
            </a>
          </div>
        @endif
      </nav>

      <!-- Mobile Menu Button -->
      <div class="md:hidden">
        <button type="button" class="mobile-menu-button text-gray-700 hover:text-blue-600 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-menu hidden md:hidden">
      <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
        @if (has_nav_menu('primary_navigation'))
          {!! wp_nav_menu([
            'theme_location' => 'primary_navigation', 
            'menu_class' => 'space-y-2', 
            'container' => false,
            'echo' => false,
            'walker' => new \App\View\Components\NavigationWalker()
          ]) !!}
        @else
          <a href="{{ home_url('/') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors rounded-md">
            Головна
          </a>
          <a href="{{ home_url('/rooms/') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors rounded-md">
            Кімнати
          </a>
          <a href="{{ home_url('/apartments/') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors rounded-md">
            Квартири
          </a>
          <a href="{{ home_url('/houses/') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors rounded-md">
            Будинки
          </a>
          <a href="{{ home_url('/plots/') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 hover:bg-gray-50 transition-colors rounded-md">
            Ділянки
          </a>
        @endif
      </div>
    </div>
  </div>
</header>
