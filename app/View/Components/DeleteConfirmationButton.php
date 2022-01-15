<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteConfirmationButton extends Component
{
    /**
     * Form increment value..
     *
     * @var integer
     */
    public $increment;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($increment)
    {
        $this->increment = $increment;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.delete-confirmation-button');
    }
}
