@php
  $current = isset($current) ? (int) $current : max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 1));
  $total = isset($total) ? (int) $total : (int) ($GLOBALS['wp_query']->max_num_pages ?? 1);
  $query_args = isset($query_args) && is_array($query_args) ? $query_args : [];
  $prev_label = isset($prev_label) ? $prev_label : __('Previous', 'praktik');
  $next_label = isset($next_label) ? $next_label : __('Next', 'praktik');
  $end_size = isset($end_size) ? (int) $end_size : 3;
  $mid_size = isset($mid_size) ? (int) $mid_size : 1;
  $has_pagination = ($total > 1);
  $number_links = $has_pagination ? paginate_links([
    'total' => $total,
    'current' => $current,
    'type' => 'array',
    'prev_next' => false,
    'end_size' => $end_size,
    'mid_size' => $mid_size,
    'add_args' => $query_args,
  ]) : [];
@endphp

@if ($has_pagination)
  <nav class="pagination mt-6" aria-label="{{ __('Пагінація', 'praktik') }}">
    <ul class="flex flex-wrap items-center gap-2 justify-between">
      @php
        $prev_url = add_query_arg($query_args, get_pagenum_link(max(1, $current - 1)));
        $next_url = add_query_arg($query_args, get_pagenum_link(min($total, $current + 1)));
      @endphp
      <li class="inline-block {{ $current > 1 ? 'flex-1 md:flex-none' : '' }}">
        @if ($current > 1)
          <a href="{{ $prev_url }}" class="btn btn--primary">
            <x-icon name="arrow-right" class="w-5 h-5 rotate-180" />{{ $prev_label }}
          </a>
        @else
          <div class="btn cursor-not-allowed">
            <x-icon name="arrow-right" class="w-5 h-5 rotate-180" />
            <span class="hidden md:block">{{ $prev_label }}</span>
          </div>
        @endif
      </li>
      @if (!empty($number_links))
        <ul class="hidden md:block">
          @foreach ($number_links as $link)
            <li class="inline-block">{!! $link !!}</li>
          @endforeach
        </ul>
      @endif
      <li class="inline-block {{ $current < $total ? 'flex-1 md:flex-none' : '' }}">
        @if ($current < $total)
          <a href="{{ $next_url }}" class="btn btn--primary">
            {{ $next_label }}
            <x-icon name="arrow-right" class="w-5 h-5" />
          </a>
        @else
          <div class="btn cursor-not-allowed">
            <span class="hidden md:block">{{ $next_label }}</span>
            <x-icon name="arrow-right" class="w-5 h-5" />
          </div>
        @endif
      </li>
    </ul>
  </nav>
@endif