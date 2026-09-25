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
                <h1 class="text-2xl text-white">My favorites genres</h1>
            </div>
            <form action="{{route('components.genres',$user->id)}}" method="POST">
                @csrf
                {{ method_field('PATCH') }}
                <div class="grid grid-cols-12 gap-12">
                    <div class="col-span-12">
                        <div class="grid xs:grid-cols-1 sm:grid-cols-3 xs:gap-0 sm:gap-1">
                        @foreach($genres as $key => $genre)
                            <div class="col-span-1 block mt-1 leading-snug">
                                <input type="checkbox" id="{{$genre}}" name="genres[]" value="{{$key}}" @foreach($FavoritasGenres as $genres) @if($key == $genres->genre_id) checked
                            @endif @endforeach>
                                <label for="{{$genre}}" class="text-sm ml-1 text-white">{{$genre}}</label>
                            </div>
                        @endforeach
                        </div>
                        <div class="mt-10">
                            <button type="submit" class="w-full text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-6 rounded-full text-sm font-400 mr-a mt-2 block text-center">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
