<div id="user_{{$user->id}}" x-data="{ open: false }" @open-me="open = true">
	<div x-show="open" class="fixed w-full h-full top-0 flex items-center justify-center z-20" x-show.transition.opacity="open" @click.away="open = false">
	    <div class="absolute w-full h-full bg-gray-900 opacity-90"></div>
	    <div class="xl:w-5/12 h-4/12 mx-auto z-50 bg-white absolute rounded-2xl flex items-center justify-center shadow-2xl p-4 sm:p-8">
	        <div class="container">
	            <div class="w-full">
	                <div class="f-modal-alert">
	                    <div class="f-modal-icon f-modal-error animate">
							<span class="f-modal-x-mark">
								<span class="f-modal-line f-modal-left animateXLeft"></span>
								<span class="f-modal-line f-modal-right animateXRight"></span>
							</span>
							<div class="f-modal-placeholder"></div>
							<div class="f-modal-fix"></div>
						</div>
	                </div>
	                <h2 class="text-2xl sm:text-3xl text-gray-800 text-center">Are you sure you want to delete your account?</h2>
	                <p class="mt-4 mb-6 sm:text-lg text-gray-800 text-center">When you accept you will not be able to recover the account</p>
	                <div class="flex items-center justify-center gap-4">
		                <div class="flex items-center justify-center gap-4">
		                	<form action="{{route('components.delete', $user->id)}}" method="POST">
				                @csrf
				                @method('DELETE')
				                <button type="submit" class="w-full text-xs bg-transparent bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a block text-center"><span>Accept</span></button>
				            </form>
		                    <button @click="open = false" class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 md:px-6 rounded-full text-sm font-400 mr-a block text-center cursor-pointer">
		                        <span>Cancel</span>
		                    </button>
		                </div>
		            </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>