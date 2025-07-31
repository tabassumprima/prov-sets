<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProvisionButton extends Component
{
    public $isLocked, $accounting_year_exists, $isYearEndClosingLocked, $isProvisionLocked;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($isLocked)
    {

        // isLocked false means no next year exists so buttons should be disabled
        if(is_null($isLocked) == true)
        {

            $this->isYearEndClosingLocked = false;
            $this->isProvisionLocked = true;
        }
        else if ($isLocked == true){

            // isLocked true means user can run provision only and not year end closing
            $this->isYearEndClosingLocked = true;
            $this->isProvisionLocked = false;
        }
        else if ($isLocked == false){
            // isLocked null means user can run only year end closing not provision
            $this->isYearEndClosingLocked = true;
            $this->isProvisionLocked = true;
        }

    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.provision-button');
    }
}
