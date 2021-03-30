<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ViewModels\MovieViewModel;
use App\ViewModels\MoviesViewModel;
use Illuminate\Support\Facades\Http;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nowPlayingMoviesurl = "https://cinemeta.strem.io/stremioget/stremio/v1/q.json?b=eyJwYXJhbXMiOltudWxsLHsicXVlcnkiOnsidHlwZSI6Im1vdmllIn0sImxpbWl0Ijo3MH1dLCJtZXRob2QiOiJtZXRhLmZpbmQiLCJpZCI6MSwianNvbnJwYyI6IjIuMCJ9";

        $popularMoviesurl = "";
        // dd(Http::get($stremio_init_url)->json());
        $popularMovies = Http::withToken(config('services.tmdb.token'))
            ->get($nowPlayingMoviesurl)
            ->json()['result'];

        // dd($popularMovies);

        // $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
        //     ->get('https://api.themoviedb.org/3/movie/now_playing')
        //     ->json()['results'];

        // $genres = Http::withToken(config('services.tmdb.token'))
        //     ->get('https://api.themoviedb.org/3/genre/movie/list')
        //     ->json()['genres'];

        $viewModel = new MoviesViewModel(
            $popularMovies

        );

        return view('movies.index', $viewModel);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '?append_to_response=credits,videos,images')
            ->json();

        // dd($movie);
        $viewModel = new MovieViewModel($movie);

        $tbp_torrents = $this->getTorrents($id);

        return view('movies.show', $viewModel)->with(["torrents" => $tbp_torrents, 'id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTorrents($id)
    {
        $baseUrl =
            'https://thepiratebay-plus.strem.fun';

        $url = $baseUrl . '/stream/movie/' . $id . '.json';

        $streams =  Http::withHeaders([])->get($url)->json();

        // dd($request);

        return $streams;
    }

    public function player($hash, $id)
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '?append_to_response=credits,videos,images')
            ->json();

        $viewModel = new MovieViewModel($movie);

        $tbp_torrents = $this->getTorrents($id);

        $streamurl = 'https://server.teamovies.tk/' . $hash . '/0';

        // dd($streamurl);
        return view('movies.player', $viewModel)->with(['streamurl' => $streamurl, "torrents" => $tbp_torrents, 'id' => $id]);
    }
}
