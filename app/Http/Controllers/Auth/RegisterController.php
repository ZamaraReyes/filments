<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FavoriteGenre;
use App\Facades\Tmdb;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/my-recomendation';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', PasswordRule::min(8)->mixedCase()->numbers(), 'confirmed'],
            'genres' => ['required', 'array', 'min:1']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $genreNames = Tmdb::genres();

        foreach ($data['genres'] ?? [] as $genreId) {
            if ($genreNames->has($genreId)) {
                FavoriteGenre::create([
                    'user_id' => $user->id,
                    'genre_id' => $genreId,
                    'name' => $genreNames[$genreId]
                ]);
            }
        }

        return $user;
    }

    /**
     * Adónde va el usuario tras registrarse: a la home, marcando la sesión
     * para que muestre el aviso de "confirma tu email" durante unos segundos.
     */
    protected function registered(\Illuminate\Http\Request $request, $user)
    {
        return redirect('/')->with('justRegistered', true);
    }

    public function index()
    {

        $firstMovie = Tmdb::get('/movie/upcoming', 'language=en-US&page=1')['results'][0] ?? [];

        $genres = Tmdb::genres();

        return view('auth.register', [
            'firstMovie' => $firstMovie,
            'genres' => $genres
        ]);
    }

    }
