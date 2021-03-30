@extends('layouts.main')

@section('head')

<style>
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>
@endsection

@section('content')
<div class="tv-info border-b border-gray-800">
    <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row">
        <div class="flex-none">
            <img src="{{ $tvshow['poster_path'] }}" alt="parasite" class="w-64 lg:w-96">
        </div>
        <div class="md:ml-24">
            <h2 class="text-4xl mt-4 md:mt-0 font-semibold">{{ $tvshow['name'] }}</h2>
            <div class="flex flex-wrap items-center text-gray-400 text-sm">
                <svg class="fill-current text-orange-500 w-4" viewBox="0 0 24 24">
                    <g data-name="Layer 2">
                        <path
                            d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z"
                            data-name="star" />
                    </g>
                </svg>
                <span class="ml-1">{{ $tvshow['vote_average'] }}</span>
                <span class="mx-2">|</span>
                <span>{{ $tvshow['first_air_date'] }}</span>
                <span class="mx-2">|</span>
                <span>{{ $tvshow['genres'] }}</span>
            </div>

            <p class="text-gray-300 mt-8">
                {{ $tvshow['overview'] }}
            </p>

            <div class="bg-white mt-10">
                <h5>Seasons</h5>
                <nav class="tabs flex flex-col sm:flex-row">
                    @foreach ($seasons as $season)
                    <button data-target="panel-{{$season[0]['season']}}"
                        class="tab {{$season[0]['season'] == 1? 'active' : ''}}  text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none text-blue-500 border-b-2 font-medium border-blue-500">
                        Season {{$season[0]['season']}}
                    </button>
                    @endforeach
                </nav>
            </div>
            <div>
                <div id="panels" class="bg-white p-10">
                    @foreach ($seasons as $season)
                    <div
                        class="panel-{{$season[0]['season']}} tab-content {{$season[0]['season'] == 1? 'active' : ''}} py-5">
                        <ul>
                            @foreach ($season as $episode)
                            <li>
                                <a
                                    href="{{route('tv.episode',[$id, $episode['season'], $episode['number']] )}}">
                                    <div
                                        class="flex justify-start cursor-pointer text-white-700 hover:text-blue-400 hover:bg-blue-100 rounded-md px-2 py-2 ">
                                        {{-- <span class="bg-gray-400 h-2 w-2 m-2 rounded-full"></span> --}}
                                        <div class="text-black font-medium px-2">
                                            {{$episode['season']}}.{{$episode['number']}}</div>
                                        <div class="text-sm font-small text-gray-800 tracking-wide">{{$episode['name']}}
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach

                </div>
            </div>


        </div>
    </div>
</div> <!-- end tv-info -->

<div class="tv-cast border-b border-gray-800">
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-4xl font-semibold">Cast</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            @foreach ($tvshow['cast'] as $cast)
            <div class="mt-8">
                <a href="">
                    <img src="{{ $cast['profile_path'] }}" alt="actor1"
                        class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
                <div class="mt-2">
                    <a href="" class="text-lg mt-2 hover:text-gray:300"></a>
                    <div class="text-sm text-gray-400">
                        {{-- {{ $cast['character'] }} --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div> <!-- end tv-cast -->

<div class="tv-images" x-data="{ isOpen: false, image: ''}">
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-4xl font-semibold">Images</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach ($tvshow['images'] as $image)
            <div class="mt-8">
                <a @click.prevent="
                                isOpen = true
                                image='{{ 'https://image.tmdb.org/t/p/original/'.$image['file_path'] }}'
                            " href="#">
                    <img src="{{ 'https://image.tmdb.org/t/p/w500/'.$image['file_path'] }}" alt="image1"
                        class="hover:opacity-75 transition ease-in-out duration-150">
                </a>
            </div>
            @endforeach
        </div>

        <div style="background-color: rgba(0, 0, 0, .5);"
            class="fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto" x-show="isOpen">
            <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                <div class="bg-gray-900 rounded">
                    <div class="flex justify-end pr-4 pt-2">
                        <button @click="isOpen = false" @keydown.escape.window="isOpen = false"
                            class="text-3xl leading-none hover:text-gray-300">&times;
                        </button>
                    </div>
                    <div class="modal-body px-8 py-8">
                        <img :src="image" alt="poster">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end tv-images -->

<script>
    const tabs = document.querySelectorAll(".tabs");
    const tab = document.querySelectorAll(".tab");
    const panel = document.querySelectorAll(".tab-content");

    function onTabClick(event) {

    // deactivate existing active tabs and panel

    for (let i = 0; i < tab.length; i++) { tab[i].classList.remove("active"); } for (let i=0; i < panel.length; i++) {
        panel[i].classList.remove("active"); } // activate new tabs and panel event.target.classList.add('active'); let
        classString=event.target.getAttribute('data-target'); console.log(classString);
        document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active"); } for (let i=0; i
        < tab.length; i++) { tab[i].addEventListener('click', onTabClick, false); }
</script>
@endsection
