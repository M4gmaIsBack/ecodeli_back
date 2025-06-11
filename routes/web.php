<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Illuminate\Support\Str;
use App\Http\Controllers\MFAController;
Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->stateless()->redirect();
});

Route::get('/api/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->stateless()->user();

    $existingUser = Users::where('email', $githubUser->getEmail())->first();

    if ($existingUser) {
        Auth::login($existingUser);
        Session::put('user_id', $existingUser->id);

        if (!$existingUser->mfaSecret || !$existingUser->mfaSecret->is_verified) {
            return redirect('http://185.255.112.204:4040/mfa');
        }

        return redirect('http://185.255.112.204:4040/dashboard');
    }

    $newUser = Users::create([
        'email' => $githubUser->getEmail(),
        'password' => bcrypt(Str::random(16)),
        'authentification_2FA' => false,
    ]);

    Auth::login($newUser);
    Session::put('user_id', $newUser->id);

    return redirect('http://185.255.112.204:4040/mfa-setup');
});


Route::get('/', fn () => response()->json(["message" => "API fonctionne"]));

Route::post('/mfa/verify', [MFAController::class, 'verify']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/mfa/setup', [MFAController::class, 'setup']);
    Route::post('/mfa/reset/{id}', [MFAController::class, 'resetMFA']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->stateless()->redirect();
});

Route::get('/api/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = Users::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        ['password' => bcrypt(Str::random(16)), 'authentification_2FA' => false]
    );

    Auth::login($user);

    return redirect('http://185.255.112.204:4040/dashboard');
});
