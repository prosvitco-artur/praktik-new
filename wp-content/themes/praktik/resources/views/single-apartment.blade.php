@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @includeFirst(['partials.content-single-apartment', 'partials.content-single'])
  @endwhile
@endsection 