<?php

namespace App\Algorithms\v1\Auth;

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

                $user->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$user->getTable() . ":$user->name [$user->id]");

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

                $token = auth()->attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ]);

                if (!$token) {
                    return ['error' => 'Tidak Terautentikasi'];
                }

            });

            return success($user);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


}
