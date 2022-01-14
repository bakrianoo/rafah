<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;


class AuthController extends Controller
{
    public function redirectToProvider($provider) {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider) {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        $userCreated = User::firstOrCreate(
            [
                'user_email' => $user->getEmail()
            ],
            [
                'user_email_verified_at' => now(),
                'user_name' => $user->getName(),
                'status' => true,
            ]
        );
        
        $userCreated->social_providers()->updateOrCreate(
            [
                'social_provider_name' => $provider,
                'social_provider_user_id' => $userCreated['id'],
                'social_provider_uuid' => $user['id'],
                'social_provider_meta' => $user->user,
            ],
            [
                'social_provider_avatar' => $user->getAvatar()
            ]
        );
        $token = $userCreated->createToken('token-name')->plainTextToken;
        return response()->json(['Access-Token' => $token]);
        return response()->json($userCreated, 200, ['Access-Token' => $token]);
    }

    protected function validateProvider($provider) {
        if (!in_array($provider, ['facebook', 'github', 'google'])) {
            return response()->json(['error' => 'Please login using facebook, github or google'], 422);
        }
    }

    public function register(Request $request) {
        $fields = $request->validate([
            "name" => 'required|string',
            "email" => 'required|string|unique:users,email',
            "password" => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt( $fields['password'] ),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            "email" => 'required|string',
            "password" => 'required|string',
        ]);

        // Check Email
        $where = ["email"=>$fields['email']];
        $user = User::where($where)->first();

        // Check Password
        if(!$user || !Hash::check($fields['password'], $user->password))
        {
        return response([
            'message' => "Bad credentials"
        ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'logged out'
        ];

        return response($response,201);
    }
}
