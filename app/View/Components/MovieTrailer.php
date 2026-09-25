<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MovieTrailer extends Component
{
    public $movie;
    public $trailer;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($movie, $trailer)
    {
        $this->movie = $movie;
        $this->trailer = $trailer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.trailer');
    }
}
