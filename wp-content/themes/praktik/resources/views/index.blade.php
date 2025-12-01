@extends('layouts.app')

@section('content')

  @if (! have_posts())
    <div class="container mx-auto px-4 py-16 text-center">
      <div class="max-w-2xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-neutral-950">
          {{ __('Nothing Found', 'praktik') }}
        </h1>
        
        @if (is_search())
          <p class="text-lg text-neutral-600 mb-8">
            {{ __('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'praktik') }}
          </p>
          <div class="mb-8">
            {!! get_search_form([]) !!}
          </div>
        @elseif (is_archive())
          <p class="text-lg text-neutral-600 mb-8">
            {{ __('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'praktik') }}
          </p>
          <div class="mb-8">
            {!! get_search_form([]) !!}
          </div>
        @else
          <p class="text-lg text-neutral-600 mb-8">
            {{ __('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'praktik') }}
          </p>
          <div class="mb-8">
            {!! get_search_form([]) !!}
          </div>
        @endif

        <div class="mt-12">
          <a href="{{ home_url('/') }}" class="btn btn--primary inline-flex items-center gap-2">
            <x-icon name="chevron-left" class="w-5 h-5" />
            {{ __('Back to Home', 'praktik') }}
          </a>
        </div>
      </div>
    </div>
  @endif

  @while(have_posts()) @php(the_post())
    @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
  @endwhile

  {!! get_the_posts_navigation() !!}
@endsection
