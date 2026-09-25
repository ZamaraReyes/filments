<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Concerns\SendsPasswordResetToken;
use Illuminate\Support\Str;
use App\Facades\Tmdb;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails, SendsPasswordResetToken;

    public function ForgetPassword() {

        $firstMovie = Tmdb::get('/movie/upcoming', 'language=en-US&page=1')['results'][0] ?? [];

        $genres = Tmdb::genres();

        return view('auth.forget-password', [
            'firstMovie' => $firstMovie,
            'genres' => $genres
        ]);
    }

    public function ForgetPasswordStore(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        if (! $this->sendPasswordResetToken($request->email)) {
            return back()->with('status', 'Server failed. Try again later');
        }

        return redirect('/')->with('status', 'We\'ve sent you an email to reset your password.');
    }

    public function ResetPassword($token) {

        $firstMovie = Tmdb::get('/movie/upcoming', 'language=en-US&page=1')['results'][0] ?? [];

        $genres = Tmdb::genres();

        return view('auth.forget-password-link', [
            'firstMovie' => $firstMovie,
            'genres' => $genres,
            'token' => $token
        ]);
    }
    
    public function ResetPasswordStore(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users',
            'password' => ['required', 'string', PasswordRule::min(8)->mixedCase()->numbers(), 'confirmed'],
            'password_confirmation' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->with('status', 'This password reset link is invalid or has expired.');
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('status', 'Your password has been successfully changed!');
        }

        return redirect()->back()->with('status', 'The provided credentials do not match our records.');
    }
}
