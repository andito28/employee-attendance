<?php

namespace App\Algorithms\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\Activity\ActivityAction;

class AuthAlgo
{
    /**
     * @param $model
     * @param Request $request
     *
     * @return JsonResponse|mixed
     */
    public function register($model, Request $request)
    {
        try {

            $user = DB::transaction(function () use ($model, $request) {

                $user = $model::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'roleId' => 2
                ]);

                return $user;

            });

            return success($user);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    public function login($model, Request $request)
    {
        try {

            $user = DB::transaction(function () use ($model, $request) {

                $credentials = $request->only('email', 'password');

                if(!$token = auth()->attempt($credentials)) {
                    errAuthentication();
                }

                $user = [
                    'userId' => auth()->user()->id,
                    "userName" => auth()->user()->name,
                    'accessToken'   => $token
                ];

                return $user;

            });

            return success($user);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


}
