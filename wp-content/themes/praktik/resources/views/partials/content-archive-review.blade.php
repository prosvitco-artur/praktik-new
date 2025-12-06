<article @php(post_class('bg-white mb-5 overflow-hidden review-card'))>
  <div class="flex items-start">
    <div class="shrink-0 w-[31.5%] md:w-[16%]">
      @if (has_post_thumbnail())
        <a href="{{ get_the_post_thumbnail_url(get_the_ID(), 'full') }}" 
           data-pswp-src="{{ get_the_post_thumbnail_url(get_the_ID(), 'full') }}"
           data-pswp-width="{{ wp_get_attachment_image_src(get_the_ID(), 'full')[1] ?? 1920 }}"
           data-pswp-height="{{ wp_get_attachment_image_src(get_the_ID(), 'full')[2] ?? 1080 }}"
           data-pswp-alt="{{ get_the_title() }}"
           class="block">
          {!! get_the_post_thumbnail(get_the_ID(), 'medium', ['loading' => 'lazy', 'decoding' => 'async']) !!}
        </a>
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