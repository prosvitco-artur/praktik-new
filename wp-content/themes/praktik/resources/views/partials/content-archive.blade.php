<article @php(post_class('bg-white mb-6 overflow-hidden property-card'))>
  <div class="flex flex-col md:flex-row">
    <div class="w-full md:w-[224px] h-48 md:h-auto bg-neutral-100 flex items-center justify-center">
      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'w-full h-full object-cover']) !!}
      @else
        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-neutral-100 to-neutral-200">
          <svg class="w-60px h-60px text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
        </div>
      @endif
    </div>

    <div class="w-full md:flex-1 p-4 md:p-5 flex flex-col justify-between">
      <div class="hidden md:block absolute top-6 right-6">
        <span class="text-h4 text-primary-600 font-bold">32000 $</span>
      </div>

      <div class="flex-1">
        <div class="flex justify-between items-start mb-4">
          <h2 class="entry-title text-h5 md:text-h4 flex-1 pr-4">
            <a href="{{ get_permalink() }}" class="text-neutral-900 hover:text-primary-600 transition-colors duration-200 no-underline property-title">
              {!! get_the_title() !!}
            </a>
          </h2>
          <!-- Bookmark in top right for mobile only -->
          <button class="text-neutral-400 hover:text-primary-600 transition-colors duration-200 flex-shrink-0 md:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
          </button>
        </div>

        <div class="flex items-center mb-3">
          <svg class="w-4 h-4 text-neutral-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
          </svg>
          <span class="text-p2 text-neutral-600">вул. І. Багряного, Олександрівка, Чернігів</span>
        </div>

        <div class="flex items-center">
          <svg class="w-4 h-4 text-neutral-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 1v10h10V5H5z" clip-rule="evenodd" />
          </svg>
          <span class="text-p2 text-neutral-600">45 м²</span>
        </div>
      </div>

      <!-- Price for mobile, Bookmark for desktop -->
      <!-- <div class="mt-4 pt-4 border-t border-neutral-100 md:border-t-0 md:pt-0 md:mt-0">
        <div class="flex justify-between items-center md:justify-end">
          <div class="md:hidden">
            <span class="text-h4 text-primary-600 font-bold">32000 $</span>
          </div>
          <button class="hidden md:block text-neutral-400 hover:text-primary-600 transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
          </button>
        </div>
      </div> -->
    </div>
  </div>
</article>
