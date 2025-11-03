<article @php(post_class('bg-white mb-5 overflow-hidden review-card'))>
  <div class="flex items-start">
    <div class="">
      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(get_the_ID()) !!}
      @endif
    </div>
    <div class="p-3 md:p-5">
      <div class="mb-3">
        <p class="font-bold mb-1 text-[14px]" style="line-height: 18px;">
          {!! get_the_title() !!}
        </p>
        <p class="text-neutral-900 text-[12px]" style="line-height: 16px;">
          {{ get_the_date('d.m.Y') }}
        </p>
      </div>

      <div class="text-[12px] review-content line-clamp-4">
        {!! get_the_content() !!}
      </div>
    </div>
  </div>
</article>