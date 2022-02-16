<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PassportAuthenticationController;
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

Route::post('register', [PassportAuthenticationController::class, 'register'])->name('api.register');
Route::post('login', [PassportAuthenticationController::class, 'login'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::post('refresh-token', [PassportAuthenticationController::class, 'refreshToken'])->name('api.refresh-token');
    Route::post('logout', [PassportAuthenticationController::class, 'logout'])->name('api.logout');
    Route::apiResource('employees', EmployeeController::class);
});
