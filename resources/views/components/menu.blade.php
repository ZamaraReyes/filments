<nav id="sticky-header" class="flex items-center justify-between flex-wrap p-3 fixed w-full z-10 top-0" x-data="{ isOpen: false }" @keydown.escape="isOpen = false">
  <div class="container mx-auto flex items-center justify-between">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
      <a href="/" class="text-white no-underline hover:text-white hover:no-underline">
        <img src="{{asset('img/logo-filments.svg')}}" class="w-48 inline-block">
      </a>
    </div>
    <div class="flex justify-end w-full">
      <ul class="list-reset flex justify-end flex-1 items-center text-white">
        @guest
          @if(Route::has('login'))
          <li class="mr-3">
            <a href="{{route('login')}}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-3 px-4 md:px-6 rounded-full text-sm font-400 mr-a block text-center">
              <i class="fas fa-unlock-alt"></i>
              <span class="hidden md:inline-block">Login</span>
            </a>
          </li>
          @endif

          @if(Route::has('register'))
          <li class="mr-3">
            <a href="{{route('auth.register')}}" class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 md:px-6 rounded-full text-sm font-400 mr-a block text-center">
              <i class="fas fa-user"></i>
              <span class="hidden md:inline-block">Register</span>
            </a>
          </li>
          @endif

            @else
              <li class="mr-3 nav-item dropdown hidden md:inline-block">
                <p id="navbarDropdown" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  <i class="far fa-user"></i> Hi, {{ Auth::user()->name }}
                </p>

                <div class="dropdown-menu absolute bg-gray-900 hover:shadow-2xl rounded-lg p-4" aria-labelledby="navbarDropdown">
                  @if(Auth::user()->isAdmin())
                  <a class="block text-sm leading-6" href="{{ route('profile.management') }}">Admin</a>
                  @endif
                  <a class="block text-sm leading-6" href="{{ route('profile.my-recomendation') }}">My recomendations</a>
                  <a class="block text-sm leading-6" href="{{ route('profile.my-profile') }}">Edit my profile</a>
                  <a class="block text-sm leading-6" href="{{ route('profile.my-password') }}">Edit my password</a>
                  <a class="block text-sm leading-6" href="{{ route('profile.my-movies') }}">My favorites movies</a>
                  <a class="block text-sm leading-6" href="{{ route('profile.my-actors') }}">My favorites actors</a>
                  <a class="block text-sm leading-6" href="{{ route('profile.my-genres') }}">My favorites genres</a>
                  <a class="block text-sm leading-6" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">Logout</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  </form>
                </div>
              </li>

              <div x-data="{ open: false }" class="md:hidden inline-block ml-3 flex order-2">
                <div id="nav-icon3" @click="open = true">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
                <ul class="w-full text-white fixed h-full top-0 left-0 pt-20 bg-gray-900 modal-menu" x-show.transition.opacity="open" @click.away="open = false">
                  <li class="md:inline-block p-3">
                    <p><i class="far fa-user mr-3"></i> Hi, {{ Auth::user()->name }}</p>
                  </li>
                  <li class="md:inline-block p-3">
                    <a class="block leading-6" href="{{ route('profile.my-profile') }}"><i class="far fa-edit text-gray-600 mr-2"></i> Edit my profile</a>
                  </li>
                  <li class="md:inline-block p-3">
                    <a class="block leading-6" href="{{ route('profile.my-password') }}"><i class="far fa-key text-gray-600 mr-2"></i> Edit my password</a>
                  </li>
                  <li class="md:inline-block p-3">
                    <a class="block leading-6" href="{{ route('profile.my-movies') }}"><i class="fa fa-film text-gray-600 mr-2"></i> My favorites movies</a>
                  </li>
                  <li class="md:inline-block p-3">
                    <a class="block leading-6" href="{{ route('profile.my-actors') }}"><i class="fa fa-users text-gray-600 mr-2"></i> My favorites actors</a>
                  </li>
                  <li class="md:inline-block p-3">
                    <a class="block leading-6" href="{{ route('profile.my-genres') }}"><i class="fa fa-video text-gray-600 mr-2"></i> My favorites genres</a>
                  </li>
                  <li class="md:inline-block p-3">
                    <a class="block leading-6" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out text-gray-600 mr-2"></i>  Logout</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      </form>
                  </li>
                </ul>
              </div>
            @endguest
            <div x-data="{ open: false }" class="text-white flex order-1">
              <div @click="open = true">
                <i class="fas fa-search"></i>
              </div>
              <div class="modal-search fixed w-full h-full top-0 left-0" x-show.transition.opacity="open" @click.away="open = false">
                <div class="absolute w-full h-full bg-gray-900 opacity-80" @click="open = false"></div>
                <div class="bg-gray-900 w-full mx-auto shadow-lg z-50 top-0 fixed modal-efecto">
                  <div class="py-4 text-left px-6 mt-16">
                    <livewire:search-dropdown/>
                  </div>
                </div>
              </div>
            </div>
        </ul>
    </div>
  </div>
</nav>