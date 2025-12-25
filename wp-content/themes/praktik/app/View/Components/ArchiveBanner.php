<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ArchiveBanner extends Component
{
    public $banner;
    public $title;
    public $count;
    
    public function __construct($banner = '', $title = '', $count = '')
    {
        $this->banner = $banner;
        $this->title = $title;
        $this->count = $count;
    }

    public function render()
    {
        return view('components.archive-banner');
    }
}
