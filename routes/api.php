<?php

use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
Route::prefix('v1')->group(function () {

    Route::get('/test', [UserController::class, 'usersGet']);

    Route::get('/token', [TokenController::class, 'generateToken'])->name('token');
    Route::get('/users', [UserController::class, 'test'])->name('users.get');
    Route::post('/users', function () {
        return 1;
    })->name('users.post');
    Route::get('/users/{id}', function () {
        return 1;
    })->name('users.id');
    Route::get('/positions', [UserController::class, 'positions'])->name('positions');
});