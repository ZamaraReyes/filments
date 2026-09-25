<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MoviesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Cargadas por bootstrap/app.php dentro del grupo de middleware "web".
|
*/

/* Públicas */
Route::get('/', [MoviesController::class, 'index'])->name('movies.index');
Route::get('/movies-now-playing', [MoviesController::class, 'nowPlaying'])->middleware('throttle:60,1')->name('movies.nowPlaying');
Route::get('/movies/{id}', [MoviesController::class, 'show'])->whereNumber('id')->name('movies.show');
Route::get('/actors/{id}', [MoviesController::class, 'showActor'])->whereNumber('id')->name('actors.show');
Route::get('/genres/{id}', [MoviesController::class, 'showGenre'])->whereNumber('id')->name('genres.showGenre');

/* Autenticación */
Route::get('/login', [LoginController::class, 'index'])->name('auth.login');
Route::post('/login', [LoginController::class, 'do'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register-user', [RegisterController::class, 'index'])->middleware('guest')->name('auth.register');
Route::post('/register', [RegisterController::class, 'register'])->middleware(['guest', 'throttle:auth-sensitive'])->name('register');

/* Recuperar contraseña */
Route::get('/forget-password', [ForgotPasswordController::class, 'ForgetPassword'])->name('ForgetPasswordGet');
Route::post('/forget-password', [ForgotPasswordController::class, 'ForgetPasswordStore'])->middleware('throttle:auth-sensitive')->name('ForgetPasswordPost');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'ResetPassword'])->name('ResetPasswordGet');
Route::post('/reset-password', [ForgotPasswordController::class, 'ResetPasswordStore'])->middleware('throttle:auth-sensitive')->name('ResetPasswordPost');

/* Usuario autenticado */
Route::middleware('auth')->group(function () {
    /* confirmar el correo de la cuenta */
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->whereNumber('id')->middleware('signed')->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    /* favoritos y recomendaciones */
    Route::post('/favorite/{id}', [MoviesController::class, 'addmovie'])->whereNumber('id')->middleware('throttle:favorites')->name('components.favorite');
    Route::delete('/dislike/{id}', [MoviesController::class, 'removemovie'])->whereNumber('id')->middleware('throttle:favorites')->name('components.dislike');
    Route::post('/favoriteactor/{id}', [MoviesController::class, 'addactor'])->whereNumber('id')->middleware('throttle:favorites')->name('components.favoritee');
    Route::delete('/dislikeactor/{id}', [MoviesController::class, 'removeactor'])->whereNumber('id')->middleware('throttle:favorites')->name('components.dislikee');
    Route::delete('/my-recomendation/dislike/{id}', [HomeController::class, 'dislikemovie'])->whereNumber('id')->middleware('throttle:favorites')->name('components.remove');

    /* perfil */
    Route::patch('/update/{id}', [HomeController::class, 'update'])->whereNumber('id')->name('components.update');
    Route::patch('/updategenres/{id}', [HomeController::class, 'updateGenre'])->whereNumber('id')->name('components.genres');

    Route::get('/edit-my-profile', [HomeController::class, 'index'])->name('profile.my-profile');
    Route::get('/my-favorites-movies', [HomeController::class, 'myFavoritesMovies'])->name('profile.my-movies');
    Route::get('/my-favorites-actors', [HomeController::class, 'myFavoritesActors'])->name('profile.my-actors');
    Route::get('/my-favorites-genres', [HomeController::class, 'myFavoritesGenres'])->name('profile.my-genres');
    Route::get('/my-recomendation', [HomeController::class, 'myRecomendation'])->name('profile.my-recomendation');
    Route::get('/edit-my-password', [HomeController::class, 'editPassword'])->name('profile.my-password');
    Route::post('/edit-my-password', [HomeController::class, 'myPassword'])->name('profile.my-password.update');
});

/* Administración: solo usuarios con users.is_admin */
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('profile.management');
    Route::get('/user/{id}', [AdminController::class, 'showUser'])->whereNumber('id')->name('users.show');
    Route::patch('/update-user/{id}', [AdminController::class, 'updateuser'])->whereNumber('id')->name('components.alert-change');
    Route::post('/forget-password-user', [AdminController::class, 'sendForgetPassword'])->name('forgetpassword.show');
    Route::delete('/delete/{id}', [AdminController::class, 'removeuser'])->whereNumber('id')->name('components.delete');
});
