@extends('layouts.main')

@section('content')
@if(session('status'))
<x-alert-reset/>
@endif
<div class="container mx-auto">
    <div class="grid grid-cols-12 gap-4 mt-20 md:mt-40">
        <div class="hidden md:block md:col-span-4 lg:col-span-3 h-full mr-5 pl-3">
            <x-menu-dashboard :user="$user"/>
        </div>
        <div class="dashboard col-span-12 md:col-span-8 lg:col-span-9 px-3 pb-20">
            <x-verify-email-banner/>
            <div class="py-3 mb-4">
                <h1 class="text-2xl text-white">Change password</h1>
            </div>
            <form method="POST" action="{{route('profile.my-password.update')}}">
                @csrf
                <div class="mt-4">
                    <label for="password" class="text-sm text-white">Password</label>
                    <div class="mt-1">
                        <input id="password" type="password" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" autofocus>
                        @if($errors->has('password'))
                            <span class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mt-4">
                    <label for="password" class="text-sm text-white">Confirm password</label>
                    <div class="mt-1">
                        <input id="password-confirm" type="password" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="current-password" autofocus>
                        @if($errors->has('password_confirmation'))
                            <span class="mt-2 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="w-full text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
