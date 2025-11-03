<article @php(post_class('bg-white mb-4 overflow-hidden review-card'))>
  <div class="flex items-start">
  <div class="w-[180px] h-[250px] bg-secondary-500 flex items-center justify-center flex-shrink-0 mr-4 md:mr-6">
    @if (has_post_thumbnail())
      {!! get_the_post_thumbnail(get_the_ID()) !!}
    @endif
  </div>  
  <div class="flex-1">
      <div class="mb-3">
        <h3 class="text-h5 md:text-h4 text-neutral-900 font-bold mb-1">
          {!! get_the_title() !!}
        </h3>
        <p class="text-p2 text-neutral-600">
          {{ get_the_date('d.m.Y') }}
        </p>
      </div>
      
      <div class="text-p1 text-neutral-700 leading-relaxed">
        {!! get_the_content() !!}
      </div>
    </div>
  </div>
</article>

