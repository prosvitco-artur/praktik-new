export default class RangeSlider {
  constructor(selector = '[data-slider]') {
    this.sliders = document.querySelectorAll(selector);
    this.sliders.forEach(slider => this.initSlider(slider));
  }

  initSlider(slider) {
    const fromSlider = slider.querySelector('.from-slider');
    const toSlider = slider.querySelector('.to-slider');
    const fromValue = slider.querySelector('.from-value');
    const toValue = slider.querySelector('.to-value');

    if (!fromSlider || !toSlider) return;

    // Find associated input fields
    const sliderContainer = slider.closest('.filter-content') || slider.closest('[data-filter-content]');
    
    // Extract base name from slider name (e.g., "price_from" -> "price", "area_from" -> "area", "plot_area_from" -> "plot-area")
    const fromSliderName = fromSlider.name || '';
    const baseName = fromSliderName.replace(/_from$|_to$/, '');
    
    // Build data attribute selector (e.g., "price" -> "price", "area" -> "area", "plot_area" -> "plot-area")
    const dataAttrPrefix = baseName.replace(/_/g, '-');
    
    // Find input fields using data attributes or IDs
    const fromInput = sliderContainer?.querySelector(`[data-${dataAttrPrefix}-input="from"]`) || 
                     sliderContainer?.querySelector(`#filter-${dataAttrPrefix}-from`) ||
                     document.querySelector(`#filter-${dataAttrPrefix}-from`);
    
    const toInput = sliderContainer?.querySelector(`[data-${dataAttrPrefix}-input="to"]`) || 
                   sliderContainer?.querySelector(`#filter-${dataAttrPrefix}-to`) ||
                   document.querySelector(`#filter-${dataAttrPrefix}-to`);

    const min = parseInt(fromSlider.min);
    const max = parseInt(toSlider.max);

    const update = (skipInputs = false) => {
      let from = parseInt(fromSlider.value);
      let to = parseInt(toSlider.value);

      if (from > to) from = fromSlider.value = to;
      if (to < from) to = toSlider.value = from;

      // Update display values
      if (fromValue) fromValue.textContent = from;
      if (toValue) toValue.textContent = to;

      // Update input fields if they exist
      if (!skipInputs) {
        if (fromInput) {
          const isFocused = document.activeElement === fromInput;
          if (!isFocused) {
            fromInput.value = from;
          }
        }
        if (toInput) {
          const isFocused = document.activeElement === toInput;
          if (!isFocused) {
            toInput.value = to;
          }
        }
      }

      // Update visual gradient
      const fromPercent = ((from - min) / (max - min)) * 100;
      const toPercent = ((to - min) / (max - min)) * 100;

      const gradient = `linear-gradient(to right, #ddd ${fromPercent}%, var(--range-color, #3C589E) ${fromPercent}%, var(--range-color, #3C589E) ${toPercent}%, #ddd ${toPercent}%)`;

      fromSlider.style.background = gradient;
      toSlider.style.background = gradient;
    };

    // Update from input changes
    if (fromInput) {
      fromInput.addEventListener('input', () => {
        let value = parseInt(fromInput.value) || min;
        if (value < min) value = min;
        if (value > max) value = max;
        if (value > parseInt(toSlider.value)) value = parseInt(toSlider.value);
        fromSlider.value = value;
        update(true);
      });

      fromInput.addEventListener('blur', () => {
        let value = parseInt(fromInput.value) || min;
        if (value < min) value = min;
        if (value > max) value = max;
        if (value > parseInt(toSlider.value)) value = parseInt(toSlider.value);
        fromInput.value = value;
        fromSlider.value = value;
        update();
      });
    }

    // Update to input changes
    if (toInput) {
      toInput.addEventListener('input', () => {
        let value = parseInt(toInput.value) || max;
        if (value < min) value = min;
        if (value > max) value = max;
        if (value < parseInt(fromSlider.value)) value = parseInt(fromSlider.value);
        toSlider.value = value;
        update(true);
      });

      toInput.addEventListener('blur', () => {
        let value = parseInt(toInput.value) || max;
        if (value < min) value = min;
        if (value > max) value = max;
        if (value < parseInt(fromSlider.value)) value = parseInt(fromSlider.value);
        toInput.value = value;
        toSlider.value = value;
        update();
      });
    }

    // Update slider changes - use both input and change events for better compatibility
    const handleSliderChange = () => {
      update();
    };
    
    fromSlider.addEventListener('input', handleSliderChange);
    toSlider.addEventListener('input', handleSliderChange);
    fromSlider.addEventListener('change', handleSliderChange);
    toSlider.addEventListener('change', handleSliderChange);

    update();
  }
}

