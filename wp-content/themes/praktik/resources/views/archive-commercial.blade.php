@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <div class="text-center py-12">
      <x-alert type="warning">
        {!! __('Sorry, no commercial properties were found.', 'sage') !!}
      </x-alert>

      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('Try searching for something else:', 'sage') }}</h3>
        @include('partials.search-form')
      </div>

      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('Or browse other property types:', 'sage') }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <a href="{{ home_url('/rooms/') }}" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-purple-600 text-2xl mb-2">🏠</div>
            <div class="text-sm font-medium text-gray-700">{{ __('Rooms', 'sage') }}</div>
          </a>
          <a href="{{ home_url('/apartments/') }}" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-blue-600 text-2xl mb-2">🏢</div>
            <div class="text-sm font-medium text-gray-700">{{ __('Apartments', 'sage') }}</div>
          </a>
          <a href="{{ home_url('/houses/') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-green-600 text-2xl mb-2">🏠</div>
            <div class="text-sm font-medium text-gray-700">{{ __('Houses', 'sage') }}</div>
          </a>
          <a href="{{ home_url('/plots/') }}" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-yellow-600 text-2xl mb-2">📍</div>
            <div class="text-sm font-medium text-gray-700">{{ __('Plots', 'sage') }}</div>
          </a>
        </div>
      </div>
    </div>
  @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @while(have_posts()) @php(the_post())
        @includeFirst(['partials.content-commercial', 'partials.content'])
      @endwhile
    </div>

    {!! get_the_posts_navigation() !!}
  @endif
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection 