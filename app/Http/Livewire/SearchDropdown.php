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
            $series =
                Http::withToken(config('services.tmdb.token'))
                    ->get('https://api.themoviedb.org/3/search/tv?query=' . $this->search)
                    ->json()['results'];

            $series =
                collect($series)->map(function ($item) {
                    // $item = collect($item);
                    $item['type'] = "tv";

                    return $item;
                });

            $movies =
                Http::withToken(config('services.tmdb.token'))
                    ->get('https://api.themoviedb.org/3/search/movie?query=' . $this->search)
                    ->json()['results'];

            $movies = collect($movies)->map(function ($item, $key) {
                // $item = collect($item);
                $item['type'] = "movie";

                return $item;
            });


            $searchResults = array_merge($movies->toArray(), $series->toArray());

            shuffle($searchResults);
            // dd($searchResults);
        }

        // dd($searchResults);

        return view('livewire.search-dropdown', [
            'searchResults' => collect($searchResults)->take(7),
        ]);
    }
}
