<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('force.json')->group(function () {
    Route::post('login', [AuthController::class, 'store']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users/create', [UserController::class, 'create'])->name('users.create');
        Route::put('users/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::patch('users/{id}/status', [UserController::class, 'setStatus'])->name('users.status');
    });
});
