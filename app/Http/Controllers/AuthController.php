<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\AdminAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class AuthController extends Controller
{
    public function redirectToGoogle(): SymfonyRedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();
        $email = $googleUser->getEmail();

        abort_unless(AdminAccess::emailIsAllowed($email), 403);

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: $email,
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'password' => Str::password(32),
            ]
        );

        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->intended(route('posts.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('posts.index');
    }
}
