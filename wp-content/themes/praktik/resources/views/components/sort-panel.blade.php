{{-- Sort Panel Overlay --}}
<div class="sort-panel-overlay fixed inset-0 bg-neutral-900/50 z-40 opacity-0 invisible transition-all duration-300"
  data-sort-panel-overlay aria-hidden="true"></div>

{{-- Sort Panel --}}
<div
  class="sort-panel p-5 fixed left-0 right-0 bottom-0 w-full max-h-[80vh] bg-white z-50 transform translate-y-full transition-transform duration-300 rounded-t-lg text-neutral-950 overflow-y-auto"
  data-sort-panel role="dialog" aria-modal="true" aria-label="{{ __('Sort by', 'praktik') }}">

  <div class="flex items-center justify-between mb-5">
    <div class="font-bold">{{ __('Sort by', 'praktik') }}</div>
    <button type="button" class="sort-panel-close" aria-label="{{ __('Close sorting', 'praktik') }}" data-sort-panel-close>
      <x-icon name="close" class="w-4 h-4" />
    </button>
  </div>

  <div class="sort-panel-content">
    @foreach(get_sort_options() as $key => $label)
      @php
        $sort_url = add_query_arg(['sort' => $key], remove_query_arg(['paged', 'sort']));
        $is_selected = get_current_sort() === $key;
      @endphp
      <a href="{{ $sort_url }}"
        class="block px-3 py-2 mb-2 {{ $is_selected ? 'text-secondary-500 font-bold' : 'text-neutral-950' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>
</div>
