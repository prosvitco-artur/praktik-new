<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PriceRangeSlider extends Component
{
    public $min;
    public $max;
    public $step;
    public $from;
    public $to;
    public $name;
    public $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct($min = 0, $max = 1000, $step = 1, $from = null, $to = null, $name = '', $text = '')
    {
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->from = $from ?? $min;
        $this->to = $to ?? $max;
        $this->name = $name;
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.price-range-slider');
    }
}
