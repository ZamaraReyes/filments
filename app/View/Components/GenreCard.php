<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GenreCard extends Component
{
    public $genre;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($genre)
    {
        $this->genre = $genre;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.genre-card');
    }
}
