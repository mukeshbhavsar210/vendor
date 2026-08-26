<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller {
    // Redirect to Google
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback() {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => bcrypt(Str::random(16)), // random password
                    'avatar' => $googleUser->avatar, // 👈 save profile picture URL
                    'avatar_color' => '#3b5998', // Facebook brand color
                ]
            );

            Auth::login($user);
            return redirect()->route('account.dashboard');

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Login failed');
        }
    }

    // Redirect to Facebook
    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    //Handle Facebook callback
    public function handleFacebookCallback() {
        try {
            $fbUser = Socialite::driver('facebook')
            ->fields(['id', 'name', 'email', 'picture'])
            ->scopes(['email', 'public_profile'])
            ->stateless()
            ->user();

            // Get the image URL (fallback to Graph API)
            $avatarUrl = $fbUser->avatar ?? "https://graph.facebook.com/{$fbUser->id}/picture?type=large";

            $user = User::firstOrCreate(
                ['email' => $fbUser->email],
                [
                    'name' => $fbUser->name,
                    'password' => bcrypt(Str::random(16)), 
                    'image' => $avatarUrl, // 👈 save profile picture URL
                    'avatar_color' => '#3b5998', // Facebook brand color
                ]
            );

            // ✅ Force update avatar if missing or new login
            if ($user->wasRecentlyCreated || empty($user->image) || str_contains($user->image, 'graph.facebook.com')) {
                try {
                    $imageContent = Http::get($avatarUrl)->body();
                    $fileName = $user->id . '.jpg';
                    $folderPath = 'uploads/profile/';
                    Storage::disk('public')->put($folderPath . $fileName, $imageContent);                  
                    $user->update(['image' => $fileName]);
                } catch (\Exception $imgError) {
                    Log::error('Facebook image download failed: ' . $imgError->getMessage());
                }
            }

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \Log::error('Facebook login failed: ' . $e->getMessage());
            return redirect('/')->with('error', 'Facebook login failed.');
        }
    }
    
}