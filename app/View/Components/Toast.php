<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Toast extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $errors, $type;
    public function __construct($errors, $type = null)
    {
        $this->errors = $errors;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.toast');
    }
}
