<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\Tmdb;
use App\Services\RecommendationService;
use App\Models\FavoriteGenre;
use App\Models\DislikeMovie;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;

class HomeController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $user = Auth::user();

        $genres = Tmdb::genres();

        $favoritesMovie = Auth::user()->favoriteMovies;

        $favoritesActor = Auth::user()->favoriteActors;

        $FavoritasGenres = Auth::user()->genres;


        return view('profile.my-profile', [
            'genres' => $genres,
            'favoritesMovie' => $favoritesMovie,
            'favoritesActor' => $favoritesActor,
            'FavoritasGenres' => $FavoritasGenres,
            'user' => $user
        ]);
    }

    public function myFavoritesMovies()
    {
        
        $user = Auth::user();

        $genres = Tmdb::genres();

        $favoritesMovie = Auth::user()->favoriteMovies;

        $favoritesActor = Auth::user()->favoriteActors;

        $FavoritasGenres = Auth::user()->genres;


        return view('profile.my-movies', [
            'genres' => $genres,
            'favoritesMovie' => $favoritesMovie,
            'favoritesActor' => $favoritesActor,
            'FavoritasGenres' => $FavoritasGenres,
            'user' => $user
        ]);
    }

    public function myFavoritesActors()
    {
        
        $user = Auth::user();

        $genres = Tmdb::genres();

        $favoritesMovie = Auth::user()->favoriteMovies;

        $favoritesActor = Auth::user()->favoriteActors;

        $FavoritasGenres = Auth::user()->genres;


        return view('profile.my-actors', [
            'genres' => $genres,
            'favoritesMovie' => $favoritesMovie,
            'favoritesActor' => $favoritesActor,
            'FavoritasGenres' => $FavoritasGenres,
            'user' => $user
        ]);
    }

    public function myFavoritesGenres()
    {
        
        $user = Auth::user();

        $genres = Tmdb::genres();

        $favoritesMovie = Auth::user()->favoriteMovies;

        $favoritesActor = Auth::user()->favoriteActors;

        $FavoritasGenres = Auth::user()->genres;


        return view('profile.my-genres', [
            'genres' => $genres,
            'favoritesMovie' => $favoritesMovie,
            'favoritesActor' => $favoritesActor,
            'FavoritasGenres' => $FavoritasGenres,
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $user->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]));

        return redirect()->back();
    }

    public function updateGenre(Request $request)
    {
        $request->validate([
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['integer'],
        ]);

        $genreNames = collect(Tmdb::get('/genre/movie/list', 'language=en-US')['genres'] ?? [])->pluck('name', 'id');

        FavoriteGenre::where('user_id', Auth::id())->delete();

        foreach ($request->genres as $genreId) {
            if ($genreNames->has($genreId)) {
                FavoriteGenre::create([
                    'user_id' => Auth::id(),
                    'genre_id' => $genreId,
                    'name' => $genreNames[$genreId]
                ]);
            }
        }

        return redirect()->back();
    }

    public function editPassword() {

        $user = Auth::user();

        $genres = Tmdb::genres();

        return view('profile.my-password', [
            'genres' => $genres,
            'user' => $user
        ]);
    }

    public function myPassword(Request $request) {

        $request->validate([
            'password' => ['required', 'string', PasswordRule::min(8)->mixedCase()->numbers(), 'confirmed'],
            'password_confirmation' => 'required'
        ]);

        Auth::user()->update(['password' => $request->password]);

        return redirect()->back()->with('status', 'Your password has been successfully changed!');
    }

    public function myRecomendation(RecommendationService $recommendations) {

        $user = Auth::user();

        $favoritasMovies = $user->favoriteMovies;

        $moviesRecom = $recommendations->forPreferences(
            $user->genres->pluck('genre_id')->all(),
            $user->favoriteActors->pluck('tmdb_id')->all(),
            $favoritasMovies->pluck('tmdb_id')->merge($user->dislikedMovies->pluck('tmdb_id'))->all(),
        );

        return view('profile.my-recomendation', [
            'genres' => Tmdb::genres(),
            'favoritasMovies' => $favoritasMovies,
            'moviesRecom' => $moviesRecom,
            'user' => $user
        ]);
    }

    public function dislikemovie($id) {

        if (! Auth::check()) {
            return redirect('/')->with('status', 'Debe estar autentificado');
        }

        $movie = Tmdb::get('/movie/'.$id, 'language=en-US');
        abort_unless(isset($movie['title']), 404);

        DislikeMovie::firstOrCreate(
            ['user_id' => Auth::id(), 'tmdb_id' => $id],
            [
                'title' => $movie['title'],
                'poster_path' => $movie['poster_path'] ?? '',
                'vote_average' => $movie['vote_average'] ?? 0,
            ]
        );

        return redirect()->back();
    }
}
