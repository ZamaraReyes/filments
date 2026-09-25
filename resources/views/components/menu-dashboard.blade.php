<ul class="fixed border-r-2 border-gray-800 h-4/6">
  @if($user->isAdmin())
  <li id="admin" class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('profile.management') }}" class="text-sx text-white leading-10"><i class="fas fa-lock text-gray-600 mr-2"></i> Admin</a>
  </li>
  @endif
  <li id="my-recomendation" class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('profile.my-recomendation') }}" class="text-sx text-white leading-10"><i class="fas fa-heart text-gray-600 mr-2"></i> My recomendations</a>
  </li>
  <li id="editar-perfil" class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('profile.my-profile') }}" class="text-sx text-white leading-10"><i class="far fa-edit text-gray-600 mr-2"></i> Edit my profile</a>
  </li>
  <li id="edit-password" class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('profile.my-password') }}" class="text-sx text-white leading-10"><i class="fa fa-key text-gray-600 mr-2"></i> Edit my password</a>
  </li>
  <li id="favorites-movies" class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('profile.my-movies') }}" class="text-sx text-white leading-10"><i class="fa fa-film text-gray-600 mr-2"></i> My favorites movies</a>
  </li>
  <li id="favorites-actors" class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('profile.my-actors') }}" class="text-sx text-white leading-10"><i class="fa fa-users text-gray-600 mr-2"></i> My favorites actors</a>
  </li>
  <li id="favorites-genres" class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('profile.my-genres') }}" class="text-sx text-white leading-10"><i class="fa fa-video text-gray-600 mr-2"></i> My favorites genres</a>
  </li>
  <li class="border-l-2 border-transparent pl-4 pr-6 hover:bg-gray-800">
    <a href="{{ route('logout') }}" class="text-sx text-white leading-10" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out text-gray-600 mr-2"></i> Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
  </li>
</ul>