<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CopyrightNotice extends Component
{
    /**
     * The text color scheme.
     *
     * @var string
     */
    public $colorScheme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($colorScheme)
    {
        $this->colorScheme = $colorScheme;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.copyright-notice');
    }
}
