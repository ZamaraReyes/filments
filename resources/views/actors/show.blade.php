@extends('layouts.main')

    @section('content')
    <div class="container mx-auto">
		<div class="text-white">
		    <div class="px-5 mt-40">
			    <div class="flex flex-row flex-wrap mb-10">
			        <div class="md:w-4/12 w-full md:pr-10">
			            <img src="{{'https://image.tmdb.org/t/p/w500'.$actor['profile_path']}}" class="w-full mb-4">
			        </div>
			        <div class="md:w-8/12 w-full">
				        <h1 class="inline-block text-3xl pr-2">{{$actor['name']}}</h1>
				        <div class="py-1">
			                <i class="fa fa-birthday-cake inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i>
			                <p class="inline-block text-sm text-gray-200">{{$birthday['birthday']}} ({{$birthday['age']}} years old)</p>
			                <p class="inline-block text-sm px-2 text-gray-200">|</p>
			                <i class="fa fa-map-marker inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i>
			                <p class="inline-block text-sm text-gray-200">{{$actor['place_of_birth']}}</p>
			                <p class="inline-block text-sm px-2 text-gray-200">|</p>
			                <i class="fa fa-star inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i>
			                <p class="inline-block text-sm text-gray-200">{{round($actor['popularity'],2)}}</p>
			                @if($actor['deathday'])
			                <p class="inline-block text-sm px-2 text-gray-200">|</p>
			                <i class="fa fa-star inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i>
			                <p class="inline-block text-sm text-gray-200">{{$actor['deathday']}}</p> 
			                @endif
			                <p class="inline-block text-sm px-2 text-gray-200">|</p> <i class="fa fa-heart inline-block text-sm text-yellow-500 pr-1" aria-hidden="true"></i> <p class="inline-block text-sm">
	                        	@if($countFav == $actor['id'])
	                        	{{$countFav->count_fav}}
	                        	@else
	                        	0
	                        	@endif
	                        likes</p>  
			            </div>
			            <div class="py-3 mt-3">
			                <p class="text-sm">{{$actor['biography']}}</p>
			            </div>
			            <div class="md:flex space-x-4 py-3">
			            	@if($actor['homepage'])
			            		<a href="{{$actor['homepage']}}" class="bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center" target=_blank><i class="fas fa-globe"></i> Website</a>
			            	@endif

			            	@if(Auth::check())
			            		@if(in_array($actor['id'], $favoriteActors))
			            		    <form action="{{route('components.dislikee', $actor['id'])}}" method="POST">
						            @csrf
						            @method('DELETE')
						            <button type="submit" @click="open = true" class="text-xs bg-transparent bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center w-full">
						                <i class="fas fa-heart-broken"></i>
						                <span>Dislike</span>
						            </button>
						        </form>
			            		@else
						        <div x-data="{ open: false }">
						            <form action="{{route('components.favoritee', $actor['id'])}}" method="POST">
						                @csrf
						                <button type="submit" @click="open = true" class="text-xs bg-transparent bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center w-full">
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
						    <a href="{{route('auth.login')}}" class="text-xs bg-transparent bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center">
					            <i class="far fa-heart"></i>
					            <span>Add favorite</span>
					        </a>
						    @endif
			            </div>
			        </div>       
			    </div>

				@if(count($knownForMovies) > 0)
			    <div class="border-t border-gray-600 py-10">
			        <h2 class="text-2xl mb-5">Known for movies</h2>
			        <div class="owl-carousel">
			       		@foreach($knownForMovies as $movie)
			       		<x-movie-card :movie="$movie" :genres="$genres" :favoritas-movies="$favoritasMovies"/>
	                    @endforeach
			        </div>
			    </div>
			    @endif
		    </div>
		</div>
	</div>
	@endsection