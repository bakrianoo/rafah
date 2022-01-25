<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }

    public function login(Request $request) {
        // Validate the request...
        $user = User::where('email', $request->email)->first();
        // Check if user exists
        if (!$user) {
            return response()->json([
                'message' => 'User does not exist'
            ], 401);
        }
        // Check if password is correct
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect'
            ], 401);
        }
        // Generate token
        $token = $user->createToken($request->device_name)->plainTextToken;
        return response()->json([
            'token' => $token
        ], 200);

    }
}