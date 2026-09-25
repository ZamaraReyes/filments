<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{asset('img/favicon.svg')}}">
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" href="{{asset('css/tailwind.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/components.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/utilities.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/fontawesome-all.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
        <title>FILMENTS</title>
        @livewireStyles
    </head>
    <body class="bg-gray-900 flex flex-col min-h-screen">
      <x-loading/>
      
      <x-menu :genres="$genres"/>
      <main class="flex-grow">
        @yield('content')
      </main>
      <footer class="w-full mt-auto">
        <div class="container mx-auto text-center px-5 md:mt-10">
          <p class="inline-block text-white text-xs text-gray-500 pt-4 pb-4 border-t-2 border-gray-500 w-full">© Copyright {{ date('Y') }} - Filments. Page designed and developed by <a href="http://zamarareyes.es/">Zamara Reyes</a>. Information extracted from the apis <a href="https://www.themoviedb.org/">The Movie Database</a> and <a href="https://www.imdb.com/">IMDB</a>.</p>
        </div>
      </footer>
      <script type="text/javascript" src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
      <script type="text/javascript" src="{{asset('js/owl.carousel.min.js')}}"></script>
      <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
      @livewireScripts
      <script type="text/javascript">
        $("#loading-scroll").hide();
      </script>
      @if(Route::getCurrentRoute()->uri() == '/')
      <script type="text/javascript">

        const favoritesMovies = @json($favoritasMovies);
        const genres = @json($genres);
        const favoriteIds = {};
        $.each(favoritesMovies, function (key, favorite) { favoriteIds[favorite['tmdb_id']] = true; });

        const isLogged = @json(Auth::check());
        const csrfToken = @json(csrf_token());
        const loginUrl = @json(route('auth.login'));

        var currentscrollHeight = 0;
        var page = 2;
        var loading = false;
        var finished = false;

        $(window).on("scroll", function () {
          const scrollHeight = $(document).height();
          const scrollPos = Math.floor($(window).height() + $(window).scrollTop());
          const isBottom = scrollHeight - 100 < scrollPos;
          if (isBottom && !loading && !finished && currentscrollHeight < scrollHeight) {
            callData(page);
            currentscrollHeight = scrollHeight;
          }
        });

        function callData(pageNumber) {
          loading = true;
          $.ajax({
            type: "GET",
            url: @json(route('movies.nowPlaying')),
            data: { page: pageNumber },
            dataType: "json",
            beforeSend: function () {
              $("#loading-scroll").show();
            },
            success: function (data) {
              if (!data.results.length) {
                finished = true;
                return;
              }
              $.each(data.results, function (key, movie) {
                $(getItemHTMLE({ movie })).appendTo('.list');
              });
              page++;
            },
            complete: function () {
              loading = false;
              $("#loading-scroll").hide();
            },
            error: function () {
              $('<div class="card my-4 py-3"><h4 class="card-title text-white">API call failed</h4></div>').appendTo('.list');
            }
          });
        }

        function esc(text) {
          return $('<div>').text(text == null ? '' : text).html();
        }

        function getItemHTMLE({ movie }) {

          const title = esc(movie.title);
          const movieUrl = '/movies/' + movie.id;
          const genreLinks = (movie.genre_ids || []).map(function (id) {
            return '<a href="/genres/' + id + '" class="inline-block text-xs text-gray-500">' + esc(genres[id]) + '</a>';
          }).join('<span class="text-xs text-gray-500">, </span>');

          let action;
          if (!isLogged) {
            action = '<a href="' + loginUrl + '" class="text-xs bg-transparent text-white font-400 py-2 px-4"><i class="far fa-heart"></i> <span>Add favorite</span></a>';
          } else if (favoriteIds[movie.id]) {
            action = '<form action="/dislike/' + movie.id + '" method="POST" class="inline-block">'
              + '<input type="hidden" name="_token" value="' + csrfToken + '">'
              + '<input type="hidden" name="_method" value="DELETE">'
              + '<button type="submit" class="text-xs bg-transparent text-white font-400 py-2 px-4"><i class="fas fa-heart-broken"></i> <span>Dislike</span></button>'
              + '</form>';
          } else {
            action = '<form action="/favorite/' + movie.id + '" method="POST" class="inline-block">'
              + '<input type="hidden" name="_token" value="' + csrfToken + '">'
              + '<button type="submit" class="text-xs bg-transparent text-white font-400 py-2 px-4"><i class="far fa-heart"></i> <span>Add favorite</span></button>'
              + '</form>';
          }

          return '<div class="movie p-4 hover:shadow-2xl hover:bg-gray-800 rounded-lg">'
            + '<a href="' + movieUrl + '"><img src="https://image.tmdb.org/t/p/w342' + movie.poster_path + '" alt="' + title + '" title="' + title + '"></a>'
            + '<a href="' + movieUrl + '"><h2 class="text-white font-bold mt-4">' + title + '</h2></a>'
            + '<div class="leading-4">' + genreLinks + '</div>'
            + '<i class="far fa-star fas text-yellow-600 inline-block text-xs mr-1"></i><p class="inline-block text-white text-xs">' + Number(movie.vote_average).toFixed(1) + '</p>'
            + action
            + '</div>';
        }

      </script>
      @endif
    </body>
</html>