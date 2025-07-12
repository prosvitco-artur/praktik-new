@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @includeFirst(['partials.content-single-plot', 'partials.content-single'])
  @endwhile
@endsection 