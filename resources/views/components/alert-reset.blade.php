<div x-data="{ open: true }" x-show.transition.opacity="open" @click.away="open = false" class="fixed w-full h-full top-0 flex items-center justify-center z-20">
    <div class="absolute w-full h-full bg-gray-900 opacity-90"></div>
    <div class="xl:w-5/12 h-4/12 mx-auto z-50 bg-white absolute rounded-2xl flex items-center justify-center shadow-2xl p-4 sm:p-8">
        <div class="container">
            <div class="w-full">
                <div class="f-modal-alert">
                    <div class="f-modal-icon f-modal-success animate">
                        <span class="f-modal-line f-modal-tip animateSuccessTip"></span>
                        <span class="f-modal-line f-modal-long animateSuccessLong"></span>
                        <div class="f-modal-placeholder"></div>
                        <div class="f-modal-fix"></div>
                    </div>
                </div>
                <h2 class="text-2xl sm:text-3xl text-gray-800 text-center">{{session('status')}}</h2>
                <div class="flex items-center justify-center gap-4">
	                <div class="flex items-center justify-center gap-4">
	                    <div @click="open = false" class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 md:px-6 rounded-full text-sm font-400 mr-a block text-center cursor-pointer">
	                        <span>Ok, thank you</span>
	                    </div>
	                </div>
	            </div>
            </div>
        </div>
    </div>
</div>