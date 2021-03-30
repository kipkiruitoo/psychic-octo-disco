<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDropdown extends Component
{
    public $search = '';

    public function render()
    {
        $searchResults = [];

        if (strlen($this->search) >= 2) {
            $series = Http::withToken(config('services.tmdb.token'))
                ->get('https://v4-cinemeta.strem.io/catalog/series/top/search=' . $this->search . '.json')
                ->json()['metas'];

            $movies =
                Http::withToken(config('services.tmdb.token'))
                    ->get('https://v4-cinemeta.strem.io/catalog/movie/top/search=' . $this->search . '.json')
                    ->json()['metas'];


            $searchResults = array_merge($movies, $series);

            shuffle($searchResults);
        }

        // dd($searchResults);

        return view('livewire.search-dropdown', [
            'searchResults' => collect($searchResults)->take(7),
        ]);
    }
}
