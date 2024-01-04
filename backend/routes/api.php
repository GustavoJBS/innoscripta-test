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

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('preference', [PreferenceController::class, 'index'])->name('preference.index');
    Route::put('preference', [PreferenceController::class, 'save'])->name('preference.save');

    Route::get('articles/filters', [ArticlesController::class, 'getFilters']);

    Route::get('articles', [ArticlesController::class, 'get']);
});
