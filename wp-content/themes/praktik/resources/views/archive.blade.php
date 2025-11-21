@extends('layouts.app')

@section('content')
  @include('partials.archive-header')
  <div class="archive-container container px-5 pb-5 md:pb-0">
    @if (! have_posts())
      <section class="container mx-auto">
        <div class="px-5 min-h-screen lg:min-h-full flex flex-col items-center justify-center lg:pt-[140px]">
          <img class="mb-10 w-full max-w-[280px] lg:max-w-[820px]" src="@asset('images/favorites/empty-favorites.svg')" alt="{{ __('No results found', 'praktik') }}">
        </div>
      </section>
    @else
      <div class="archive-posts">
        @while(have_posts()) @php(the_post())
          @includeFirst(['partials.content-archive-' . get_post_type(), 'partials.content-archive'])
        @endwhile
      </div>

      @php($pg = praktik_get_pagination_params())
      @include('components.pagination', $pg)
    @endif
  </div>
@endsection
