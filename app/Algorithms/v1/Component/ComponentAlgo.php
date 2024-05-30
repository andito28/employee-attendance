<?php

namespace App\Algorithms\v1\Component;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\Activity\ActivityAction;

class ComponentAlgo
{
    /**
     * @param $model
     * @param Request $request
     *
     * @return JsonResponse|mixed
     */
    public function createBy($model, Request $request)
    {
        try {

            $component = DB::transaction(function () use ($model, $request) {

                $createdBy = [];

                $component = $model::create($request->all() + $createdBy);

                $component->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$component->getTable() . ":$component->name [$component->id]");

                return $component;

            });

            return success($component);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


}
