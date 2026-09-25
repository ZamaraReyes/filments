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
                <h1 class="text-2xl text-white">Users</h1>
            </div>
            <div class="grid grid-cols-12 gap-4 text-sm text-white mb-6">
                <p class="col-span-1 uppercase">Id</p>
                <p class="col-span-4 uppercase">Email</p>
                <p class="col-span-2 uppercase">Verified</p>
                <p class="col-span-5 uppercase"></p>
            </div>
                @foreach($users as $user)
                <div class="grid grid-cols-12 gap-4 text-sm text-white my-2">
                    <p class="col-span-1 leading-8">{{$user->id}}</p>
                    <p class="col-span-4">{{$user->email}}</p>
                    @if($user->email_verified_at == null)
                    <p class="col-span-2 leading-8 uppercase">No</p>
                    @else
                    <p class="col-span-2 leading-8 uppercase">Yes</p>
                    @endif
                    <div class="col-span-5 flex">
                        <form method="POST" action="{{route('forgetpassword.show')}}" class="w-full mr-2 text-right">
                            @csrf
                            <input id="email" type="hidden" name="email" value="{{$user->email}}">
                            <button type="submit" class="px-4 text-xs bg-transparent bg-green-500 hover:bg-green-600 text-white py-2 rounded-md text-sm font-400 m-a inline-block text-center">Reset password</button>
                        </form>
                        <a href="{{route('users.show', $user->id)}}" class="px-6 text-xs bg-transparent bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-md text-sm font-400 m-a inline-block text-center mr-2">
                            <span>Edit</span>
                        </a>
                        <button onclick="document.getElementById('user_{{$user->id}}').dispatchEvent(new CustomEvent('open-me', { detail: {}}));" class="px-6 text-xs bg-transparent bg-red-500 hover:bg-red-600 text-white py-2 rounded-md text-sm font-400 m-a inline-block text-center">
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
                @endforeach
              </div>
        </div>
    </div>
</div>

@foreach($users as $user)
    <x-alert-delete :user="$user"/>
@endforeach

@endsection
