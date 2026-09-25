@extends('layouts.main')

@section('content')

@if(session('status') == 'We\'ve sent you an email to reset your password.')
<x-toast/>
@elseif(session('status'))
<x-alert-reset/>
@endif

@if(session('justRegistered'))
<x-verify-email-toast/>
@endif

<div class="demo-cont">
  <div class="fnc-slider example-slider">
    <div class="fnc-slider__slides">
      @foreach($movieSlider as $movie)
      <x-movie-slider :movie="$movie" :genres="$genres" :movies-trailer="$moviesTrailer" :playing-movies="$playingMovies"/>
      @endforeach
    </div>
    <nav class="fnc-nav">
      <div class="fnc-nav__bgs">
        <div class="fnc-nav__bg m--navbg-green m--active-nav-bg"></div>
        <div class="fnc-nav__bg m--navbg-dark"></div>
        <div class="fnc-nav__bg m--navbg-red"></div>
        <div class="fnc-nav__bg m--navbg-blue"></div>
      </div>
      <div class="fnc-nav__controls">
        <button class="fnc-nav__control">
          <span class="fnc-nav__control-progress"></span>
        </button>
        <button class="fnc-nav__control">
          <span class="fnc-nav__control-progress"></span>
        </button>
        <button class="fnc-nav__control">
          <span class="fnc-nav__control-progress"></span>
        </button>
        <button class="fnc-nav__control">
          <span class="fnc-nav__control-progress"></span>
        </button>
      </div>
    </nav>
  </div>
</div>

@foreach($movieSlider as $movie)
@foreach($moviesTrailer as $trailer)
@if($movie['id'] == $trailer['id_movie'])
<x-trailer :movie="$movie" :trailer="$trailer"/>      
@endif
@endforeach
@endforeach

<div class="container mx-auto pb-2 overflow-hidden p-3">
  <!--@if((Auth::check()) && ((Auth::user()->email_verified_at) != null))
  @if($countGenre)
  <div class="grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 pt-20">
    @foreach($countGenre as $genre)
    <x-genre-card :genre="$genre"/>
    @endforeach
  </div>
  @endif
  @endif-->
  <div class="border-b border-gray-600 p-3 mb-4 pt-20">
    <h1 class="text-2xl text-white">Now playing</h1>
  </div>
  <div id="infinite-list" class="grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($playingMovies as $movie)
    <x-movie-card :movie="$movie" :genres="$genres" :favoritas-movies="$favoritasMovies"/>
    @endforeach
  </div>
  <div id="loading-scroll" class="flex justify-center fixed left-0 bottom-0 h-screen w-screen">
    <div class="spinner my-20 text-4xl fixed bottom-0">&nbsp;</div>
  </div>
  <div class="list grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
@endsection