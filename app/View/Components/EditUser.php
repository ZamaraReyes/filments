<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EditUser extends Component
{
    public $user;
    public $allgenres;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, $allgenres)
    {
        $this->user = $user;
        $this->allgenres = $allgenres;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.alert-change');
    }
}
