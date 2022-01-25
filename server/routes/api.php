<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return ['status'=>true, 'user'=>$request->user()];
});

Route::post('register', [AuthController::class,'register']);
Route::any('login', [AuthController::class,'login']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class,'logout']);