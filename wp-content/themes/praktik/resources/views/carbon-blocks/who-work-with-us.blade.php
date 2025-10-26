<div class="who-work-with-us-block">
  @if(!empty($fields['clients']))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 gap-3">
      @foreach($fields['clients'] as $client)
        <div>
          @if(!empty($client['client_logo']))
            {!! wp_get_attachment_image($client['client_logo'], 'full') !!}
            <div class="py-3">
              @if(!empty($client['client_name']))
                <div class="font-bold mb-1">{{ $client['client_name'] }}</div>
              @endif
              @if(!empty($client['client_text']))
                <p class="client-text">{{ $client['client_text'] }}</p>
              @endif
            </div>
          @endif
        </div>
      @endforeach
    </div>
  @endif
</div>