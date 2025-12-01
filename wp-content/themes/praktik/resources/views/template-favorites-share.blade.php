@extends('layouts.app')

@section('content')
  @if($has_favorites)
    <div class="container px-5">
    <div class="sort-buttons mt-4 mb-4 flex items-center justify-between flex-wrap gap-3">
      <div class="flex items-center gap-3">
        <div>
          {!! 
            sprintf(__('Found <strong>%s properties</strong>', 'praktik'), count($favorites))
          !!}
        </div>

        <div class="md:hidden flex items-center gap-2">
          <button
            type="button"
            class="flex items-center gap-2 text-info-600 hover:text-info-700 transition-colors bg-white p-2.5"
            id="sort-button-mobile"
            data-sort-panel-toggle
            aria-expanded="false">
            <x-icon name="sort" class="w-6 h-6" />
          </button>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative hidden md:flex items-center gap-2">
          <label class="block text-sm text-neutral-600">{{ __('Sort by', 'praktik') }}</label>
          <button type="button"
            class="filter-dropdown flex items-center gap-2 text-neutral-800 transition-colors bg-white p-2.5"
            id="sort-dropdown" data-dropdown-toggle="sort">
            <span>{{ get_sort_label(get_current_sort()) }}</span>
            <x-icon name="chevron" class="w-4 h-4" />
          </button>

          <div class="dropdown-menu" data-dropdown-content="sort">
            <div class="py-2">
              @foreach(get_sort_options() as $key => $label)
                @php
                  $sort_url = add_query_arg(['sort' => $key], remove_query_arg(['paged', 'sort']));
                @endphp
                <a href="{{ $sort_url }}"
                  class="px-3 py-2 w-full block hover:text-secondary-500 hover:font-bold font-medium {{ get_current_sort() === $key ? 'text-secondary-500 font-bold' : '' }}">
                  {{ $label }}
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('components.sort-panel')

      <div class="archive-posts">
        @foreach($favorites as $post)
          @includeFirst(
            ['partials.content-archive-' . get_post_type($post), 'partials.content-archive'],
            [
              'post' =>  $post, 'favorite' => false
            ]
          )
        @endforeach
        @php(wp_reset_postdata())
      </div>
    </div>
  @else
    <section class="container mx-auto">
      <div class="px-5 min-h-screen lg:min-h-full flex flex-col items-center justify-center lg:py-[140px]">
        <img class="mb-10 w-full max-w-[280px] lg:max-w-[820px]" src="@asset('images/favorites/empty-favorites.svg')" alt="{{ __('Favorites', 'praktik') }}">
        <div class="text-center">
          <p class="font-semibold text-2xl leading-[1.4] mb-5">{{ __('No favorites found', 'praktik') }}</p>
        </div>
      </div>
    </section>
  @endif
@endsection

