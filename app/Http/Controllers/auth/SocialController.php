<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();

            $authUser = $this->findOrCreateUser($socialUser);
            Auth::login($authUser);

            return redirect()->route('home')->with('toastr', [
                'status' => 'success',
                'message' => 'Login Successfully !'
            ]);

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['msg' => 'Google login failed: ' . $e->getMessage()]);
        }
    }

    protected function findOrCreateUser($socialUser)
{
    // Tìm theo google_id
    $user = User::where('google_id', $socialUser->getId())->first();

    // Nếu chưa có thì tìm theo email
    if (!$user && $socialUser->getEmail()) {
        $user = User::where('email', $socialUser->getEmail())->first();

        // Nếu có user theo email thì cập nhật google_id
        if ($user) {
            $user->google_id = $socialUser->getId();
            $user->save();
        }
    }

    // Phải kiểm tra lại $user sau bước trên
    if (!$user) {
        $user = User::create([
            'username' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Unknown User',
            'email' => $socialUser->getEmail(),
            'google_id' => $socialUser->getId(),
            'password' => bcrypt('social_login_' . uniqid()), 
        ]);
    }

    return $user;
}

}
