<?php

namespace App\Algorithms\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Employee\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Employee\Resignation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\StatusEmployee;
use App\Services\Constant\Activity\ActivityAction;

class ResignationAlgo
{
    public function __construct(public ?Employee $employee = null)
    {
    }

    public function create($request)
    {
        try {
            $resignation = DB::transaction(function () use ($request) {

                $user = auth()->user();
                $createdBy = [
                'createdBy' =>   $user->employee->id,
                'createdByName' =>   $user->employee->name
                ];

                $resignation = $this->createResignation($request,$createdBy);

                $resignation->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " . $resignation->getTable() . ": [ $resignation->id]");

                return $resignation;

            });

            return success($resignation);
        } catch (\Exception $exception) {
            return exception($exception);
        }
    }

    public function reverseResignationStatus()
    {
        try {

            DB::transaction(function (){

                $this->employee->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $this->checkDateStatusResignation();

                $this->updateResignation();

                $this->employee->setActivityPropertyAttributes(ActivityAction::UPDATE)
                ->saveActivity("Update employee : {$this->employee->name} [{$this->employee->id}]");

            });

            return success($this->employee->fresh());

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

    /** --- SUB FUNCTIONS --- */

    private function saveFile($request)
    {
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public/resignation', $fileName);
        return $filePath;
    }

    private function createResignation($request,$createdBy)
    {
        $filePath = $this->saveFile($request);

        $dataInput = [
            'employeeId' => $this->employee->id,
            'date' => $request->date,
            'reason' => $request->reason,
            'file' => $filePath
        ];

        $resignation = Resignation::create($dataInput+$createdBy);
        return $resignation;
    }

    private function checkDateStatusResignation()
    {
        $resignation = Resignation::where('employeeId', $this->employee->id)
        ->latest()->first();

        $resignationDate = Carbon::create($resignation->date);
        $today = Carbon::now();

        $diffInYears = $resignationDate->diffInYears($today);
        if($diffInYears >= 1){
            errEmployeeResignExists();
        }
    }

    private function updateResignation()
    {
        Employee::where('id', $this->employee->id)->update([
            'statusId' => StatusEmployee::ACTIVE_ID
        ]);
    }


}
