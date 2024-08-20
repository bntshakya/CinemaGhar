<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class admin.hallseats extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct($tickets,$locations,$movienames)
    {
        //
        $this->tickets = $tickets;
        $this->locations = $locations;
        $this->movienames = $movienames
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('admin::components.admin.hallseats');
    }
}
