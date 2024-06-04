<?php

namespace App\Algorithms\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\Activity\ActivityAction;

class ResignationAlgo
{

    public function create($model, Request $request,$id)
    {
        try {
            $resignation = DB::transaction(function () use ($model, $request,$id) {
                $createdBy = [];

                if(Auth::check()){
                    $createdBy = [
                    'createdBy' => auth()->user()->employee->id,
                    'createdByName' => auth()->user()->employee->name
                    ];
                }

                $resignation = $this->createResignation($model,$request,$createdBy,$id);

                $resignation->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " . $resignation->getTable() . ": [ $resignation->id]");

                return $resignation;

            });

            return success($resignation);
        } catch (\Exception $exception) {
            return exception($exception);
        }
    }


    public function reverseResignationStatus($model,$id)
    {
        try {

            DB::transaction(function () use ($model,$id) {

                $model->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                // $this->updateResignation($model);

                $model->setActivityPropertyAttributes(ActivityAction::UPDATE)
                    ->saveActivity("Update " . $model->getTable() . ": $model->name [$model->id]");

            });

            return success($model->fresh());

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


    private function createResignation($model,$request,$createdBy,$id)
    {
        $filePath = $this->saveFile($request);

        $dataInput = [
            'employeeId' => $id,
            'date' => $request->date,
            'reason' => $request->reason,
            'file' => $filePath
        ];

        $resignation = $model::create($dataInput+$createdBy);
        return $resignation;
    }

}
