<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class GoogleAuthController extends Controller
{
    /**
     * Function: googleLogin
     * Description: This function will redirect to Google
     * @param NA
     * @return void
     */
    public function googleLogin()
    {
        $tenantId = tenant()->id;
    
        $state = base64_encode(json_encode([
            'tenant' => $tenantId,
        ]));
    
        return Socialite::driver('google')
            ->with(['state' => $state])
            ->redirectUrl(config('services.google.redirect')) // this points to localhost:8000
            ->redirect();
    }

    public function googleCallback(Request $request)
    {
        $state = json_decode(base64_decode($request->get('state')), true);
        $tenantId = $state['tenant'] ?? null;
    
        if (!$tenantId) {
            abort(403, 'Missing tenant ID');
        }
    
        tenancy()->initialize($tenantId);
    
        $googleUser = Socialite::driver('google')->stateless()->user();
    
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
            ]
        );
    
        Auth::login($user);
    
        return redirect('/dashboard');
    }
    
}

