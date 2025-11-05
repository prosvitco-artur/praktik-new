@php
  $post_type = get_post_type();
  $property_meta = get_property_meta();
  $address_parts = array_filter([
    $property_meta['street'] ?? '',
    $property_meta['district'] ?? '',
    $property_meta['city'] ?? ''
  ]);
  $property_address = implode(', ', $address_parts);
  $favorites = get_user_favorites();
  $post_id = get_the_ID();
  $is_favorite = in_array((string)$post_id, $favorites);
@endphp

<article @php(post_class('bg-white mb-6 overflow-hidden property-card'))>
  <div class="flex flex-col md:flex-row">
    <div class="w-full md:w-[224px] h-48 md:h-[168px] bg-light-gray flex items-center justify-center">
      @if (has_post_thumbnail())
        {!! get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'w-full h-full object-cover']) !!}
      @endif
    </div>

    <div class="w-full md:flex-1 p-4 md:p-5 flex flex-col justify-between">
      <div class="flex-1">
        <div class="flex justify-between items-start mb-2">
          <h2 class="entry-title text-h5 md:text-h4 flex-1 pr-4 line-clamp-2">
            <a href="{{ get_permalink() }}"
              class="text-neutral-900 transition-colors duration-200 no-underline property-title block">
              {!! get_the_title() !!}
            </a>
          </h2>
          <div class="text-h4 text-primary-500 hidden md:block">
            <span>{{ format_property_price($property_meta['price']) }}</span>
          </div>
          <button class="md:hidden text-secondary-500 {{ $is_favorite ? 'favorites-post' : '' }}" data-post-id="{{ $post_id }}">
            <x-icon name="bookmark" />
          </button>
        </div>

        <div class="flex items-center gap-1 mb-3 text-secondary-500">
          <x-icon name="location" />
          <span class="text-p1 text-neutral-700 truncate">{{ $property_address }}</span>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2 text-neutral-400">
            <x-icon name="qube-2" />
            <span class="text-p2">{{ $property_meta['area'] }} м²</span>
          </div>
          <div class="text-h4 text-primary-500 md:hidden">
            <span>{{ format_property_price($property_meta['price']) }}</span>
          </div>
          <button class="hidden md:block text-secondary-500 {{ $is_favorite ? 'favorites-post' : '' }}" data-post-id="{{ $post_id }}">
            <x-icon name="bookmark" />
          </button>
        </div>

      </div>
    </div>
  </div>
</article>