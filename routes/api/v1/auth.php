<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegistrationController::class);
Route::post('login', [AuthenticationController::class, 'store']);
Route::middleware('auth:sanctum')->post('logout', [AuthenticationController::class, 'destroy']);
