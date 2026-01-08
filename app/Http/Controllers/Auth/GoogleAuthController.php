<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::query()->firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Seller'),
                'password' => bcrypt(str()->random(32)),
            ]
        );

        // OPTIONAL: kalau kamu pakai role seller
        // pastikan user jadi seller otomatis
        if (property_exists($user, 'role') && empty($user->role)) {
            $user->role = 'seller';
            $user->save();
        }

        Auth::login($user, true);

        return redirect()->route('seller.dashboard');
    }
}
