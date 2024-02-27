<?php

use App\Http\Controllers\Admin\BalanceController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Route::middleware('api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    //AUTH ROUTES
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('signup',   [AuthController::class, 'signup']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    //ADMIN ROUTES
    Route::post('amount', [BalanceController::class, 'index']);
    Route::post('deposit', [BalanceController::class, 'store']);
});