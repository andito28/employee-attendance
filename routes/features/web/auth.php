<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\auth\AuthController;

//AUTH
Route::post('login', [AuthController::class, 'login']);






