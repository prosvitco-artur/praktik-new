@extends('layouts.app')

@section('content')
  <div class="container px-4 py-8">
    <header class="page-header spacing-lg">
      <h1 class="page-title">
        {!! $title !!}
      </h1>
      
      @if ($description)
        <div class="page-description text-neutral-600 mt-4">
          {!! $description !!}
        </div>
      @endif
    </header>

    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'sage') !!}
      </x-alert>
    @endif

    <div class="archive-posts">
      @while(have_posts()) @php(the_post())
        @includeFirst(['partials.content-archive-' . get_post_type(), 'partials.content-archive'])
      @endwhile
    </div>

    <div class="mt-12">
      {!! get_the_posts_navigation() !!}
    </div>
  </div>
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection
