<article @php(post_class('bg-white mb-4 overflow-hidden review-card'))>
  <div class="p-5 md:p-6">
    
    <div>
      <div class="mb-3">
        <h3 class="text-h5 md:text-h4 text-neutral-900 font-bold mb-1">
          {!! get_the_title() !!}
        </h3>
        <p class="text-p2 text-neutral-500">
          {{ get_the_date('d.m.Y') }}
        </p>
      </div>
      
      <div class="text-p1 text-neutral-700 leading-relaxed">
        {!! get_the_content() !!}
      </div>
    </div>
  </div>
</article>

