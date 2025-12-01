{{-- Review Filter Panel Overlay --}}
<div class="filter-panel-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-filter-panel-overlay aria-hidden="true"></div>

{{-- Review Filter Panel --}}
<div
  class="filter-panel p-5 fixed left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 rounded-t-lg text-neutral-950 overflow-y-auto"
  data-filter-panel role="dialog" aria-modal="true" aria-label="{{ __('Filters', 'praktik') }}">

  <div class="flex items-center justify-between mb-5">
    <div class="font-bold">{{ __('Filter', 'praktik') }}</div>
    <button type="button" class="filter-panel-close" aria-label="{{ __('Close filters', 'praktik') }}" data-filter-panel-close>
      <x-icon name="close" class="w-4 h-4" />
    </button>
  </div>

  <form method="GET" action="{{ get_post_type_archive_link('review') }}" class="filter-panel-content">
    @if(!empty($_GET['search']))
      <input type="hidden" name="search" value="{{ esc_attr($_GET['search']) }}">
    @endif

    <div class="filter-section border-b border-neutral-200">
      <div class="py-3 px-3">
        <label for="review-filter-date-from" class="block text-sm text-neutral-600 mb-2">{{ __('Date From', 'praktik') }}</label>
        <input type="date" id="review-filter-date-from" name="date_from" value="{{ esc_attr($date_from ?? '') }}"
          class="w-full px-3 py-2 border border-neutral-300 rounded">
      </div>
    </div>

    <div class="filter-section border-b border-neutral-200">
      <div class="py-3 px-3">
        <label for="review-filter-date-to" class="block text-sm text-neutral-600 mb-2">{{ __('Date To', 'praktik') }}</label>
        <input type="date" id="review-filter-date-to" name="date_to" value="{{ esc_attr($date_to ?? '') }}"
          class="w-full px-3 py-2 border border-neutral-300 rounded">
      </div>
    </div>

    <div class="flex gap-5 pt-5">
      <button type="button" class="btn btn--second w-full font-bold" data-filter-clear>
        {{ __('Clear', 'praktik') }}
      </button>
      <button type="submit" class="btn btn--primary w-full font-bold" data-filter-apply>
        {{ __('Apply', 'praktik') }}
      </button>
    </div>
  </form>
</div>

