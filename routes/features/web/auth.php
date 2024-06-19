<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\auth\AuthController;

//AUTH
Route::prefix("login")
    ->group(function () {
        Route::post("", [AuthController::class, "login"]);
    });



