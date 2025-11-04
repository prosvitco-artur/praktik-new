@extends('layouts.app')

@section('content')
  @include('partials.archive-header')
  
  <div class="container">

    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'praktik') !!}
      </x-alert>
    @endif

    <div class="archive-posts">
      @while(have_posts()) @php(the_post())
        @includeFirst(['partials.content-archive-' . get_post_type(), 'partials.content-archive'])
      @endwhile
    </div>

    @php($pg = praktik_get_pagination_params())
    @include('components.pagination', $pg)
  </div>
@endsection
