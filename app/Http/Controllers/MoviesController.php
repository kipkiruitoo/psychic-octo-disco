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
        $popularMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/popular')
            ->json()['results'];

        // dd($popularMovies);
        $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];

        $viewModel = new MoviesViewModel(
            $popularMovies,
            $nowPlayingMovies,
            $genres,
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


        $viewModel = new MovieViewModel($movie);

        seo()->title('Watch ' . $movie['title'] . ' for free on Tea Movies');
        seo()->description($movie['overview']);

        $tbp_torrents = $this->getTorrents($movie['imdb_id']);

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
        // $baseUrl1 =
        //     'https://thepiratebay-plus.strem.fun';

        // $baseUrl2 =
        //     'https://torrentio.strem.fun';

        $baseUrl =
            'https://movies123-strem.herokuapp.com';

        $url = $baseUrl . '/stream/movie/' . $id . '.json';
        // $url1 = $baseUrl1 . '/stream/movie/' . $id . '.json';
        // $url2 = $baseUrl2 . '/stream/movie/' . $id . '.json';

        // dd($url);
        $streams =  Http::withHeaders([])->get($url)->json();

        if(isset($streams)){
            $streams = $streams['streams'];
        }else{
            $streams = [];
        }

        // $streams1 = Http::withHeaders([])->get($url1)->json()['streams'];

        // $streams2 = Http::withHeaders([])->get($url2)->json()['streams'];


        $finalStreams = array_merge(
            $streams,

            // $streams1,
            //  $streams2
        );


        return $finalStreams;
    }

    public function player($hash, $id)
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '?append_to_response=credits,videos,images')
            ->json();

        $viewModel = new MovieViewModel($movie);

        $tbp_torrents = $this->getTorrents($movie['imdb_id']);

        if ($hash == "dl") {
            $streamurl = request()->get('dlink');

            $streamurl = urldecode($streamurl);
        } else {
            $streamurl = 'https://server.teamovies.tk/' . $hash . '/0';
        }

        // dd($tbp_torrents);


        seo()->title('Watching ' . $movie['title'] . ' for free on Tea Movies');
        seo()->description($movie['overview']);
        return view('movies.player', $viewModel)->with(['streamurl' => $streamurl, "torrents" => $tbp_torrents, 'id' => $id]);
    }
}
