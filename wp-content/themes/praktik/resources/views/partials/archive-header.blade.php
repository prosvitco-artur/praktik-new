<header class="archive-header">
  <div class="container px-4 py-8">
    <div class="archive-search">
      <form role="search" method="get" class="lg:flex gap-[8px]" action="{{ get_post_type_archive_link(get_post_type()) }}">
        <div class="w-full relative">
          <input
            type="search"
            placeholder="{{ __('Search', 'praktik') }}"
            value="{{ $_GET['search'] ?? '' }}"
            name="search"
            class="w-full h-[44px] pr-4 pl-[44px] border-0 focus:outline-none"
          >
          <button class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>
        </div>

        <button type="submit" class="btn btn--primary hidden lg:flex">
          <span>{{ __('Search', 'praktik') }}</span>
          <x-icon name="search" class="w-5 h-5 stroke-current" />
        </button>
      </form>
    </div>
  </div>
</header>
