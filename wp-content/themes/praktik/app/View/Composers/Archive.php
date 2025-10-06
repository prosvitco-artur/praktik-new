<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Archive extends Composer
{
    protected static $views = [
        'archive',
        'archive-*',
    ];

    public function override()
    {
        return [
            'title' => $this->title(),
            'description' => $this->description(),
        ];
    }

    public function title()
    {
        return get_the_archive_title();
    }

    public function description()
    {
        return get_the_archive_description();
    }
}
