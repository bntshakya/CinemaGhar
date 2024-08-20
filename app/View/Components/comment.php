<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class comment extends Component
{
    public $comments;

    /**
     * Create a new component instance.
     *
     * @param array $comments
     */
    public function __construct($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Get the view / contents that represent the component.
     * 
     */
    public function render(): View|Closure|string
    {
        return view('comment::components.comment', ['tickets' => $this->comments]);
    }
}