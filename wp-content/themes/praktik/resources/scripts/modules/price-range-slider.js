export default class PriceRangeSlider {
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

    // Find associated input fields only within the filter panel (mobile)
    const sliderContainer = slider.closest('.filter-content') || slider.closest('[data-filter-content]');
    const fromInput = sliderContainer?.querySelector('[data-price-input="from"]') || 
                      sliderContainer?.querySelector('#filter-price-from');
    const toInput = sliderContainer?.querySelector('[data-price-input="to"]') || 
                    sliderContainer?.querySelector('#filter-price-to');

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

      // Update input fields if they exist and are not focused
      if (!skipInputs) {
        if (fromInput && document.activeElement !== fromInput) {
          fromInput.value = from;
        }
        if (toInput && document.activeElement !== toInput) {
          toInput.value = to;
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

    // Update slider changes
    fromSlider.addEventListener('input', update);
    toSlider.addEventListener('input', update);

    update();
  }
}
