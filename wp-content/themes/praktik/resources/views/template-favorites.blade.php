@extends('layouts.app')

@section('content')
  @if($has_favorites)
    <div class="container">
      <header class="page-header mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">
          {{ __('Favorites', 'praktik') }}
        </h1>
      </header>

      <div class="archive-posts">
        @foreach($favorites as $post)
          @php(setup_postdata($post))
          @includeFirst(['partials.content-archive-' . get_post_type(), 'partials.content-archive'])
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