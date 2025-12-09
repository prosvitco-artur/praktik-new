@extends('layouts.app')

@section('content')
@php
  $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
  $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
  $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';
  $review_archive_banner = carbon_get_theme_option('review_archive_banner') ?? 2909;
@endphp

<div class="archive-container container md:pb-0">
  <div class="block-cover mb-8" style="min-height:160px;aspect-ratio:unset;">
    {!! wp_get_attachment_image($review_archive_banner, 'full', false, ['class' => 'absolute inset-0 w-full h-full object-cover', 'loading' => 'lazy', 'decoding' => 'async']) !!}
    <span aria-hidden="true"
      class="wp-block-cover__background has-secondary-500-background-color has-background-dim-70 has-background-dim">
    </span>
    <div class="wp-block-cover__inner-container flex items-center gap-5">
      <h1 class="m-0 text-4xl">
        <strong>{{ post_type_archive_title('', false) }}</strong>
      </h1>
      <span class="text-2xl">
        {{ wp_count_posts('review')->publish }}
      </span>
    </div>
  </div>
  <div class="flex items-center gap-4 mb-8 px-5 md:px-0">
    <form method="GET" class="md:flex gap-[8px] w-full" action="{{ get_post_type_archive_link(get_post_type()) }}">
      <div class="w-full relative filter-input">
        <label for="review-search-input" class="sr-only">{{ __('Search', 'praktik') }}</label>
        <input type="search" id="review-search-input" placeholder="{{ __('Search', 'praktik') }}" value="{{ $_GET['search'] ?? '' }}"
          name="search" class="w-full h-[44px] pr-4 pl-[44px] border-0 focus:outline-none">
        <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" aria-label="{{ __('Search', 'praktik') }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>
      </div>

      <button type="submit" class="btn btn--primary hidden lg:flex md:mr-8">
        <span>{{ __('Search', 'praktik') }}</span>
        <x-icon name="search" class="w-5 h-5 text-transparent" />
      </button>

      <div class="h-[44px] bg-white hidden md:flex items-center gap-2 pl-5">
        <label for="date_from" class="text-neutral-500">{{ __('From: ', 'praktik') }}</label>
        <input id="date_from" name="date_from" type="date" value="{{ esc_attr($date_from) }}"
          class="px-3 py-2 text-neutral-500">
      </div>
      <div class="h-[44px] bg-white hidden md:flex items-center gap-2 pl-5">

        <label for="date_to" class="text-neutral-500">{{ __('To: ', 'praktik') }}</label>
        <input id="date_to" name="date_to" type="date" value="{{ esc_attr($date_to) }}"
          class="px-3 py-2 text-neutral-500">
      </div>
    </form>
    <div class="md:hidden">
      <button type="button"
        class="flex items-center gap-2 text-info-600 hover:text-info-700 transition-colors relative bg-white p-2.5"
        id="filter-button" data-filter-panel-toggle aria-expanded="false">
        <x-icon name="filter" class="w-6 h-6" />
        @php
          $has_active_review_filters = !empty($_GET['date_from']) || !empty($_GET['date_to']);
        @endphp
        @if($has_active_review_filters)
        <div class="absolute top-2.5 right-2.5 w-2 h-2 bg-warning-500 rounded-full border border-white"></div>
        @endif
      </button>
      @include('components.review-filter-panel', ['date_from' => $date_from, 'date_to' => $date_to])
    </div>
  </div>

  @if (! have_posts())
    <section class="container mx-auto">
      <div class="px-5 min-h-screen lg:min-h-full flex flex-col items-center justify-center lg:pt-[140px]">
        <img class="mb-10 w-full max-w-[280px] lg:max-w-[820px]" src="@asset('images/favorites/empty-favorites.svg')" alt="{{ __('No reviews found', 'praktik') }}">
      </div>
    </section>
  @else
    <div class="mb-8 px-5 md:px-0">
      @while(have_posts()) @php(the_post())
      @include('partials.content-archive-review')
      @endwhile
    </div>
    @php($pg = praktik_get_pagination_params([
      'persist_keys' => ['search','date_from','date_to'],
      'end_size' => 3,
      'mid_size' => 1,
    ]))
    @include('components.pagination', $pg)
  @endif
</div>
@endsection