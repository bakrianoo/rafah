<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $bearer = $request->bearerToken();
        // if ($token = DB::table('personal_access_tokens')->where('token',hash('sha256',$bearer))->first())
        // {
        //     if ($user = User::find($token->tokenable_id))
        //     {
        //         Auth::login($user);
        //         return $next($request);
        //     }
        // }

        return response()->json([
            'success' => false,
            'error' => 'Access denied.',
        ]);

        // return $next($request);
    }
}