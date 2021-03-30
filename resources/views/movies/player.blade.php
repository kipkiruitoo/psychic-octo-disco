@extends('layouts.main')
@php

$tr = $torrents["streams"];
// dd($movie)
@endphp
@section('head')

@section('title')
Watch {{$movie['title']}} on Tea Movies
@endsection
{{-- <link href="https://vjs.zencdn.net/7.11.4/video-js.css" rel="stylesheet" />
<script src="https://vjs.zencdn.net/7.11.4/video.min.js"></script> --}}
@endsection
@section('content')
<div class="mt-10 mb-10 p-4">
    <small>Click on the Player to start watching</small>
</div>
<video class="h-full w-full mr-8 p-2" src="{{$streamurl}}" controls></video>

<div class="movie-info border-b border-gray-800">
    <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
        <div class="flex-none">
            <img src="{{ $movie['poster_path'] }}" alt="poster" class="w-64 lg:w-96">
        </div>
        <div class="md:ml-24">
            <h2 class="text-4xl mt-4 md:mt-0 font-semibold">{{ $movie['title'] }}</h2>
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
                <span>{{ $movie['release_date'] }}</span>
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
                            <a href="{{route('movies.player',[$torrent['infoHash'], $id] )}}">
                                <div
                                    class="flex justify-start cursor-pointer text-white-700 hover:text-blue-400 hover:bg-blue-100 rounded-md px-2 py-2 my-2">
                                    <span class="bg-gray-400 h-2 w-2 m-2 rounded-full"></span>
                                    <div class="flex-grow font-medium px-2">{{$torrent['title']}}</div>
                                    <div class="text-sm font-normal text-gray-500 tracking-wide">{{$torrent['name']}}
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>




                </div>
            </div>

            <div class="mt-12">
                <h4 class="text-white font-semibold">Featured Crew</h4>
                <div class="flex mt-4">
                    @foreach ($movie['crew'] as $crew)
                    <div class="mr-8">
                        <div>{{ $crew['name'] }}</div>
                        <div class="text-sm text-gray-400">{{ $crew['job'] }}</div>
                    </div>

                    @endforeach
                </div>
            </div>


            <div x-data="{ isOpen: false }">
                @if (count($movie['videos']['results']) > 0)
                <div class="mt-12">
                    <button @click="isOpen = true"
                        class="flex inline-flex items-center bg-orange-500 text-gray-900 rounded font-semibold px-5 py-4 hover:bg-orange-600 transition ease-in-out duration-150">
                        <svg class="w-6 fill-current" viewBox="0 0 24 24">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M10 16.5l6-4.5-6-4.5v9zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                        </svg>
                        <span class="ml-2">Play Trailer</span>
                    </button>
                </div>

                <template x-if="isOpen">
                    <div style="background-color: rgba(0, 0, 0, .5);"
                        class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto">
                        <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                            <div class="bg-gray-900 rounded">
                                <div class="flex justify-end pr-4 pt-2">
                                    <button @click="isOpen = false" @keydown.escape.window="isOpen = false"
                                        class="text-3xl leading-none hover:text-gray-300">&times;
                                    </button>
                                </div>
                                <div class="modal-body px-8 py-8">
                                    <div class="responsive-container overflow-hidden relative"
                                        style="padding-top: 56.25%">
                                        <iframe class="responsive-iframe absolute top-0 left-0 w-full h-full"
                                            src="https://www.youtube.com/embed/{{ $movie['videos']['results'][0]['key'] }}"
                                            style="border:0;" allow="autoplay; encrypted-media"
                                            allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                @endif


            </div>

        </div>
    </div>
</div>
@endsection
