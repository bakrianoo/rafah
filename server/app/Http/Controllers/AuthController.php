<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

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
}