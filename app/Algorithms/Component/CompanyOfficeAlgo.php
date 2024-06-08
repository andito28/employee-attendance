<?php

namespace App\Algorithms\Component;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Component\Department;
use Illuminate\Support\Facades\Auth;
use App\Models\Component\CompanyOffice;
use Illuminate\Database\Eloquent\Model;
use App\Models\Component\CompanyOfficeDepartment;
use App\Services\Constant\Activity\ActivityAction;

class CompanyOfficeAlgo
{
    public function __construct(public ? CompanyOffice $companyOffice = null)
    {
    }

    public function mappingOfficeDepartment(Request $request)
    {
        try {
            $mapping = DB::transaction(function () use ($request) {
                return collect($request->departmentIds)
                    ->map(function ($departmentId){
                        return $this->saveOfficeDepartmentComponent($departmentId);
                    })
                    ->filter();
            });

            $mappingData = CompanyOfficeDepartment::whereIn('id', $mapping->pluck('id'))->get();

            return success($mappingData);
        } catch (\Exception $exception) {
            return exception($exception);
        }
    }

    /** --- SUB FUNCTIONS --- */

    private function saveOfficeDepartmentComponent($departmentId)
    {
        if (!$this->departmentExists($departmentId)) {
            errComponentDepartmentGet();
        }

        return $this->createOfficeDepartment($departmentId);
    }

    private function departmentExists($departmentId)
    {
        return Department::find($departmentId) !== null;
    }

    private function createOfficeDepartment($departmentId)
    {
        $attributes = [
            'companyOfficeId' => $this->companyOffice->id,
            'departmentId' => $departmentId
        ];

        $user = auth()->user();

        $createdBy = [
            'createdBy' =>  $user->employee->id,
            'createdByName' =>  $user->employee->name
        ];

        $mapping = CompanyOfficeDepartment::updateOrCreate($attributes, $createdBy);

        $mapping->setActivityPropertyAttributes(ActivityAction::CREATE)
                ->saveActivity("Enter new mapping company office department" . ": [$mapping->id]");

        return $mapping;
    }
}
