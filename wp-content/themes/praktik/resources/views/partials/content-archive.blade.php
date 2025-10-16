<article @php(post_class('bg-white mb-6 overflow-hidden property-card'))>
  <div class="flex flex-col md:flex-row">
    <div class="w-full md:w-[224px] h-48 md:h-auto bg-light-gray flex items-center justify-center">
      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'w-full h-full object-cover']) !!}
      @endif
    </div>

    <div class="w-full md:flex-1 p-4 md:p-5 flex flex-col justify-between">
      <div class="flex-1">
        <div class="flex justify-between items-start mb-2">
          <h2 class="entry-title text-h5 md:text-h4 flex-1 pr-4 line-clamp-2">
            <a href="{{ get_permalink() }}" class="text-neutral-900 hover:text-primary-600 transition-colors duration-200 no-underline property-title block">
              {!! get_the_title() !!}
            </a>
          </h2>
          <div class="text-h4 text-neutral-500 hidden md:block">
            <span>32000 $</span>
          </div>
        </div>

        <div class="flex items-center mb-3">
          <x-icon name="location" class="w-5 h-5 text-neutral-400 mr-2 flex-shrink-0" />
          <span class="text-p1 text-neutral-600 truncate">вул. І. Багряного, Олександрівка, Чернігів</span>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <x-icon name="qube-2" class="w-5 h-5 text-neutral-400 mr-2 flex-shrink-0" />
            <span class="text-p2 text-neutral-600">45 м²</span>
          </div>
          <div class="text-h4 text-neutral-500 md:hidden">
            <span>32000 $</span>
          </div>
        </div>
      </div>

      <!-- Price for mobile, Bookmark for desktop -->
      <!-- <div class="mt-4 pt-4 border-t border-neutral-100 md:border-t-0 md:pt-0 md:mt-0">
        <div class="flex justify-between items-center md:justify-end">
          <div class="md:hidden">
            <span class="text-h4 text-primary-600">32000 $</span>
          </div>
          <button class="hidden md:block text-neutral-400 hover:text-primary-600 transition-colors duration-200">
            <x-icon name="bookmark" class="w-5 h-5 stroke-current" />
          </button>
        </div>
      </div> -->
    </div>
  </div>
</article>
