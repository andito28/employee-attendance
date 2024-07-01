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
use App\Services\Constant\Employee\StatusEmployee;
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
                    ->saveActivity("Enter new resignation : [{$resignation->id}]");

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
                ->saveActivity("Update status resignation : {$this->employee->name} [{$this->employee->id}]");

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

    private function checkRequestDateResign($request)
    {
        $today = Carbon::now();
        $requestedDate = Carbon::parse($request->date);
        $oneMonthFromNow = $today->copy()->addMonth();

        if ($requestedDate->lt($today) || $requestedDate->lt($oneMonthFromNow)){
            errEmployeeDateResign("Cek tanggal resign, minimal 1 bulan sebelumnya");
        }

        $resignation = Resignation::where('employeeId',  $this->employee->id)
                ->where('date', '>=', Carbon::now()->subYear())
                ->latest();

        if($resignation){
            errEmployeeResignExists();
        }
    }

    private function createResignation($request,$createdBy)
    {
        $this->checkRequestDateResign($request);

        $filePath = $this->saveFile($request);

        $resignation = Resignation::create([
            'employeeId' => $this->employee->id,
            'date' => $request->date,
            'reason' => $request->reason,
            'file' => $filePath
        ]+$createdBy);
        return $resignation;
    }

    private function checkDateStatusResignation()
    {
        if($this->employee->statusId == StatusEmployee::RESIGNED_ID)
        {
            $resignation = Resignation::where('employeeId',  $this->employee->id)
                ->where('date', '>=', Carbon::now()->subYear())
                ->first();

            if(!$resignation){
                errEmployeeResignExists("Resign sudah lebih dari satu tahun");
            }
        }else{
            errEmployeeNotResign();
        }
    }

    private function updateResignation()
    {
        Employee::where('id', $this->employee->id)->update([
            'statusId' => StatusEmployee::ACTIVE_ID
        ]);
    }

}
