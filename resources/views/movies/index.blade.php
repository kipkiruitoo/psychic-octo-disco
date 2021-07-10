@extends('layouts.main')
@section('head')

@endsection
@section('content')
<div class="container mx-auto px-4 pt-16">
    <div class="popular-movies">
        <h1 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Popular and Trending Movies</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach ($popularMovies as $movie)
            <x-movie-card :movie="$movie" />
            @endforeach

        </div>
    </div> <!-- end pouplar-movies -->

    <div class="now-playing-movies py-24">
        <h1 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Now Playing</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach ($nowPlayingMovies as $movie)
            <x-movie-card :movie="$movie" />
            @endforeach
        </div>
    </div> <!-- end now-playing-movies -->
</div>
@endsection
