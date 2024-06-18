<?php

namespace App\Algorithms\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\Constant\RoleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\StatusEmployee;
use App\Services\Constant\Activity\ActivityAction;

class AuthAlgo
{
    /**
     * @param $model
     * @param Request $request
     *
     * @return JsonResponse|mixed
     */

    public function login($model, Request $request)
    {
        try {

            $dataUser = DB::transaction(function () use ($model, $request) {

                $credentials = $request->only('email', 'password');

                if(!$token = auth()->attempt($credentials)) {
                    errAuthentication("email atau password salah");
                }

                $user = auth()->user();

                if ($user->employee->statusId !== StatusEmployee::ACTIVE_ID) {
                    errAuthentication("Akun karyawan tidak aktif");
                }

                $dataUser = [
                    'id' => $user->employee->id,
                    "name" => $user->employee->name,
                    "role" => RoleUser::display($user->roleId),
                    "accessToken"  => $token
                ];

                return $dataUser;

            });

            return success($dataUser);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


}
