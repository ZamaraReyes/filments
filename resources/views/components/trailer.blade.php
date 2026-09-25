<div id="trailer_{{$movie['id']}}" x-data="{ open: false }" @open-me="open = true">
    <div x-show="open" class="fixed w-full h-screen top-0 left-0 flex items-center justify-center z-50" x-show.transition.opacity="open" @click.away="open = false">
        <div class="absolute w-full h-full bg-gray-900 opacity-90"></div>
        <div class="w-full h-full mx-auto z-50 overflow-y-auto modal-efecto">            
            <div class="absolute p-10 top-0 right-0">                 
                <button @click="open = false">
                    <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                    </svg>
                </button>
            </div>
            <div class="h-full mx-auto flex items-center justify-center">
                <div class="w-full md:w-6/12 mx-5">
                    <h2 class="text-2xl text-white mb-4">Trailer</h2>
                    @if(isset($trailer['key']))
                    <iframe width="100%" height="450" src="{{'https://www.youtube.com/embed/'.$trailer['key']}}" title="{{$trailer['name']}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>