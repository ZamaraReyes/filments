<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MovieSlider extends Component
{
    public $movie;
    public $genres;
    public $moviesTrailer;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($movie, $genres, $moviesTrailer)
    {
        $this->movie = $movie;
        $this->genres = $genres;
        $this->moviesTrailer = $moviesTrailer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.movie-slider');
    }
}
