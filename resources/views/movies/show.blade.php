@extends('layouts.main')

@section('content')
<div class="container mx-auto">
    <div class="text-white">

        {{-- Fondo --}}
        @if(!empty($movie['backdrop_path']))
            <header
                class="bg-cover bg-center absolute inset-0 opacity-50 portada"
                style="background: linear-gradient(transparent, rgba(17,24,39,1) 80%), url({{'https://image.tmdb.org/t/p/original'.$movie['backdrop_path']}}) top center/cover no-repeat">
            </header>
        @endif

        <div class="px-5 mt-20 lg:mt-80 pt-20">

            <div class="flex flex-row flex-wrap mb-10">

                {{-- Poster --}}
                <div class="md:w-4/12 w-full md:pr-10">
                    @if(!empty($movie['poster_path']))
                        <img
                            src="{{'https://image.tmdb.org/t/p/w500'.$movie['poster_path']}}"
                            class="w-full mb-4">
                    @endif
                </div>

                {{-- Información --}}
                <div class="md:w-8/12 w-full">

                    <h1 class="inline-block text-3xl pr-2">
                        {{$movie['title'] ?? 'Sin título'}}
                    </h1>

                    @if(!empty($movie['release_date']))
                        <h2 class="inline-block text-2xl text-gray-500">
                            {{\Carbon\Carbon::parse($movie['release_date'])->format('Y')}}
                        </h2>
                    @endif

                    <div class="py-1">

                        {{-- Duración --}}
                        @if(isset($movie['runtime']))
                            <span class="text-gray-400">
                                {{$movie['runtime']}} min
                            </span>
                        @endif

                        {{-- Géneros --}}
                        @if(!empty($movie['genres']) && is_array($movie['genres']))
                            @foreach($movie['genres'] as $genre)
                                @if(is_array($genre) && isset($genre['id'], $genre['name']))
                                    <a
                                        href="{{route('genres.showGenre', $genre['id'])}}"
                                        class="text-gray-400 hover:text-white">

                                        {{$genre['name']}}

                                        @if(!$loop->last)
                                            ,
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        @endif

                        {{-- Valoración --}}
                        @if(isset($movie['vote_average']))
                            <span class="ml-2">
                                <i class="fa fa-star inline-block text-sm text-yellow-500 pr-1"></i> {{number_format((float)$movie['vote_average'], 1)}}
                            </span>
                        @endif

                        {{-- Favoritos --}}
                        <span class="ml-2">
                            {{$countFav ?? 0}} likes
                        </span>

                    </div>

                    {{-- Sinopsis --}}
                    <div class="py-3 mt-3">
                        <p class="text-sm">
                            {{$movie['overview'] ?? 'Sin sinopsis disponible.'}}
                        </p>
                    </div>

                    {{-- Información OMDb --}}
                    <div class="py-3 mb-3">

                        <div>
                            <h3 class="inline-block font-bold">
                                Director:
                            </h3>

                            <p class="inline-block">
                                {{$movieDetails['Director'] ?? 'N/A'}}
                            </p>
                        </div>

                        <div>
                            <h3 class="inline-block font-bold">
                                Writers:
                            </h3>

                            <p class="inline-block">
                                {{$movieDetails['Writer'] ?? 'N/A'}}
                            </p>
                        </div>

                        @if(($movieDetails['Awards'] ?? 'N/A') != 'N/A')
                            <div>
                                <h3 class="inline-block font-bold">
                                    Awards:
                                </h3>

                                <p class="inline-block">
                                    {{$movieDetails['Awards']}}
                                </p>
                            </div>
                        @endif

                        <div>
                            <h3 class="inline-block font-bold">
                                Production:
                            </h3>

                            <p class="inline-block">
                                {{$movieDetails['Production'] ?? 'N/A'}}
                            </p>
                        </div>

                        <div>
                            <h3 class="inline-block font-bold">
                                Country:
                            </h3>

                            <p class="inline-block">
                                {{$movieDetails['Country'] ?? 'N/A'}}
                            </p>
                        </div>

                    </div>

                    <div class="md:flex md:space-x-4 py-3">

                        <div class="flex gap-4">

                            {{-- Web oficial --}}
                            @if(!empty($movie['homepage']))
                                <a
                                    href="{{$movie['homepage']}}"
                                    target="_blank"
                                    class="inline-block w-1/2 md:w-auto text-center bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded mb-2">

                                    Website

                                </a>
                            @endif

                            {{-- Trailer --}}
                            @if(!empty($movieTrailer) && !empty($movieTrailer['key']))

                                <div class="w-1/2 md:w-auto" x-data="{ open: false }">

                                    <button
                                        @click="open = true"
                                        class="w-full md:w-auto bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded">

                                        Trailer

                                    </button>

                                    <div
                                        x-show="open"
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
                                        style="display: none;">

                                        <div class="relative w-full max-w-4xl p-4">

                                            <button
                                                @click="open = false"
                                                class="absolute right-2 top-2 z-10 text-white text-2xl">

                                                &times;

                                            </button>

                                            <div class="aspect-w-16 aspect-h-9">

                                                <iframe
                                                    class="w-full h-96"
                                                    src="{{'https://www.youtube.com/embed/'.$movieTrailer['key']}}"
                                                    title="{{$movieTrailer['name'] ?? 'Trailer'}}"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen>
                                                </iframe>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                        {{-- Favoritos --}}
                        @if(Auth::check())

                            @if(collect($favoritasMovies)->contains('tmdb_id', $movie['id']))
                                <form
                                        action="{{route('components.dislike', $movie['id'])}}"
                                        method="POST">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-xs bg-transparent bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a block text-center w-full">

                                            <i class="fas fa-heart-broken text-white"></i> Remove from favorites

                                        </button>

                                    </form>
                            @else

                                <form
                                    action="{{route('components.favorite', $movie['id'])}}"
                                    method="POST">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="inline-block bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded mb-2 md:mb-0">

                                        <i class="fas fa-heart text-white"></i> Add to favorites

                                    </button>

                                </form>

                            @endif

                        @else

                            <a
                                href="{{route('login')}}"
                                class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded">

                                Login to add favorites

                            </a>

                        @endif

                    </div>

                </div>
            </div>

            {{-- Actores --}}
            <div class="border-t border-gray-600 py-10">

                <h2 class="text-2xl mb-5">
                    Cast
                </h2>

                <div class="owl-carousel">

                    @foreach($movieActors ?? [] as $actor)

                        <x-actor-card
                            :actor="$actor"
                            :genres="$genres"
                            :favoritas-actors="$favoritasActors"
                        />

                    @endforeach

                </div>

            </div>

            {{-- Películas similares --}}
            @if(!empty($allMovieSimilar))

                <div class="border-t border-gray-600 py-10">

                    <h2 class="text-2xl mb-5">
                        You may be interested
                    </h2>

                    <div class="owl-carousel">

                        @foreach($allMovieSimilar as $similarMovie)

                            <x-movie-card
                                :movie="$similarMovie"
                                :genres="$genres"
                                :favoritas-movies="$favoritasMovies"
                            />

                        @endforeach

                    </div>

                </div>

            @endif

        </div>
    </div>
</div>
@endsection