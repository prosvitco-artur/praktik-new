<div class="how-we-work-block">
  @if(!empty($fields['steps']))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-4 gap-3">
      @foreach($fields['steps'] as $key => $step)
        <div class="py-4">
          @if(!empty($step['step_title']))
            <div class="font-bold mb-2">
              <span class="text-neutral-500">{{ $key + 1 }}.</span>
              <span>{{ $step['step_title'] }}</span>
            </div>
          @endif
          
          @if(!empty($step['step_description']))
            <p class="step-description">{{ $step['step_description'] }}</p>
          @endif
        </div>
      @endforeach
    </div>
  @endif
</div>

