@extends('layouts.main')

@section('content')
<div class="container mx-auto pb-2 overflow-hidden">
	<header class="bg-cover bg-center absolute inset-0 opacity-50 portada" style="background: linear-gradient(transparent, rgba(17,24,39,1) 80%),url({{'https://image.tmdb.org/t/p/original'.$genresMovies[0]['backdrop_path']}}) top center/cover no-repeat"></header>
    <div class="list-group mt-80 pt-20" id="infinite-list">
        <div class="container mx-auto">
            <div class="border-b border-gray-600 py-3 mb-4">
                <h1 class="text-2xl text-white">{{$genreName}}</h1>
            </div>
            <div class="grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($genresMovies as $movie)
                <x-movie-card :movie="$movie" :genres="$genres" :favoritas-movies="$favoritasMovies"/>
                @endforeach
            </div>
        </div>
    </div>
</div>    
@endsection