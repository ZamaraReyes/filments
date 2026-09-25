<div class="container mx-auto h-screen flex items-center justify-center">
    <div class="w-full sm:w-6/12 lg:w-4/12 p-3">
        <h2 class="text-4xl text-white">Reset Password</h2>
        @if($errors->any())
        <div class="mt-4 bg-red-100 px-4 py-3 rounded-md border-2 border-red-300 flex items-center">
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
        <form method="POST" action="{{ route('ResetPasswordPost') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mt-4">
                <label for="email" class="text-sm text-white">E-mail</label>
                <div class="mt-2">
                    <input id="email" type="email" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="email" required autocomplete="email" autofocus>
                </div>
            </div>
            <div class="mt-4">
                <label for="password" class="text-sm text-white">Password</label>
                <div class="mt-1">
                    <input id="password" type="password" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="password" required autocomplete="current-password" autofocus>
                </div>
            </div>
            <div class="mt-4">
                <label for="password" class="text-sm text-white">Confirm Password</label>
                <div class="mt-1">
                    <input id="password-confirm" type="password" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent" name="password_confirmation" required autocomplete="current-password" autofocus>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center">Reset Password</button>
            </div>
        </form>  
    </div>
</div>