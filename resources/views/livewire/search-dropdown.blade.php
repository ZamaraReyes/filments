<div class="container modal-container-search mx-auto bg-gray-900">
    <input type="text" class="w-full rounded-l-md p-3 bg-gray-800 text-white" placeholder="Search movie" wire:model.live.debounce.500ms="search"/>
    <!--<button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white uppercase text-xs py-2 px-4 rounded-r-md">Submit</button>-->
    <div wire:loading class="spinner right-20 mr-4 mt-3"></div>
    @if(strlen($search) >= 2)
	    @if(count($searchResults) > 0)
	    <ul class="h-96 overflow-y-scroll mt-2">
			@foreach($searchResults as $movie)
				@if($movie['backdrop_path'] && $movie['poster_path'])
				<li class="border-b border-gray-700 text-xs text-left initial">
					<a href="{{route('movies.show', $movie['id'])}}" class="block hover:bg-gray-800 px-3 py-3 flex items-center">
						@if($movie['poster_path'])
						<img src="{{'https://image.tmdb.org/t/p/w92'.$movie['poster_path']}}" alt="{{$movie['title']}}" class="w-8 mr-3">
						@endif
						<div>
							<p>{{$movie['title']}}</p>
							<span class="text-gray-500">{{\Carbon\Carbon::parse($movie['release_date'])->format('Y')}}</span>
						</div>
					</a>
				</li>
				@endif
			@endforeach
		</ul>
		@else
		<p class="py-3 text-xs initial mt-2">No results for <span class="text-gray-500">{{$search}}</span></p>
		@endif
	@endif
</div>