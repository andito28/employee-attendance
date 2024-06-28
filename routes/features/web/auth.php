<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\AuthController;

//AUTH
Route::prefix("login")
    ->group(function () {
        Route::post("", [AuthController::class, "login"]);
    });



