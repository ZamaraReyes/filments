<div class="container mx-auto h-screen flex items-center justify-center">
    <div class="w-full sm:w-6/12 lg:w-4/12 p-3">
        <h2 class="text-4xl text-white">Login</h2>
        <p class="mt-2 text-white">Don't have an account yet? <a href="{{route('auth.register')}}" class="text-yellow-600">Sign up</a></p>
        @if(session('status'))
        <div class="mt-4 bg-red-100 px-4 py-3 rounded-md border-2 border-red-300 flex items-center">
            <i class="fa fa-exclamation-circle text-lg text-red-600 mr-2"></i>
            <span class="text-sm text-red-600">{{session('status')}}</span>
        </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mt-4">
                <label for="email" class="text-sm text-white">E-mail</label>
                <div class="mt-1">
                    <input id="email" type="email" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @if($errors->has('email'))
                        <span class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <label for="password" class="text-sm text-white">Password</label>
                <div class="mt-1">
                    <input id="password" type="password" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @if($errors->has('password'))
                        <span class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</span>
                    @endif
                </div>
            </div>
            <div class="mt-4 flex items-baseline justify-between">
                <div class="mt-1">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="text-sm text-white" for="remember">Remember me</label>
                </div>
                <a href="{{route('ForgetPasswordGet')}}" class="text-sm text-yellow-600">Forgot your password?</a>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center">Login</button>
            </div>
        </form>      
    </div>
</div>