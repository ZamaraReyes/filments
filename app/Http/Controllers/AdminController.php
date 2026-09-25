<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\Tmdb;
use App\Models\FavoriteMovie;
use App\Models\FavoriteActor;
use App\Models\User;
use App\Models\FavoriteGenre;
use Illuminate\Validation\Rule;
use App\Models\DislikeMovie;
use App\Http\Controllers\Concerns\SendsPasswordResetToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;

class AdminController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    use SendsPasswordResetToken;

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

        $users = DB::table('users')
            ->get();

        return view('profile.management', [
            'genres' => $genres,
            'favoritesMovie' => $favoritesMovie,
            'favoritesActor' => $favoritesActor,
            'FavoritasGenres' => $FavoritasGenres,
            'user' => $user,
            'users' => $users
        ]);
    }

    public function updateuser(Request $request, $id) {

        $user = User::findOrFail($id);

        // El formulario de edición (alert-change.blade.php) solo envía name,
        // email y genres: no tiene campo de contraseña, así que pedirla aquí
        // como "required" hacía que la validación fallara siempre y el admin
        // no pudiera guardar ningún cambio. Cambiar la contraseña de un
        // usuario ya tiene su propio flujo (botón "Reset password").
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'genres' => ['required', 'array', 'min:1']
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $genreNames = collect(Tmdb::get('/genre/movie/list', 'language=en-US')['genres'] ?? [])->pluck('name', 'id');

        FavoriteGenre::where('user_id', $user->id)->delete();

        foreach ($request->genres as $genreId) {
            if ($genreNames->has($genreId)) {
                FavoriteGenre::create([
                    'user_id' => $user->id,
                    'genre_id' => $genreId,
                    'name' => $genreNames[$genreId]
                ]);
            }
        }

        return redirect()->back();
    }

    public function sendForgetPassword(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        if (! $this->sendPasswordResetToken($request->email)) {
            return back()->with('status', 'Server failed. Try again later');
        }

        return back()->with('status', 'We have emailed the password reset link!');
    }

    public function showUser($id) {

        $totalGenres = array();
        $moviesFavorites = array();

        // Solo las columnas que usa la vista: nunca el hash de la contraseña
        // ni el remember_token, que no hacen falta ahí.
        $user = DB::table('users')
            ->select(['id', 'name', 'email', 'created_at', 'updated_at'])
            ->where('id', $id)
            ->get();

        $favoritesMovie = FavoriteMovie::where('user_id', $id)->get();

        $favoritesActor = FavoriteActor::where('user_id', $id)->get();

        $favoritasGenres = FavoriteGenre::where('user_id', $id)->get();

        $genres = Tmdb::genres();

        $allgenres = $genres;

        /** cogemos la información de las películas favoritas **/
        for ($i=0; $i<count($favoritesMovie); $i++){
            $moviesFavorites[$i] = Tmdb::get('/movie/'.$favoritesMovie[$i]->tmdb_id, 'language=en-US');
        }

        for ($i=0; $i<count($moviesFavorites); $i++){
            for ($j=0; $j<count($moviesFavorites[$i]['genres']); $j++){
                $totalGenres[] = $moviesFavorites[$i]['genres'][$j]['name'];
            }
        }

        $countGenres = array_count_values($totalGenres);
        $allCountGenres = collect($countGenres)->sortDesc()->toArray();


        return view('users.show', [
            'favoritesMovie' => $favoritesMovie,
            'favoritesActor' => $favoritesActor,
            'genres' => $genres,
            'user' => $user,
            'favoritasGenres' => $favoritasGenres,
            'allCountGenres' => $allCountGenres,
            'allgenres' => $allgenres
        ]);
    }

    public function removeuser($id) {

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('status', 'No puedes eliminar tu propia cuenta');
        }

        DB::transaction(function () use ($user) {
            FavoriteMovie::where('user_id', $user->id)->delete();
            FavoriteActor::where('user_id', $user->id)->delete();
            DislikeMovie::where('user_id', $user->id)->delete();
            FavoriteGenre::where('user_id', $user->id)->delete();
            $user->delete();
        });

        return redirect()->route('profile.management')->with('status', 'Usuario eliminado con éxito');
    }

}
