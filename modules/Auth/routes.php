<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Controllers\AuthController;

Route::get('captcha/', function () {
    return [app('Captcha')::create('flat', true)];
})->withoutMiddleware('auth');

Route::post('change-pass', [AuthController::class, 'changePass']);
Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth');
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::get('me', [AuthController::class, 'me']);
