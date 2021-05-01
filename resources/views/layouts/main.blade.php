<!DOCTYPE html>
<html lang="en">

<head>
    {{-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
    <link rel="stylesheet" href="/css/main.css">
    <meta name="keywords" content="action thriller movies,
action thriller,
action thriller movies 2019,
action thriller movies 2020,
thriller drama movies,
adventure thriller movies,
drama thriller,
2020 crime movies,
action and thriller movies,
crime action thriller movies,
thriller action movies 2019,
thriller 2021,
thriller action movies 2020,
crime thriller action movies,
2020 action thriller movies,
action thriller 2020,
action thriller 2019,
thriller sci fi,
2019 action thriller movies,
action thriller 2020 movies,
action thriller films,
action adventure thriller movies,
thriller drama movies 2019,
action thriller books,
mystery action thriller movies,
tea movies, netflix movies, 123 movies,  free movies, marvel movies in order, bmovies
" />
    {{ seo()->render() }}
    @yield('head')
    <livewire:styles>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
{{--
        <script data-ad-client="ca-pub-8350234676394186" async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> --}}


</head>

<body class="font-sans bg-gray-900 text-white">
    <nav class="border-b border-gray-800">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between px-4 py-6">
            <ul class="flex flex-col md:flex-row items-center">
                <li>
                    <a href="{{ route('movies.index') }}">
                        <img src="{{asset('logo.png')}}" alt="" width="150">
                    </a>
                </li>
                <li class="md:ml-16 mt-3 md:mt-0">
                    <a href="{{ route('movies.index') }}" class="hover:text-gray-300">Movies</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="{{ route('tv.index') }}" class="hover:text-gray-300">TV Shows</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="{{ route('actors.index') }}" class="hover:text-gray-300">Actors</a>
                </li>
            </ul>
            <div class="flex flex-col md:flex-row items-center">
                <livewire:search-dropdown>
                    <div class="md:ml-4 mt-3 md:mt-0">
                        {{-- <a href="#">
                        <img src="/img/avatar.jpg" alt="avatar" class="rounded-full w-8 h-8">
                    </a> --}}
                    </div>
            </div>
        </div>
    </nav>
    @yield('content')
    {{ seo('body')->render() }}
    <footer class="border border-t border-gray-800">
        <div class="container mx-auto text-sm px-4 py-6">
            Powered by <a href="https://www.stremio.com/" class="underline hover:text-gray-300">Stremio</a>
        </div>
    </footer>
    <livewire:scripts>
        @yield('scripts')
        {{-- <script type="text/javascript" data-adel="atag" src="//achcdn.com/script/atg.js" czid="5wgi4kin"></script> --}}
</body>

</html>
