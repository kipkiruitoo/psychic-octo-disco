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
        ->get('https://api.themoviedb.org/3/tv/' . $id . '?append_to_response=external_ids,credits,videos,images')
        ->json();


        // dd($tvshow);


        $seasons = $tvshow['seasons'];

        foreach ($seasons as $key => $season) {

            $url = 'https://api.themoviedb.org/3/tv/' . $id . '/season/' . $season['season_number'] . '?append_to_response=external_ids,credits,videos,images';
            $e = Http::withToken(config('services.tmdb.token'))->get($url)
            ->json();

            $seasons[$key]["episodes"] = $e["episodes"];
        }

        // dd($seasons);

        seo()->title('Watching ' . $tvshow['name'] . ' for free on Tea Movies');
        seo()->description($tvshow['overview']);

        $viewModel = new TvShowViewModel($tvshow);

        return view('tv.show', $viewModel)->with(['seasons' => $seasons, 'id' => $id]);
    }


    public function episode($id, $season, $episode)
    {
        $tvshow = Http::withToken(config('services.tmdb.token'))
        ->get('https://api.themoviedb.org/3/tv/' . $id . '?append_to_response=external_ids,credits,videos,images')
        ->json();


        // dd($tvshow['external_ids']['imdb_id']);


        $seasons = $tvshow['seasons'];

        foreach ($seasons as $key => $season) {

            $url = 'https://api.themoviedb.org/3/tv/' . $id . '/season/' . $season['season_number'] . '?append_to_response=external_ids,credits,videos,images';
            $e = Http::withToken(config('services.tmdb.token'))->get($url)
                ->json();

            $seasons[$key]["episodes"] = $e["episodes"];
        }

        $tbp_torrents = $this->getTorrents($tvshow['external_ids']['imdb_id'], $season, $episode);

        // dd($tbp_torrents);

        $viewModel = new TvShowViewModel($tvshow);

        $streams = $tbp_torrents;

        $rand = $streams[1];

        if (isset(request()->hash)) {
            $hash = request()->hash;
        } else {
            $hash = "dl";
        }

        seo()->title('Watching ' . $tvshow['name'] . ' for free on Tea Movies');
        seo()->description($tvshow['overview']);

        if (isset(request()->dlink)) {
             $streamurl = urldecode(request()->dlink);
        }else{
            if ($hash != 'dl') {
                $streamurl =
                'https://server.teamovies.tk/' . $hash . '/0';
            }else{
                $streamurl = $rand['url'];
            }

        }


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

        $baseUrl1 =
        'https://thepiratebay-plus.strem.fun';

        $baseUrl2 =
        'https://torrentio.strem.fun';

        $baseUrl =
        'https://movies123-strem.herokuapp.com';

        $url = $baseUrl .
        '/stream/series/' . $id . urlencode(':' . $season['season_number'] . ':' . $episode) . '.json';
        $url1 = $baseUrl1 .
        '/stream/series/' . $id . urlencode(':' . $season['season_number'] . ':' . $episode) . '.json';
        $url2 = $baseUrl2 .
        '/stream/series/' . $id . urlencode(':' . $season['season_number'] . ':' . $episode) . '.json';

        $streams =  Http::withHeaders([])->get($url)->json()['streams'];

        $streams1 = Http::withHeaders([])->get($url1)->json()['streams'];

        $streams2 = Http::withHeaders([])->get($url2)->json()['streams'];


        $finalStreams = array_merge($streams, $streams1, $streams2);


        return $finalStreams;
    }
}
