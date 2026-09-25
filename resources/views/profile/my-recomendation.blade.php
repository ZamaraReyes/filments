@extends('layouts.main')

@section('content')
@php
    $chunks = collect($moviesRecom)->chunk(20);
    $gridClasses = 'grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4';
@endphp
<div class="container mx-auto">
    <div class="grid grid-cols-12 gap-4 mt-20 md:mt-40">
        <div class="hidden md:block md:col-span-4 lg:col-span-3 h-full mr-5 pl-3">
            <x-menu-dashboard :user="$user"/>
        </div>
        <div class="dashboard col-span-12 md:col-span-8 lg:col-span-9 px-3 pb-20">
            <x-verify-email-banner/>
            @if(session('status'))
            <x-alert-reset/>
            @endif
            <div class="py-3 mb-4">
                <h1 class="text-2xl text-white">My recomendation</h1>
            </div>
            @if($chunks->isNotEmpty())
            <div id="infinite-list" class="{{ $gridClasses }}">
                @foreach($chunks->first() as $movie)
                <x-movie-card :movie="$movie" :genres="$genres" :favoritas-movies="$favoritasMovies" :removable="true"/>
                @endforeach
            </div>
            {{-- El resto de películas se renderiza en el servidor y se inserta al hacer scroll --}}
            @foreach($chunks->slice(1) as $chunk)
            <template class="recom-chunk">
                <div class="{{ $gridClasses }} mt-4">
                    @foreach($chunk as $movie)
                    <x-movie-card :movie="$movie" :genres="$genres" :favoritas-movies="$favoritasMovies" :removable="true"/>
                    @endforeach
                </div>
            </template>
            @endforeach
            <div id="recom-more"></div>
            @else
            <div class="grid grid-cols-1">
                <p class="text-white text-xl">We don't have recommendations for you yet. Add favorite genres, actors or movies to get some.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@if($chunks->count() > 1)
<script type="text/javascript">
  (function () {
    var chunks = Array.prototype.slice.call(document.querySelectorAll('template.recom-chunk'));
    var sentinel = document.getElementById('recom-more');
    var next = 0;

    function loadNext() {
      if (next >= chunks.length) return false;
      sentinel.parentNode.insertBefore(chunks[next].content.cloneNode(true), sentinel);
      next++;
      return next < chunks.length;
    }

    window.addEventListener('scroll', function onScroll() {
      var nearBottom = window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight - 300;
      if (nearBottom && !loadNext()) {
        window.removeEventListener('scroll', onScroll);
      }
    });
  })();
</script>
@endif
@endsection
