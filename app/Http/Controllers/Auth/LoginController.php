<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Facades\Tmdb;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LoginController extends Controller implements HasMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/my-recomendation';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
        ];
    }

    public function do(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (! Auth::attempt($credentials)) {
            $this->incrementLoginAttempts($request);

            return redirect()->back()->with('status', 'The provided credentials do not match our records.');
        }

        $this->clearLoginAttempts($request);
        $request->session()->regenerate();

        return redirect()->intended('/my-recomendation');
    }

    public function index()
    {

        $firstMovie = Tmdb::get('/movie/upcoming', 'language=en-US&page=1')['results'][0] ?? [];

        $genres = Tmdb::genres();

        return view('auth.login', [
            'firstMovie' => $firstMovie,
            'genres' => $genres
        ]);
    }
}
