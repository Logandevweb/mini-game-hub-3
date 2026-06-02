<?php

use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) { return $request->user(); });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/scores', [ScoreController::class, 'store']);
    Route::get('/scores/user', [ScoreController::class, 'userScores']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/update', [UserController::class, 'update']);
    Route::delete('/user/delete-self', [UserController::class, 'destroySelf']);


});
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::delete('/admin/users/{id}',[UserController::class, 'destroy']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{slug}/scores', [ScoreController::class, 'bestScores']);
Route::get('/scores/all', [ScoreController::class, 'allScores']);
Route::post('/forgot-password', [PasswordController::class, 'forgot']);
Route::post('/reset-password', [PasswordController::class, 'reset']);
