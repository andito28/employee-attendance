<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\v1\auth\AuthController;

//AUTH
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);






