<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class MoviesViewModel extends ViewModel
{
    public $popularMovies;
    public $nowPlayingMovies;
    public $genres;

    public function __construct(
        $popularMovies
        // , $nowPlayingMovies, $genres
    ) {
        $this->popularMovies = $popularMovies;
        // $this->nowPlayingMovies = $nowPlayingMovies;
        // $this->genres = $genres;
    }

    public function popularMovies()
    {
        return $this->formatMovies($this->popularMovies);
    }

    public function nowPlayingMovies()
    {
        return $this->formatMovies($this->nowPlayingMovies);
    }

    // public function genres()
    // {
    //     return collect($this->genres)->mapWithKeys(function ($genre) {
    //         return [$genre['id'] => $genre['name']];
    //     });
    // }

    private function formatMovies($movies)
    {
        return collect($movies)->map(function ($movie) {
            $genresFormatted = collect($movie['genre'])->implode(', ');

            return collect($movie)->merge([
                'poster_path' => $movie['poster'],
                'title' => $movie['name'],
                'vote_average' => round($movie['popularity'] ) . '%' ,
                'release_date' => Carbon::parse($movie['released'])->format('M d, Y'),
                'genres' => $genresFormatted,
            ])->only([
                'poster_path', 'id', 'genre_ids', 'title', 'vote_average', 'overview', 'release_date', 'genres',
            ]);
        });
    }
}
