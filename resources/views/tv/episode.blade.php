@extends('layouts.main')
@php

$tr = $torrents;

$movie = $tvshow;
// dd($movie);
@endphp


@section('head')
<meta name="keywords"
    content="{{$movie['name']}}, tea movies, netflix movies, 123 movies,  free movies, marvel movies in order, bmovies" />
<link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
<link href="https://unpkg.com/@videojs/themes@1/dist/city/index.css" rel="stylesheet" />
<script src="https://unpkg.com/video.js/dist/video.js"></script>
@endsection


@section('content')
<div class="mt-10 mb-10 p-4">
    <h1 class="text-4xl mt-4 md:mt-0 font-semibold">{{ $movie['name'] }}</h1>
    <small>Watching Season {{$season['season_number']}} episode {{$episode}}</small>
    <br>
    <hr>
    <a href="{{route('tv.show', [$tvshow['id']])}}"><button
            class="bg-transparent mt-2 hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            < Back to Episodes</button> </a> </div> <div class=" justify-center m-8 p-8">
                <video
                    class="h-full max-h-32 w-full min-w-full max-w-screen-xl min-w-screen-md  vjs-default-skin video-js vjs-fluid "
                    id="video_player" controls>
                    <source src="{{$streamurl}}" type="application/x-mpegURL">
                </video>
</div>
<div class="movie-info border-b border-gray-800">
    <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
        <div class="flex-none">
            <img src="{{ $movie['poster_path'] }}" alt="poster" class="w-64 lg:w-96">
        </div>
        <div class="md:ml-24">
            <h2 class="text-4xl mt-4 md:mt-0 font-semibold">{{ $movie['name'] }}</h2>
            <div class="flex flex-wrap items-center text-gray-400 text-sm">
                <svg class="fill-current text-orange-500 w-4" viewBox="0 0 24 24">
                    <g data-name="Layer 2">
                        <path
                            d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z"
                            data-name="star" />
                    </g>
                </svg>
                <span class="ml-1">{{ $movie['vote_average'] }}</span>
                <span class="mx-2">|</span>
                <span>{{ $movie['first_air_date'] }}</span>
                <span class="mx-2">|</span>
                <span>{{ $movie['genres'] }}</span>
            </div>

            <p class="text-gray-300 mt-8">
                {{ $movie['overview'] }}
            </p>
            <div class="mt-12">
                <h4 class="text-white font-semibold">Servers</h4>
                <small>click on any of the servers to change streaming server or download</small>
                <div class="flex py-3 text-sm mt-4">
                    <ul>
                        @foreach ($tr as $torrent)
                        <li>
                            @if (isset($torrent['url']))

                            <a
                                href="{{route('tv.episode',[$id,$season['season_number'], $episode] )}}?hash=dl&dlink={{urlencode($torrent['url'])}}">
                                <div
                                    class="flex justify-start cursor-pointer text-white-700 hover:text-blue-400 hover:bg-blue-100 rounded-md px-2 py-2 my-2">
                                    <span class="bg-gray-400 h-2 w-2 m-2 rounded-full"></span>
                                    <div class="flex-grow font-medium px-2">{{$torrent['title']}}</div>
                                    <div class="text-sm font-normal text-gray-500 tracking-wide">
                                        {{$torrent['name'] ?? ''}}
                                    </div>
                                </div>
                            </a>
                            @else
                            <a
                                href="{{route('tv.episode',[$id,$season['season_number'], $episode] )}}?hash={{$torrent['infoHash']}}">
                                <div
                                    class="flex justify-start cursor-pointer text-white-700 hover:text-blue-400 hover:bg-blue-100 rounded-md px-2 py-2 my-2">
                                    <span class="bg-gray-400 h-2 w-2 m-2 rounded-full"></span>
                                    <div class="flex-grow font-medium px-2">{{$torrent['title']}}</div>
                                    <div class="text-sm font-normal text-gray-500 tracking-wide">
                                        {{$torrent['name'] ?? ''}}
                                    </div>
                                </div>
                            </a>
                            @endif

                        </li>
                        @endforeach
                    </ul>




                </div>
            </div>

            <div class="mt-12">
                <h4 class="text-white font-semibold">Featured Crew</h4>
                <div class="flex mt-4">
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    var player = videojs('video_player');
    player.play();
</script>
@endsection
