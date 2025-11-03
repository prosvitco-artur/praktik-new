@extends('layouts.app')

@section('content')
  @php
    $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
    $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
    $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
  @endphp

  <div class="container p-5">
    <div class="archive-reviews mb-8">
      @while(have_posts()) @php(the_post())
        @include('partials.content-archive-review')
      @endwhile
    </div>
  </div>
@endsection
