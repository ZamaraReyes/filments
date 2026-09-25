@php
$id_movie = $movie['id'];
@endphp
<div class="fnc-slide">
    <div class="fnc-slide__inner flex items-center h-full" style="background: linear-gradient(rgba(17,24,39,0.7) 100%, transparent, rgba(17,24,39,0.7) 100%), url({{'https://image.tmdb.org/t/p/original'.$movie['backdrop_path']}}) top center/cover no-repeat">
        <div class="container mx-auto flex items-center h-full grid sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-5 p-3">
            <div class="col-span-2">
                <p class="inline-block text-xs text-gray-200 uppercase tracking-widest">{{$movie['subtitle']}}</p>
                <a href="{{route('movies.show', $movie['id'])}}"><h2 class="text-4xl text-white mb-2">{{$movie['title']}}</h2></a>
                <p class="text-sm text-gray-100 mb-1">{{\Illuminate\Support\Str::limit($movie['overview'] ?? '',120,' ...')}}</p>
                <div>
                    @if($movie['subtitle'] == 'upcoming')
                    <i class="fa fa-calendar inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i>
                    <p class="inline-block text-xs text-gray-200">{{\Carbon\Carbon::parse($movie['release_date'])->format('M d, Y')}}</p> <p class="inline-block text-gray-200 text-xs px-2">|</p>
                    @elseif($movie['subtitle'] == 'top ranted')
                    <i class="fa fa-star inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i><p class="inline-block text-gray-200 text-xs">{{round($movie['vote_average'],2)}}</p> <p class="inline-block text-gray-200 text-xs px-2">|</p>
                    @endif
                    <i class="fa fa-video inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i>
                    @foreach($movie['genres'] as $genre)
                    <a href="{{route('genres.showGenre', $genre['id'])}}" class="inline-block text-gray-200 text-xs">{{$genre['name']}}@if(!$loop->last), @endif</a>
                    @endforeach
                </div>
                <div class="flex items-center">
                    <a href="{{route('movies.show', $movie['id'])}}" class="bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-3 mt-4 block text-center" target=_blank>
                        <i class="far fa-film"></i>
                        <span>View details</span>
                    </a>
                    <button onclick="document.getElementById('trailer_{{$id_movie}}').dispatchEvent(new CustomEvent('open-me', { detail: {}}));" class="bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-4 block text-center">
                        <i class="far fa-play-circle"></i>
                        <span>View trailer</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>