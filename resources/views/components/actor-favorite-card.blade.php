<div class="movie p-4 hover:shadow-2xl hover:bg-gray-800 rounded-lg">
    <a href="{{route('actors.show', $actor->tmdb_id)}}">
        <img src="https://image.tmdb.org/t/p/w342{{$actor->profile_path}}" alt="{{$actor->name}}" title="{{$actor->name}}">
    </a>
    <a href="{{route('actors.show', $actor->tmdb_id)}}">
        <h2 class="text-white font-bold mt-4">{{$actor->name}}</h2>
    </a>
    <div class="inline-block">
        <form action="{{route('components.dislikee', $actor->tmdb_id)}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" @click="open = true" class="text-xs bg-transparent text-white font-400 py-2 rounded-full">
                <i class="fas fa-heart-broken"></i>
                <span>Dislike</span>
            </button>
        </form>
    </div>
</div>