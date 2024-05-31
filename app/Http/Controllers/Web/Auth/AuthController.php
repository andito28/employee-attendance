<?php

namespace App\Http\Controllers\Web\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Algorithms\Auth\AuthAlgo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\LoginRequest;

class AuthController extends Controller
{
    public function register(LoginRequest $request){
        $algo = new AuthAlgo();
        return $algo->register(User::class,$request);
    }

    public function login(LoginRequest $request){
        $algo = new AuthAlgo();
        return $algo->login(User::class,$request);
    }
}
