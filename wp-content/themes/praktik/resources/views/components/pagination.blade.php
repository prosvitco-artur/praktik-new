@php
  $current = isset($current) ? (int) $current : max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 1));
  $total = isset($total) ? (int) $total : (int) ($GLOBALS['wp_query']->max_num_pages ?? 1);
  $query_args = isset($query_args) && is_array($query_args) ? $query_args : [];
  $prev_label = isset($prev_label) ? $prev_label : __('« Назад', 'praktik');
  $next_label = isset($next_label) ? $next_label : __('Далі »', 'praktik');
  $end_size = isset($end_size) ? (int) $end_size : 3;
  $mid_size = isset($mid_size) ? (int) $mid_size : 1;
  $has_pagination = ($total > 1);
  $number_links = $has_pagination ? paginate_links([
    'total'     => $total,
    'current'   => $current,
    'type'      => 'array',
    'prev_next' => false,
    'end_size'  => $end_size,
    'mid_size'  => $mid_size,
    'add_args'  => $query_args,
  ]) : [];
@endphp

@if ($has_pagination)
  <nav class="mt-6" aria-label="{{ __('Пагінація', 'praktik') }}">
    <ul class="flex flex-wrap items-center gap-2">
      @php
        $prev_url = add_query_arg($query_args, get_pagenum_link(max(1, $current - 1)));
        $next_url = add_query_arg($query_args, get_pagenum_link(min($total, $current + 1)));
      @endphp
      <li class="inline-block">
        @if ($current > 1)
          <a href="{{ $prev_url }}" class="px-3 py-2 border border-neutral-200 rounded hover:bg-neutral-50">{{ $prev_label }}</a>
        @else
          <span class="px-3 py-2 border border-neutral-200 rounded text-neutral-400 cursor-not-allowed">{{ $prev_label }}</span>
        @endif
      </li>
      @if (!empty($number_links))
        @foreach ($number_links as $link)
          <li class="inline-block">{!! $link !!}</li>
        @endforeach
      @endif
      <li class="inline-block">
        @if ($current < $total)
          <a href="{{ $next_url }}" class="px-3 py-2 border border-neutral-200 rounded hover:bg-neutral-50">{{ $next_label }}</a>
        @else
          <span class="px-3 py-2 border border-neutral-200 rounded text-neutral-400 cursor-not-allowed">{{ $next_label }}</span>
        @endif
      </li>
    </ul>
  </nav>
@endif


