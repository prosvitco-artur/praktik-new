@php
  $layout = (is_singular('realtor')) ? 'layouts.app-no-header-footer' : 'layouts.app';
@endphp

@extends($layout)

@section('content')
  @while(have_posts()) @php(the_post())
    @if(in_array(get_post_type(), array_keys(get_property_post_types())))
      @include('partials.content-single-property')
    @else
      @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
    @endif
  @endwhile
@endsection
