<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActorCard extends Component
{
    public $actor;
    public $genres;
    public $favoritasActors;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($actor, $genres, $favoritasActors)
    {
        $this->actor = $actor;
        $this->genres = $genres;
        $this->favoritasActors = $favoritasActors;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.actor-card');
    }
}
