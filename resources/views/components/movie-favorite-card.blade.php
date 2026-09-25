<div class="p-4 hover:shadow-2xl hover:bg-gray-800 rounded-lg">
    <a href="{{route('movies.show', $movie->tmdb_id)}}">
        <img src="{{'https://image.tmdb.org/t/p/w342'.$movie->poster_path}}" alt="{{$movie->title}}" title="{{$movie->title}}">
    </a>
    <a href="{{route('movies.show', $movie->tmdb_id)}}">
        <h2 class="text-white font-bold mt-4">{{$movie->title}}</h2>
    </a>
    <i class="far fa-star fas text-yellow-600 inline-block text-xs mr-1"></i><p class="inline-block text-white text-xs">{{round($movie->vote_average,2)}}</p>
    <div class="inline-block">
        <form action="{{route('components.dislike', $movie->tmdb_id)}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" @click="open = true" class="text-xs bg-transparent text-white font-400 py-2 px-4 rounded-full">
                <i class="fas fa-heart-broken"></i>
                <span>Dislike</span>
            </button>
        </form>
    </div>
</div>