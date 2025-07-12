<article @php(post_class('property-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300'))>
  <div class="property-image">
    @if (has_post_thumbnail())
      <a href="{{ get_permalink() }}" class="block">
        {!! get_the_post_thumbnail(null, 'medium', ['class' => 'w-full h-48 object-cover']) !!}
      </a>
    @else
      <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
        <span class="text-gray-400">{{ __('No Image', 'sage') }}</span>
      </div>
    @endif
  </div>

  <div class="property-content p-4">
    <header class="mb-3">
      <h2 class="entry-title text-xl font-semibold mb-2">
        <a href="{{ get_permalink() }}" class="text-gray-800 hover:text-blue-600 transition-colors">
          {!! $title !!}
        </a>
      </h2>

      @include('partials.entry-meta')
    </header>

    <div class="entry-summary text-gray-600 mb-4">
      @php(the_excerpt())
    </div>

    <div class="property-footer flex justify-between items-center">
      <a href="{{ get_permalink() }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
        {{ __('View Details', 'sage') }}
      </a>
      
      <span class="text-sm text-gray-500">
        {{ get_post_type_object(get_post_type())->labels->singular_name }}
      </span>
    </div>
  </div>
</article> 