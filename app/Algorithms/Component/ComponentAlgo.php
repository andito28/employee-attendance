<?php

namespace App\Algorithms\Component;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\Activity\ActivityAction;

class ComponentAlgo
{
    public function createBy($model, Request $request)
    {
        try {

            $component = DB::transaction(function () use ($model, $request) {

                $user = auth()->user();
                $createdBy = [
                    'createdBy' =>  $user->employee->id,
                    'createdByName' =>  $user->employee->name
                ];

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

    public function update(Model $model, Request $request)
    {
        try {

            DB::transaction(function () use ($model, $request) {

                $model->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $model->update($request->all());

                $model->setActivityPropertyAttributes(ActivityAction::UPDATE)
                    ->saveActivity("Update " . $model->getTable() . ": $model->name [$model->id]");

            });

            return success($model->fresh());

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    public function delete(Model $model)
    {
        try {

            DB::transaction(function () use ($model) {

                $model->setOldActivityPropertyAttributes(ActivityAction::DELETE);

                $model->delete();

                $model->setActivityPropertyAttributes(ActivityAction::DELETE)
                    ->saveActivity("Delete " . $model->getTable() . ": $model->name [$model->id]");

            });

            return success();

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

}
