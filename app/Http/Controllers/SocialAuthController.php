<?php

namespace App\Http\Controllers;

use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        $existingUser = User::where('email', $user->getEmail())->first();
        if ($existingUser) {
            Auth::login($existingUser, true);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'provider' => 'google',
                'provider_id' => $user->getId(),
                'avatar' => $user->getAvatar(),
            ]);

            Auth::login($newUser, true);
        }

        return redirect('/userhome/products');
    }
}
