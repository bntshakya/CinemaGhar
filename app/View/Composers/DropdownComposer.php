<?php
namespace App\View\Composers;

use App\Models\Location;
use Illuminate\View\View;

class DropdownComposer
{
/**
* Create a new profile composer.
*/
public function __construct(protected Location $location){
}

/**
* Bind data to the view.
*/
public function compose(View $view): void
{
    $view->with(['location'=>$this->location->all(),'location_names'=>$this->location->distinct()->pluck('location')]);
}
}