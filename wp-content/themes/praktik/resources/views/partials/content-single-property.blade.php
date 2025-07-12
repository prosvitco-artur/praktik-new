<article @php(post_class('property-single max-w-4xl mx-auto'))>
  <header class="mb-8">
    <h1 class="entry-title text-3xl font-bold mb-4">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')
  </header>

  @if (has_post_thumbnail())
    <div class="property-featured-image mb-8">
      {!! get_the_post_thumbnail(null, 'large', ['class' => 'w-full h-96 object-cover rounded-lg']) !!}
    </div>
  @endif

  <div class="property-content prose prose-lg max-w-none">
    @php(the_content())
  </div>

  <footer class="mt-8 pt-8 border-t border-gray-200">
    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
      <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
        {{ get_post_type_object(get_post_type())->labels->singular_name }}
      </span>
      
      @if (get_the_date())
        <span>{{ __('Published:', 'sage') }} {{ get_the_date() }}</span>
      @endif
      
      @if (get_the_modified_date())
        <span>{{ __('Updated:', 'sage') }} {{ get_the_modified_date() }}</span>
      @endif
    </div>
  </footer>
</article> 