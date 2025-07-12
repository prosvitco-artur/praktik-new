@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no dachas were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @while(have_posts()) @php(the_post())
      @includeFirst(['partials.content-dacha', 'partials.content'])
    @endwhile
  </div>

  {!! get_the_posts_navigation() !!}
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection 