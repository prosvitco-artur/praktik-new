<form role="search" method="get" class="search-form" action="{{ home_url('/') }}">
  <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
    <div class="flex-1">
      <label for="search-field" class="sr-only">{{ __('Search for:', 'sage') }}</label>
      <input 
        type="search" 
        id="search-field" 
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
        placeholder="{{ __('Search properties...', 'sage') }}" 
        value="{{ get_search_query() }}" 
        name="s"
      >
    </div>
    <button 
      type="submit" 
      class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
    >
      {{ __('Search', 'sage') }}
    </button>
  </div>
</form> 