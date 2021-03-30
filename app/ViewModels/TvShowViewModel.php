<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class TvShowViewModel extends ViewModel
{
    public $tvshow;

    public function __construct($tvshow)
    {
        $this->tvshow = $tvshow;
    }

    public function tvshow()
    {
        return collect($this->tvshow)->merge([
            'poster_path' => $this->tvshow['poster']
                ? $this->tvshow['poster']
                : 'https://via.placeholder.com/500x750',
            'vote_average' => $this->tvshow['popularity'] * 10 . '%',
            'first_air_date' => Carbon::parse($this->tvshow['released'])->format('M d, Y'),
            'genres' => collect($this->tvshow['genres'])->implode(', '),
            'overview' => $this->tvshow['description'],
            'cast' => collect($this->tvshow['cast'])->take(5)->map(function ($cast) {
                return collect($cast)->merge([
                    'profile_path' => 'https://via.placeholder.com/300x450',
                ]);
            }),
            'images' => [],
        ])->only([
            'poster_path', 'id', 'genres', 'name', 'vote_average', 'overview', 'first_air_date', 'credits',
            'videos', 'images', 'crew', 'cast', 'images',  'episodes'
        ]);
    }
}
