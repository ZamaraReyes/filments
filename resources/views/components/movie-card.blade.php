<div class="movie p-4 hover:shadow-2xl hover:bg-gray-800 rounded-lg relative">
    @if(! empty($removable))
    <form action="{{route('components.remove', $movie['id'])}}" method="POST" class="remove inline-block absolute w-full text-right right-0 top-0">
        @csrf
        @method('DELETE')
        <button type="submit" @click="open = true" class="text-xs bg-red-500 hover:bg-red-600 text-white font-400 py-2 px-4 rounded-full w-10 h-10 p-10">
            <span><i class="fas fa-times text-white text-lg"></i></span>
        </button>
    </form>
    @endif
    <a href="{{route('movies.show', $movie['id'])}}">
        <img src="{{'https://image.tmdb.org/t/p/w342'.$movie['poster_path']}}" alt="@if(isset($movie['title'])){{$movie['title']}}@else{{$movie['name']}}@endif" title="@if(isset($movie['title'])){{$movie['title']}}@else{{$movie['name']}}@endif">
    </a>
    <a href="{{route('movies.show', $movie['id'])}}">
        @if(isset($movie['title']))
        <h2 class="text-white font-bold mt-4">{{$movie['title']}}</h2>
        @else
        <h2 class="text-white font-bold mt-4">{{$movie['name']}}</h2>
        @endif
    </a>
    <div class="leading-4">
        @if(isset($movie['genre_ids']))
            @if(Auth::check())
                @foreach($movie['genre_ids'] as $genre)
                <a href="{{route('genres.showGenre', $genre)}}" class="inline-block text-xs text-gray-500">{{$genres->get($genre)}}@if(!$loop->last), @endif</a>
                @endforeach
            @else
                @foreach($movie['genre_ids'] as $genre)
                <a href="{{route('auth.login')}}" class="inline-block text-xs text-gray-500">{{$genres->get($genre)}}@if(!$loop->last), @endif</a>
                @endforeach
            @endif
        @else
            @if(Auth::check())
                @foreach($movie['genres'] as $genre)
                <a href="{{route('genres.showGenre', $genre)}}" class="inline-block text-xs text-gray-500">{{$genres->get($genre['id'])}}@if(!$loop->last), @endif</a>
                @endforeach
            @else
                @foreach($movie['genres'] as $genre)
                <a href="{{route('auth.login')}}" class="inline-block text-xs text-gray-500">{{$genres->get($genre['id'])}}@if(!$loop->last), @endif</a>
                @endforeach
            @endif
        @endif
    </div>
    <i class="far fa-star fas text-yellow-600 inline-block text-xs mr-1"></i><p class="inline-block text-white text-xs">{{round($movie['vote_average'],1)}}</p>
    @if(Auth::check())
        @if(collect($favoritasMovies)->contains('tmdb_id', $movie['id']))
        <form action="{{route('components.dislike', $movie['id'])}}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" @click="open = true" class="text-xs bg-transparent text-white font-400 py-2 px-4">
                <i class="fas fa-heart-broken"></i>
                <span>Dislike</span>
            </button>
        </form>
        @else
        <div x-data="{ open: false }" class="inline-block">
            <form action="{{route('components.favorite', $movie['id'])}}" method="POST" class="inline-block">
                @csrf
                <button id="btnFavMovie" type="submit" @click="open = true" class="text-xs bg-transparent text-white font-400 py-2 px-4">
                    <i class="far fa-heart"></i>
                    <span>Add favorite</span>
                </button>
            </form>
            <div class="movieFav-wrapper" x-show.transition.opacity="open" @click.away="open = false">
                <div class="movieFav"></div>
            </div>
        </div>
        @endif
    @else
    <a href="{{route('auth.login')}}" class="text-xs bg-transparent text-white font-400 py-2 px-4">
        <i class="far fa-heart"></i>
        <span>Add favorite</span>
    </a>
    @endif
</div>