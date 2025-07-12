<div class="entry-meta text-sm text-gray-600 flex flex-wrap gap-4">
  <time class="dt-published" datetime="{{ get_post_time('c', true) }}">
    <span class="font-medium">{{ __('Published:', 'sage') }}</span>
    {{ get_the_date() }}
  </time>

  @if (get_the_author())
    <p class="author">
      <span class="font-medium">{{ __('By', 'sage') }}</span>
      <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" class="p-author h-card hover:text-blue-600 transition-colors">
        {{ get_the_author() }}
      </a>
    </p>
  @endif

  @if (get_post_type() !== 'post')
    <span class="property-type">
      <span class="font-medium">{{ __('Type:', 'sage') }}</span>
      {{ get_post_type_object(get_post_type())->labels->singular_name }}
    </span>
  @endif
</div>
