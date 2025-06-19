<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                Auth::login($user);
                return redirect()->intended('/admin');
            }

            // Check if user exists with this email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Link Google account to existing user
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
                Auth::login($user);
                return redirect()->intended('/admin');
            }

            // Check if user has admin access (wolberg.pro email)
            if (!str_ends_with($googleUser->getEmail(), '@wolberg.pro')) {
                return redirect('/admin/login')
                    ->withErrors(['email' => 'You do not have admin access.']);
            }

            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)), // Random password
                'email_verified_at' => now(),
            ]);

            Auth::login($user);
            return redirect()->intended('/admin');

        } catch (\Exception $e) {
            return redirect('/admin/login')
                ->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }
    }
}
