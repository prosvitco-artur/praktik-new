<div class="how-we-work-block">
  @if(!empty($fields['steps']))
    <div class="steps-container">
      @foreach($fields['steps'] as $step)
        <div class="step-item">
          @if(!empty($step['step_title']))
            <h3 class="step-title">{{ $step['step_title'] }}</h3>
          @endif
          
          @if(!empty($step['step_description']))
            <p class="step-description">{{ $step['step_description'] }}</p>
          @endif
        </div>
      @endforeach
    </div>
  @endif
</div>

