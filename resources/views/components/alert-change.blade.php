<div id="user_{{$user[0]->id}}" x-data="{ open: false }" @open-me="open = true">
	<div x-show="open" class="fixed w-full h-full top-0 flex items-center justify-center z-20" x-show.transition.opacity="open" @click.away="open = false">
	    <div class="absolute w-full h-full bg-gray-900 opacity-90"></div>
	    <div class="w-full h-full mx-auto z-50 overflow-y-auto modal-efecto">
		    <div class="absolute p-10 top-0 right-0">                 
	            <button @click="open = false">
	                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
	                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
	                </svg>
	            </button>
	        </div>
	        <div class="w-full h-full mx-auto">
	        	<div class="container mx-auto xs:h-full sm:h-screen flex items-center justify-center pb-10 pt-20 md:pb-0 md:pt-0">
	                <div class="w-full sm:w-8/12 lg:w-6/12 p-3">
		                <form action="{{route('components.alert-change',$user[0]->id)}}" method="POST">
							@csrf
							{{ method_field('PATCH') }}
							<input id="user_id" type="hidden" name="id" value="{{$user[0]->id}}">
				            <div class="mt-4">
				                <label for="name" class="text-sm text-white">Name</label>
				                <div class="mt-1">
				                    <input id="name" type="text" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="name" value="{{$user[0]->name}}" required autocomplete="name" autofocus>
				                </div>
				            </div>
				            <div class="mt-4">
				                <label for="email" class="text-sm text-white">E-Mail Address</label>
				                <div class="mt-1">
				                    <input id="email" type="email" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="email" value="{{$user[0]->email}}" required autocomplete="email">
				                </div>
				            </div>
				            <div class="mt-4">
				                <label for="genres" class="text-sm text-white">What are your favorite genres?</label>
				                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xs:gap-0 sm:gap-1 mt-1 border-2 border-gray-600 rounded-md p-4">
				                @foreach($allgenres as $key => $genre)
				                    <div class="col-span-1 block mt-1 leading-snug">
				                        <input type="checkbox" id="{{$genre}}" name="genres[]" value="{{$key}}" @foreach($favoritasGenres as $genres) @if($key == $genres->genre_id) checked
                            		@endif @endforeach>
				                        <label for="{{$genre}}" class="text-sm ml-1 text-white">{{$genre}}</label>
				                    </div>
				                @endforeach
				                </div>
				            </div>
				            <div class="mt-5">
				                <button type="submit" class="w-full text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a block text-center">Save</button>
				            </div>
						</form>
		            </div>
		    	</div>
		    </div>
	    </div>
	</div>
</div>