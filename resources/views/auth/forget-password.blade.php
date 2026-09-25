@extends('layouts.main')

@section('content')
@if(session('status') == 'Server failed. Try again later')
<x-alert-error/>
@endif
<div class="w-full h-full mx-auto" style="background: linear-gradient(rgba(17,24,39,0.8) 50%, rgba(17,24,39,1) 100%),url({{'https://image.tmdb.org/t/p/original'.($firstMovie['backdrop_path'] ?? '')}}) top center/cover no-repeat">
  <x-forget/>
</div>
@endsection