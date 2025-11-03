<article @php(post_class('bg-white mb-5 overflow-hidden review-card'))>
  <div class="flex items-start">
    <div class="review-image">
      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(get_the_ID()) !!}
      @endif
    </div>
    <div class="p-3 md:p-5">
      <div class="mb-3">
        <p class="font-bold mb-1 review-title">
          {!! get_the_title() !!}
        </p>
        <p class="text-neutral-900 review-date">
          {{ get_the_date('d.m.Y') }}
        </p>
      </div>

      <div class="review-content line-clamp-4">
        {!! get_the_content() !!}
      </div>
    </div>
  </div>
</article>