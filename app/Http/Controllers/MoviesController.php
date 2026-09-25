<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Facades\Omdb;
use App\Facades\Tmdb;
use App\Models\FavoriteMovie;
use App\Models\FavoriteActor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\FavoriteGenre;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * JSON de "now playing" para el scroll infinito de la home. Así la clave de
     * TMDB no llega al navegador.
     */
    public function nowPlaying(Request $request)
    {
        $page = min(max($request->integer('page', 1), 1), 500);

        $movies = collect(Tmdb::get('/movie/now_playing', ['language' => 'en-US', 'page' => $page])['results'] ?? [])
            ->filter(fn ($movie) => ($movie['vote_average'] ?? 0) > 2 && ! empty($movie['poster_path']))
            ->map(fn ($movie) => [
                'id' => $movie['id'],
                'title' => $movie['title'] ?? '',
                'poster_path' => $movie['poster_path'],
                'vote_average' => $movie['vote_average'],
                'genre_ids' => $movie['genre_ids'] ?? [],
            ])
            ->values();

        return response()->json(['results' => $movies]);
    }

    public function index(Request $request)
    {
        $playingMovies = Tmdb::get('/movie/now_playing', 'language=en-US&page=1')['results'] ?? [];


        $sliderMovies = Tmdb::get('/movie/upcoming', 'language=en-US&page=1')['results'] ?? [];

        $allMovieSlider = collect($sliderMovies)
            ->sortBy('release_date')
            ->reverse()
            ->where('backdrop_path', '!=', null)
            ->where('original_language', '==', 'en')
            ->take(4)
            ->values();

        // Detalles completos de cada película del slider, en una sola tanda de
        // peticiones concurrentes (antes: 1 petición secuencial por película).
        $movieSlider = collect(Tmdb::getMany(
            $allMovieSlider->mapWithKeys(fn ($movie) => [
                $movie['id'] => ['/movie/'.$movie['id'], ['language' => 'en-US']],
            ])->all()
        ))
            ->map(fn ($movie) => $movie + ['subtitle' => 'upcoming'])
            ->values()
            ->all();

        // Ídem para el tráiler de cada una. Antes esto vivía en un bucle anidado
        // cuyo límite interior crecía con cada vuelta del exterior, así que el
        // número de peticiones a TMDB aumentaba en progresión cuadrática en vez
        // de lineal con el número de películas del slider.
        $videoRequests = collect($movieSlider)
            ->filter(fn ($movie) => ! empty($movie['imdb_id']))
            ->mapWithKeys(fn ($movie) => [
                $movie['id'] => ['/movie/'.$movie['imdb_id'].'/videos', ['language' => 'en-US']],
            ])->all();

        $moviesTrailer = collect(Tmdb::getMany($videoRequests))
            ->map(fn ($video, $movieId) => ($video['results'][0] ?? null)
                ? $video['results'][0] + ['id_movie' => $movieId]
                : null)
            ->filter()
            ->values()
            ->all();

        $genresArray = Tmdb::get('/genre/movie/list', 'language=en-US')['genres'] ?? []; 

        $genres = Tmdb::genres();


        
        $favoritasMovies = FavoriteMovie::where('user_id', Auth::id())->get();


        $countGenres = FavoriteGenre::where('user_id', Auth::id())->get();

        $countGenre = array();
        $number = random_int(0, 20);

        for ($i=0; $i<count($countGenres); $i++) {
            $genresMovies = Tmdb::get('/genre/'.$countGenres[$i]->genre_id.'/movies', 'language=en-US&include_adult=false&sort_by=created_at.asc')['results'] ?? [];

            $countGenre[$i]['id'] = $countGenres[$i]->genre_id;
            $countGenre[$i]['name'] = $countGenres[$i]->name;
            $countGenre[$i]['backdrop_path'] = $genresMovies[19]['backdrop_path'];
        }

        $favorito = false;


        
        return view('index', [
            'playingMovies' => $playingMovies,
            'genres' => $genres,
            'sliderMovies' => $sliderMovies,
            'genresArray' => $genresArray,
                        'favoritasMovies' => $favoritasMovies,
                        'moviesTrailer' => $moviesTrailer,
            'countGenre' => $countGenre,
            'request' => $request,
            'favorito' => $favorito,
            'movieSlider' => $movieSlider
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Tmdb::get('/movie/'.$id, 'language=en-US');

        $movieDetails = Omdb::find($movie['imdb_id']);

        $movieTrailer = Tmdb::get('/movie/'.$movie['imdb_id'].'/videos')['results'][0] ?? [];

        $movieCredits = Tmdb::get('/movie/'.$id.'/credits')['cast'] ?? [];
        
        $noneImagen = collect($movieCredits)->where('profile_path', '!=', null);
        $popularityActor = collect($noneImagen)->where('popularity', '>', 4);
        $movieActors = collect($popularityActor)->sortBy('popularity')->reverse()->toArray();


        $moviesSimilar = Tmdb::get('/movie/'.$id.'/similar', 'language=en-US')['results'] ?? [];

        $genresMovies = collect($movie['genres'] ?? [])->pluck('id')->all();

        // Similares que comparten al menos un género con la película actual, sin
        // duplicados. Antes esto era un triple bucle anidado (similar × sus
        // géneros × los géneros de la película) que además podía insertar el
        // mismo similar varias veces, y `count($movie['genres'])` sin `?? []`
        // rompía la página entera (TypeError) si TMDB no devolvía la película.
        $allMovieSimilar = collect($moviesSimilar)
            ->filter(fn ($similar) => array_intersect($similar['genre_ids'] ?? [], $genresMovies))
            ->unique('id')
            ->values()
            ->all();


        $genres = Tmdb::genres();




        $favoritasMovies = FavoriteMovie::where('user_id', Auth::id())->get();

        

        $favoritasActors = FavoriteActor::where('user_id', Auth::id())->get();

        

        $countFavorites = DB::table('favorites_movies')
            ->select(DB::raw('count(id) as count_fav, tmdb_id'))
            ->groupBy('tmdb_id')
            ->get();

        $countFavorite = array();
        $countFav = 0;

        foreach($countFavorites as $favorite){
            $countFavorite[$favorite->tmdb_id] = true;
            $countFav++;
        }

        return view('movies.show', [
            'movie' => $movie,
            'moviesSimilar' => $moviesSimilar,
            'genres' => $genres,
            'movieDetails' => $movieDetails,
            'movieTrailer' => $movieTrailer,
                        'movieCredits' => $movieCredits,
            'countFavorites' => $countFavorites,
                        'favoritasMovies' => $favoritasMovies,
            'favoritasActors' => $favoritasActors,
            'countFav' => $countFav,
            'allMovieSimilar' => $allMovieSimilar,
            'moviesSimilar' => $moviesSimilar,
            'movieActors' => $movieActors
        ]);
    }

    public function showActor($id)
    {
        $actor = Tmdb::get('/person/'.$id, 'language=en-US');

        $birthday = collect($actor)->merge([
            'birthday' => Carbon::parse($actor['birthday'])->format('M d, Y'),
            'age' => Carbon::parse($actor['birthday'])->age
        ]);


        $actorMovies = Tmdb::get('/person/'.$id.'/combined_credits', 'language=en-US')['cast'] ?? [];

        
        $collection = collect($actorMovies);
        $noneImagen = $collection->where('backdrop_path', '!=', null);
        $noneVote = $noneImagen->where('vote_count', '>', 100);
        $noneTv = $noneVote->where('media_type', '==', 'movie');
        $noneGenres = $noneTv->where('genre_ids', '!=', []);
        $knownForMovies = collect($noneGenres)->sortBy('vote_average')->reverse()->toArray();


        $genres = Tmdb::genres();

        $favoritasMovies = FavoriteMovie::where('user_id', Auth::id())->get();
            
        

        $favoritasActors = FavoriteActor::where('user_id', Auth::id())->get();

        $favoriteActors = array();

        foreach($favoritasActors as $favoriteActor){
          $favoriteActors[$favoriteActor->tmdb_id] = $favoriteActor->tmdb_id;
        }


        $countFavorites = DB::table('favorites_actors')
            ->select(DB::raw('count(id) as count_fav, tmdb_id'))
            ->groupBy('tmdb_id')
            ->get();

        $countFavorite = array();
        $countFav = 0;

        foreach($countFavorites as $favorite){
            $countFavorite[$favorite->tmdb_id] = true;
            $countFav++;
        }

        return view('actors.show', [
            'actor' => $actor,
            'actorMovies' => $actorMovies,
            'genres' => $genres,
            'favoriteActors' => $favoriteActors,
                        'favoritasMovies' => $favoritasMovies,
            'favoritasActors' => $favoritasActors,
            'countFav' => $countFav,
            'birthday' => $birthday,
            'knownForMovies' => $knownForMovies
        ]);
    }

    public function showGenre($id)
    {
        $genresMovies = Tmdb::get('/genre/'.$id.'/movies', 'language=en-US&include_adult=false&sort_by=created_at.asc')['results'] ?? [];

        $genresArray = Tmdb::get('/genre/movie/list', 'language=en-US')['genres'] ?? []; 

        $genres = Tmdb::genres();

        foreach ($genresArray as $genre) {
            if($genre['id'] == $id) {
                $genreName = $genre['name'];
            }
        }


        $favoritasMovies = FavoriteMovie::where('user_id', Auth::id())->get();

                
        
        return view('genres.showGenre', [
            'genresMovies' => $genresMovies,
            'genres' => $genres,
            'genreName' => $genreName,
            'favoritasMovies' => $favoritasMovies
                    ]);
    }

    public function addmovie($id) {

        if (! Auth::check()) {
            return redirect('/')->with('status', 'Debe estar autentificado');
        }

        $movie = Tmdb::get('/movie/'.$id, 'language=en-US');
        abort_unless(isset($movie['title']), 404);

        FavoriteMovie::firstOrCreate(
            ['user_id' => Auth::id(), 'tmdb_id' => $id],
            [
                'title' => $movie['title'],
                'poster_path' => $movie['poster_path'] ?? '',
                'vote_average' => $movie['vote_average'] ?? 0,
            ]
        );

        return redirect()->back();
    }


    public function removemovie($id) {

        FavoriteMovie::where('tmdb_id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back();
    }

    public function addactor($id) {

        if (! Auth::check()) {
            return redirect('/')->with('status', 'Debe estar autentificado');
        }

        $actor = Tmdb::get('/person/'.$id, 'language=en-US');
        abort_unless(isset($actor['name']), 404);

        FavoriteActor::firstOrCreate(
            ['user_id' => Auth::id(), 'tmdb_id' => $id],
            [
                'name' => $actor['name'],
                'profile_path' => $actor['profile_path'] ?? '',
            ]
        );

        return redirect()->back();
    }

    public function removeactor($id) {

        FavoriteActor::where('tmdb_id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back();
    }


}
