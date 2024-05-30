<?php

namespace App\Algorithms\v1\Component;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\v1\Component\Department;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\Activity\ActivityAction;

class ComponentAlgo
{

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


    public function mappingOfficeDepartment($model, Request $request,$id)
    {
        try {

            $components = DB::transaction(function () use ($model, $request, $id) {

                $createdComponents = collect($request->departmentId)->map(function($data) use ($model, $id) {

                    $departmentExist = Department::find($data);
                    if (!$departmentExist) {
                        errComponentDepartmentGet();
                    }

                    $officeDepartmentExists = $model::where('companyOfficeId', $id)
                        ->where('departmentId', $data)
                        ->exists();

                    if ($officeDepartmentExists) {
                        errComponenOfficetDepartmentExists();
                    }

                    $component = $model::create([
                        'companyOfficeId' => $id,
                        'departmentId' => $data
                    ]);

                    $component->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " . $component->getTable() . ":$component->name [$component->id]");

                    return $component;

                })->filter();

                return $createdComponents;
            });

            $componentsData = $model::whereIn('id', $components->pluck('id'))->get();

            return success($componentsData);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }




}
