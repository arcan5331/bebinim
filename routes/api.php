<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'controller' => \App\Http\Controllers\AuthController::class
], function () {
    Route::post('check-phone', 'checkPhoneNumber');
    Route::post('register', 'register');
    Route::post('login', 'userLogin');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
