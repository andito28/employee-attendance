<?php

namespace App\Http\Controllers\web\v1\auth;

use App\Models\v1\User\User;
use Illuminate\Http\Request;
use App\Algorithms\v1\Auth\AuthAlgo;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;

class AuthController extends Controller
{
    public function register(Request $request){
        $algo = new AuthAlgo();
        return $algo->register(User::class,$request);
    }

    public function login(LoginRequest $request){
        $algo = new AuthAlgo();
        return $algo->login(User::class,$request);
    }
}
