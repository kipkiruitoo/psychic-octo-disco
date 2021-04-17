<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ViewModels\TvViewModel;
use App\ViewModels\TvShowViewModel;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = 'https://api.themoviedb.org/3/tv/popular';
        $popularTv = Http::withToken(config('services.tmdb.token'))
            ->get($url)
            ->json()['results'];

        $topRatedTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/top_rated')
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

        $viewModel = new TvViewModel(
            $popularTv,
            $topRatedTv,
            $genres,
        );


        return view('tv.index', $viewModel);
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

        $tvshow = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/' . $id . '?append_to_response=credits,videos,images')
        ->json();


        // dd($tvshow);


        $seasons = $tvshow['seasons'];

        foreach ($seasons as $key => $season) {

            $url = 'https://api.themoviedb.org/3/tv/' . $id . '/season/' . $season['season_number'] . '?append_to_response=credits,videos,images';
            $e = Http::withToken(config('services.tmdb.token'))->get($url)
            ->json();

            $seasons[$key]["episodes"] = $e["episodes"];
        }

        // dd($seasons);

        seo()->title('Watching ' . $tvshow['name'] . ' for free on Tea Movies');
        // seo()->description($tvshow['description']);

        $viewModel = new TvShowViewModel($tvshow);

        return view('tv.show', $viewModel)->with(['seasons' => $seasons, 'id' => $id]);
    }


    public function episode($id, $season, $episode)
    {
        $tvshow = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/' . $id . '?append_to_response=credits,videos,images')
        ->json();


        // dd($tvshow);


        $seasons = $tvshow['seasons'];

        foreach ($seasons as $key => $season) {

            $url = 'https://api.themoviedb.org/3/tv/' . $id . '/season/' . $season['season_number'] . '?append_to_response=credits,videos,images';
            $e = Http::withToken(config('services.tmdb.token'))->get($url)
                ->json();

            $seasons[$key]["episodes"] = $e["episodes"];
        }

        $tbp_torrents = $this->getTorrents($id, $season['season_number'], $episode);

        // dd($tbp_torrents);

        $viewModel = new TvShowViewModel($tvshow);

        $streams = $tbp_torrents['streams'];

        $rand = $streams[array_rand($streams, 1)];

        if (isset(request()->hash)) {
            $hash = request()->hash;
        } else {
            $hash = $rand['infoHash'];
        }

        seo()->title('Watching ' . $tvshow['name'] . ' for free on Tea Movies');
        seo()->description($tvshow['description']);

        $streamurl =
            'https://server.teamovies.tk/' . $hash . '/0';

        return view('tv.episode', $viewModel)->with(["torrents" => $tbp_torrents, 'id' => $id, 'streamurl' => $streamurl, 'season' => $season, 'episode' => $episode]);
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

    public function getTorrents($id, $season, $episode)
    {
        // dd($season);
        $baseUrl =
            'https://thepiratebay-plus.strem.fun';

        $url = $baseUrl . '/stream/series/' . $id . urlencode(':' . $season . ':' . $episode) . '.json';

        $streams =  Http::withHeaders([])->get($url)->json();

        return $streams;
    }
}
