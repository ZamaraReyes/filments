<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Facades\Tmdb;

class SearchDropdown extends Component {

    public $search = "";

    public function render() {

    	$searchResults = [];

    	if (strlen($this->search) >= 2 ) {
    		$searchResults = Tmdb::get('/search/movie', ['language' => 'en-US', 'query' => $this->search], 300)['results'] ?? [];
    	}

        return view('livewire.search-dropdown', [
        	'searchResults' => $searchResults
        ]);
    }
}
