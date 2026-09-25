<a href="{{route('genres.showGenre', $genre['id'])}}" style="background: linear-gradient(transparent, rgba(17,24,39,0.8) 80%),url({{'https://image.tmdb.org/t/p/original'.$genre['backdrop_path']}}) top center/cover no-repeat" class="hover:shadow-2xl h-40 flex items-center justify-center rounded-xl">
  	<h1 class="text-2xl text-white">{{$genre['name']}}</h1>
</a>