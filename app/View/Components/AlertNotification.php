<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AlertNotification extends Component
{
    public $type, $status, $message;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
        // $this->status = $status;
        // $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.alert-notification');
    }
}
