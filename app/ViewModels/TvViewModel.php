<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class TvViewModel extends ViewModel
{
    public $popularTv;
    public $topRatedTv;
    public $genres;

    public function __construct($popularTv)
    {
        $this->popularTv = $popularTv;
    }

    public function popularTv()
    {
        return $this->formatTv($this->popularTv);
    }


    private function formatTv($tv)
    {
        return collect($tv)->map(function ($tvshow) {
            $genresFormatted = collect($tvshow['genre'])->implode(', ');

            return collect($tvshow)->merge([
                'poster_path' =>  $tvshow['poster'],
                'vote_average' => $tvshow['popularity'] * 10 . '%',
                'first_air_date' => Carbon::parse($tvshow['released'])->format('M d, Y'),
                'genres' => $genresFormatted,
            ])->only([
                'poster_path', 'id', 'genre_ids', 'name', 'vote_average', 'overview', 'first_air_date', 'genres',
            ]);
        });
    }
}
