<?php

use App\Http\Controllers\OauthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\API\TaskApiController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})
    ->middleware('auth:sanctum');

Route::apiResource('tasks', TaskApiController::class)
    ->middleware('auth:sanctum');*/

Route::group(['prefix' => 'v1'], function () {
    Route::middleware(['auth:api'])->group(function () {
        Route::apiResource('tasks', TaskApiController::class);
    });

    Route::group(['prefix' => 'oauth'], function () {
        Route::post('register', [OauthController::class, 'register']);
        Route::post('login', [OauthController::class, 'login']);
    });
});
