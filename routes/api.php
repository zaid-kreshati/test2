<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController_api;
use App\Http\Middleware\ValidateToken;
use App\Http\Controllers\api\PostController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes
Route::controller(AuthController_api::class)->group(function () {
    Route::post('/login', 'login')->name('api.login');
    Route::post('/register/user', 'register_user')->name('api.register.user');
    Route::post('/register/admin', 'register_admin')->name('api.register.admin');


    Route::post('/logout', 'logout')->middleware(ValidateToken::class,'auth:api')->name('api.logout');
});


Route::post('/post/store', [PostController::class, 'store'])->middleware('auth:api');
Route::post('/post/update/{id}', [PostController::class, 'update'])->middleware('auth:api');
Route::delete('/post/destory/{id}', [PostController::class, 'destroy'])->middleware('auth:api');





