<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\Auth\AuthController;

//AUTH
Route::prefix("login")
    ->group(function () {
        Route::post("", [AuthController::class, "login"]);
    });



