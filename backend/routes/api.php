<?php

use App\Http\Controllers\{ArticlesController, AuthController, PreferenceController};
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('preference', [PreferenceController::class, 'index']);
    Route::put('preference', [PreferenceController::class, 'save']);

    Route::get('articles', [ArticlesController::class, 'get']);
});
