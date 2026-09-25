<div class="movie p-4 hover:shadow-2xl hover:bg-gray-800 rounded-lg">
    <a href="{{route('actors.show', $actor['id'])}}">
        <img src="https://image.tmdb.org/t/p/w342{{$actor['profile_path']}}" alt="{{$actor['name']}}" title="{{$actor['name']}}">
    </a>
    <a href="{{route('actors.show', $actor['id'])}}">
        <h2 class="text-white font-bold mt-4">{{$actor['name']}}</h2>
    </a>
    <p class="block text-xs text-gray-500">{{$actor['character']}}</p>
    @if(Auth::check())
        @if(collect($favoritasActors)->contains('tmdb_id', $actor['id']))
            <form action="{{route('components.dislikee', $actor['id'])}}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" @click="open = true" class="text-xs bg-transparent text-white font-400 py-2">
                <i class="fas fa-heart-broken"></i>
                <span>Dislike</span>
            </button>
        </form>
        @else
        <div x-data="{ open: false }" class="inline-block">
            <form action="{{route('components.favoritee', $actor['id'])}}" method="POST" class="inline-block">
                @csrf
                <button id="btnFavActor" type="submit" @click="open = true" class="text-xs bg-transparent text-white font-400 py-2"></i>
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
    <a href="{{route('auth.login')}}" class="text-xs bg-transparent text-white font-400 py-2">
        <i class="far fa-heart"></i>
        <span>Add favorite</span>
    </a>
    @endif
</div>