<article @php(post_class('bg-white mb-5 overflow-hidden review-card'))>
  <div class="flex items-start">
  <div class="">
    @if (has_post_thumbnail())
      {!! get_the_post_thumbnail(get_the_ID()) !!}
    @endif
  </div>
  <div class="p-3 md:p-5">
      <div class="mb-3">
        <h3 class="text-h5 md:text-h4 text-neutral-900 font-bold mb-1">
          {!! get_the_title() !!}
        </h3>
        <p class="text-p2 text-neutral-600">
          {{ get_the_date('d.m.Y') }}
        </p>
      </div>
      
      <div class="text-p1 text-neutral-700 leading-relaxed review-content line-clamp-4">
        {!! get_the_content() !!}
      </div>
    </div>
  </div>
</article>

