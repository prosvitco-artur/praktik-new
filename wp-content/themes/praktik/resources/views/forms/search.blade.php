<form role="search" method="get" class="search-form" action="{{ home_url('/') }}">
  <label for="search-input">
    <span class="sr-only">
      {{ _x('Search for:', 'label', 'praktik') }}
    </span>

    <input
      type="search"
      id="search-input"
      placeholder="{!! esc_attr_x('Search &hellip;', 'placeholder', 'praktik') !!}"
      value="{{ get_search_query() }}"
      name="s"
    >
  </label>
  <button type="submit">{{ _x('Search', 'submit button', 'praktik') }}</button>
</form>