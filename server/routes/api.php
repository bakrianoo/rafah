<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    return $request->user();
});

//Public routes
Route::get('/login/{provider}', [AuthController::class,'redirectToProvider']);
Route::get('/login/{provider}/callback', [AuthController::class,'handleProviderCallback']);

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

//Protected routes
Route::group(['middleware'=>['auth:sanctum', 'custom_auth']], function () {
    Route::post('/logout', [AuthController::class,'logout']);
});
