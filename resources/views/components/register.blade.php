<div class="container mx-auto xs:h-full sm:h-screen flex items-center justify-center pb-10 pt-20 md:pb-0 md:pt-0">
    <div class="w-full p-3">
        <h2 class="text-4xl text-white">Register</h2>
        <p class="mt-2 text-white">Already a member? <a href="{{route('login')}}" class="text-yellow-600">Log in</a></p>
        @if($errors->any())
        <div class="mt-2 bg-red-100 px-4 py-3 rounded-md border-2 border-red-300 flex items-center">
            <i class="fa fa-exclamation-circle text-lg text-red-600 mr-2"></i>
            <ul>
                @foreach($errors->all() as $error)
                <li class="leading-none">
                    <span class="text-sm text-red-600">{{$error}}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        <form method="POST" action="{{ route('register') }}" class="grid xs:grid-cols-1 sm:grid-cols-2 xs:gap-0 sm:gap-8">
            @csrf
            <div class="col-span-1">
                <div class="mt-4">
                    <label for="name" class="text-sm text-white">Name</label>
                    <div class="mt-1">
                        <input id="name" type="text" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="email" class="text-sm text-white">E-Mail Address</label>
                    <div class="mt-1">
                        <input id="email" type="email" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                </div>
                <div class="mt-4">
                    <label for="password" class="text-sm text-white">Password</label>
                    <div class="mt-1">
                        <input id="password" type="password" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="password" required autocomplete="password">
                    </div>
                </div>
                <div class="mt-4">
                    <label for="password-confirm" class="text-sm text-white">Confirm Password</label>
                    <div class="mt-1">
                        <input id="password-confirm" type="password" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="mt-4">
                    <label for="genres" class="text-sm text-white">What are your favorite genres?</label>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xs:gap-0 sm:gap-1 mt-1 border-2 border-gray-600 rounded-md p-4">
                    @foreach($genres as $key => $genre)
                        <div class="col-span-1 block mt-1 leading-snug">
                            <input type="checkbox" id="{{$genre}}" name="genres[]" value="{{$key}}">
                            <label for="{{$genre}}" class="text-sm ml-1 text-white">{{$genre}}</label>
                        </div>
                    @endforeach
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="w-full text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a block text-center">Register</button>
                </div>
            </div>
        </form>
    </div>
</div>