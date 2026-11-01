<?php

use App\Http\Controllers\Api\PostApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*Route::get('posts', [PostApiController::class, 'index']);
Route::get('posts/{post}', [PostApiController::class, 'show']);*/

//Route::apiResource('posts', PostApiController::class);
