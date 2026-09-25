@extends('layouts.main')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-12 gap-4 mt-20 md:mt-40">
        <div class="hidden md:block md:col-span-4 lg:col-span-3 h-full mr-5 pl-3">
            <x-menu-dashboard :user="$user"/>
        </div>
        <div class="dashboard col-span-12 md:col-span-8 lg:col-span-9 px-3 pb-20">
            <x-verify-email-banner/>
            <div class="py-3 mb-4">
                <h1 class="text-2xl text-white">My favorites movies</h1>
            </div>
            @if(count($favoritesMovie) > 0)
            <div class="grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($favoritesMovie as $movie)
                <x-movie-favorite-card :movie="$movie" :genres="$genres" :favoritesMovie="$favoritesMovie"/>
                @endforeach
            </div>
            @else
            <div class="grid grid-cols-1">
                <p class="text-white text-xl">You don't have favorite movies</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
