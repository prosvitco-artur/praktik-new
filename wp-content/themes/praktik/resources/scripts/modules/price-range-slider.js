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

    const min = parseInt(fromSlider.min);
    const max = parseInt(fromSlider.max);

    const update = () => {
      let from = parseInt(fromSlider.value);
      let to = parseInt(toSlider.value);

      if (from > to) from = fromSlider.value = to;
      if (to < from) to = toSlider.value = from;

      fromValue.textContent = from;
      toValue.textContent = to;

      const fromPercent = ((from - min) / (max - min)) * 100;
      const toPercent = ((to - min) / (max - min)) * 100;

      const gradient = `linear-gradient(to right, #ddd ${fromPercent}%, var(--range-color, #3C589E) ${fromPercent}%, var(--range-color, #3C589E) ${toPercent}%, #ddd ${toPercent}%)`;

      fromSlider.style.background = gradient;
      toSlider.style.background = gradient;
    };

    fromSlider.addEventListener('input', update);
    toSlider.addEventListener('input', update);

    update();
  }
}
