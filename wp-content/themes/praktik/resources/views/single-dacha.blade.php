@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @includeFirst(['partials.content-single-dacha', 'partials.content-single'])
  @endwhile
@endsection 