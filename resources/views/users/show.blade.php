@extends('layouts.main')

    @section('content')
	<div class="container mx-auto">
		<div class="text-white mt-40 px-5">
			<div class="mb-5 flex items-center justify-between">
				<div>
					<h1 class="inline-block text-3xl pr-2">Details user</h1> <h2 class="inline-block text-2xl text-gray-500">{{$user[0]->id}}</h2>
				</div>
				<button onclick="document.getElementById('user_{{$user[0]->id}}').dispatchEvent(new CustomEvent('open-me', { detail: {}}));" class="text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-md text-sm font-400 m-a block text-center"><span>Edit profile</span></button>
			</div>
			<div class="flex flex-row flex-wrap border-t border-gray-600 py-10">
				<div class="md:w-4/12 w-full mb-3">
					<h3>Name</h3>
					<p class="text-gray-500 font-bold">{{$user[0]->name}}</p>
				</div>
				<div class="md:w-4/12 w-full mb-3">
					<h3>E-Mail Address</h3>
					<p class="text-gray-500 font-bold">{{$user[0]->email}}</p>
				</div>
				<div class="md:w-4/12 w-full mb-3 md:mb-0">
					<h3>Favorites Genres</h3>
					@foreach($genres as $key => $genre)
		                @foreach($favoritasGenres as $genres)
						@if($key == $genres->genre_id)
						<p class="text-gray-500 font-bold inline-block">{{$genre}}@if(!$loop->last), @endif</p>
		                @endif
						@endforeach
		            @endforeach
				</div>
				<div class="md:w-4/12 w-full mb-3 md:mb-0">
					<h3>Created</h3>
					<p class="text-gray-500 font-bold">{{$user[0]->created_at}}</p>
				</div>
				<div class="md:w-4/12 w-full mb-3 md:mb-0">
					<h3>Updated</h3>
					<p class="text-gray-500 font-bold">{{$user[0]->updated_at}}</p>
				</div>
			</div>
			<div class="border-t border-gray-600 py-10">
				<h2 class="text-2xl mb-5">Genres Favorites Movies</h2>
				@if(count($allCountGenres) > 0)
				<div class="flex flex-row flex-wrap">
					@foreach($allCountGenres as $genre => $key)
					<div class="md:w-2/12 w-full mb-3 flex md:inline justify-between">
						<h3>{{$genre}}</h3><p class="text-gray-500 font-bold inline-block">{{$key}}</p>
					</div>
					@endforeach
				</div>
				@else
				<div class="grid grid-cols-1">
	                <p class="text-white text-xl">The user don't have favorite movies</p>
	            </div>
				@endif
			</div>
			<div class="border-t border-gray-600 py-10">
				<h2 class="text-2xl mb-5 inline-block pr-2">Favorites Actors</h2>
				@if(count($favoritesActor) > 0)
				<p class="text-gray-500 font-bold inline-block text-xl">{{count($favoritesActor)}}</p>
				<div class="owl-carousel">
					@foreach($favoritesActor as $actor)
					<x-actor-favorite-card :actor="$actor" :genres="$genres" :favoritesActor="$favoritesActor"/>
					@endforeach
				</div>
	            @else
	            <div class="grid grid-cols-1">
	                <p class="text-white text-xl">The user don't have favorite actors</p>
	            </div>
	            @endif
			</div>
			<div class="border-t border-gray-600 pt-10">
				<h2 class="text-2xl mb-5 inline-block pr-2">Favorites Movies</h2>
				@if(count($favoritesMovie) > 0)
				<p class="text-gray-500 font-bold inline-block text-xl">{{count($favoritesMovie)}}</p>
				<div class="owl-carousel">
					@foreach($favoritesMovie as $movie)
					<x-movie-favorite-card :movie="$movie" :genres="$genres" :favoritesMovie="$favoritesMovie"/>
					@endforeach
				</div>
	            @else
	            <div class="grid grid-cols-1">
	                <p class="text-white text-xl">The user don't have favorite movies</p>
	            </div>
	            @endif
			</div>
		</div>					
	</div>
	<x-alert-change :user="$user" :allgenres="$allgenres" :favoritasGenres="$favoritasGenres"/>
@endsection