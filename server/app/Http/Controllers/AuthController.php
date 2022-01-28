<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\ClientException; 
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request) {
        // Validate the request...
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->device_name)->plainTextToken;
            return  response()->json(['status'=>true, 'token'=>$token]);
        }

        return response()->json(['status'=>false, 'message'=>'Invalid credentials'])
                         ->setStatusCode(401, 'Unauthorized');
    }

    public function logout(Request $request){
        // logout the user
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function social_redirect($provider) {
        return response()->json([
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function social_login($provider){
        $social_user = Socialite::driver($provider)->stateless()->user();
        
        $user = User::where('social_uuid', $social_user->id)
                     ->where('social_provider', $provider)->first();

        if ($user) {
            $user->update([
                'social_token' => $social_user->token,
                'social_refresh_token' => $social_user->refreshToken,
            ]);
        } else {
            $user = User::create([
                'name' => $social_user->name,
                'email' => $social_user->email,
                'social_provider' => $provider,
                'social_uuid' => $social_user->id,
                'social_token' => $social_user->token,
                'social_refresh_token' => $social_user->refreshToken,
            ]);
        }

        $token = $user->createToken($provider)->plainTextToken;
        return view('social_login', [
            'name' => $user->name,
            'token' => $token,
        ]);
    }


}