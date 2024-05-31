<?php

namespace App\Algorithms\Component;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Component\Department;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\Activity\ActivityAction;

class CompanyOfficeAlgo
{

    public function mappingOfficeDepartment($model, Request $request, $id)
    {
        try {
            $components = DB::transaction(function () use ($model, $request, $id) {
                return collect($request->departmentIds)
                    ->map(function ($departmentId) use ($model, $id) {
                        return $this->saveOfficeDepartmentComponent($model, $id, $departmentId);
                    })
                    ->filter();
            });

            $componentsData = $model::whereIn('id', $components->pluck('id'))->get();

            return success($componentsData);
        } catch (\Exception $exception) {
            return exception($exception);
        }
    }


    /** --- SUB FUNCTIONS --- */

    private function saveOfficeDepartmentComponent($model, $companyOfficeId, $departmentId)
    {
        if (!$this->departmentExists($departmentId)) {
            errComponentDepartmentGet();
        }

        return $this->createOfficeDepartment($model, $companyOfficeId, $departmentId);
    }

    private function departmentExists($departmentId)
    {
        return Department::find($departmentId) !== null;
    }

    private function createOfficeDepartment($model, $companyOfficeId, $departmentId)
    {
        $attributes = [
            'companyOfficeId' => $companyOfficeId,
            'departmentId' => $departmentId
        ];
        $createdBy = [];
        // $createdBy = [
        //     'createdBy' => auth()->user()->id,
        //     'createdByName' => auth()->user()->employee->name
        // ];

        $component = $model::updateOrCreate($attributes, $createdBy);

        $component->setActivityPropertyAttributes(ActivityAction::CREATE)
                ->saveActivity("Enter new " . $component->getTable() . ": $component->name [$component->id]");
        return $component;
    }
}
