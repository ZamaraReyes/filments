@extends('layouts.main')

@section('content')
<div class="container mx-auto">
    <div class="grid grid-cols-12 gap-4 mt-20 md:mt-40">
        <div class="hidden md:block md:col-span-4 lg:col-span-3 h-full mr-5 pl-3">
            <x-menu-dashboard :user="$user"/>
        </div>
        <div class="dashboard col-span-12 md:col-span-8 lg:col-span-9 px-3 pb-20">
            <x-verify-email-banner/>
            <div class="py-3 mb-4">
                <h1 class="text-2xl text-white">Edit my profile</h1>
            </div>
            <form action="{{route('components.update',$user->id)}}" method="POST">
                @csrf
                {{ method_field('PATCH') }}
                <div class="mt-4">
                    <label for="name" class="text-white">Name</label>
                    <div class="mt-2">
                        <input id="name" type="text" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent @error('name') is-invalid @enderror" name="name" value="{{$user->name}}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="email" class="text-white">E-Mail Address</label>
                    <div class="mt-2">
                        <input id="email" type="email" class="w-full py-2 px-5 text-white border-2 rounded-full border-gray-600 bg-transparent @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required autocomplete="email">
                        @error('email')
                            <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <div class="mt-2">
                        <button type="submit" class="w-full text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
            <button onclick="document.getElementById('user_{{auth()->id()}}').dispatchEvent(new CustomEvent('open-me', { detail: {}}));" class="w-full mt-5 text-xs bg-transparent bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center">
                <span>Delete my account</span>
            </button>
        </div>
    </div>
</div>
<x-alert-delete :user="$user"/>
@endsection
