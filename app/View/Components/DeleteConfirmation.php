<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteConfirmation extends Component
{
    /**
     * The delete confirmation name.
     *
     * @var string
     */
    public $name;

    /**
     * Form attribute values..
     *
     * @var array
     */
    public $formData;

    /**
     * Form increment value..
     *
     * @var int
     */
    public $increment;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $formData, $increment)
    {
        $this->name = $name;
        $this->formData = $formData;
        $this->increment = $increment;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.delete-confirmation');
    }
}
