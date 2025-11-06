@extends('layouts.app')

@section('content')
  @if($has_favorites)
    <div class="container">
    <div class="wp-block-cover mb-8" style="min-height:160px;aspect-ratio:unset;">
      {!! wp_get_attachment_image($favorite_page_banner, 'full', false, ['class' => 'absolute inset-0 w-full h-full object-cover', 'loading' => 'lazy', 'decoding' => 'async']) !!}
      <span aria-hidden="true"
        class="wp-block-cover__background has-secondary-500-background-color has-background-dim-70 has-background-dim">
      </span>
      <div class="wp-block-cover__inner-container flex items-center gap-5">
        <h1 class="m-0 text-4xl">
          <strong>{{ __('Compare selected objects', 'praktik') }}</strong>
        </h1>
      </div>
    </div>

      <div class="archive-posts">
        @foreach($favorites as $post)
          @includeFirst(
            ['partials.content-archive-' . get_post_type(), 'partials.content-archive'],
            ['post' =>  $post]
          )
        @endforeach
        @php(wp_reset_postdata())
      </div>
    </div>
  @else
    <section class="container mx-auto">
      <div class="px-5 min-h-screen lg:min-h-full flex flex-col items-center justify-center lg:pt-[140px]">
        <img class="mb-10 w-full max-w-[280px] lg:max-w-[820px]" src="@asset('images/favorites/empty-favorites.svg')" alt="{{ __('Favorites', 'praktik') }}">
        <div class="text-center max-w-md">
          <p class="font-semibold text-2xl leading-[1.4] mb-5">{{ __('Your savings items will be here.', 'praktik') }}</p>
          <p class="text-base leading-[1.5] text-gray-600">{{ __('Select those that deserve the most, and equalize them at any time.', 'praktik') }}</p>
        </div>
      </div>
    </section>
  @endif
@endsection