<?php

namespace App\Algorithms\Employee;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Employee\User;
use App\Models\Employee\Sibling;
use App\Models\Employee\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\Constant\RoleUser;
use App\Models\Employee\Resignation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Services\Constant\StatusEmployee;
use App\Services\Number\Generator\EmployeeNumber;
use App\Services\Constant\Activity\ActivityAction;
use App\Http\Requests\Employee\CreateEmployeeRequest;

class EmployeeAlgo
{
    public function __construct(public ? Employee $employee = null)
    {
    }


    public function create(CreateEmployeeRequest $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $user = auth()->user();

                $createdBy = [
                    'createdBy' =>  $user->employee->id,
                    'createdByName' =>  $user->employee->name
                ];

                $this->checkExistingEmployeeAndResignation($request);

                $this->employee = $this->createEmployee($request,$createdBy);

                $this->employee->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$this->employee->getTable() . ":$this->employee->name [$this->employee->id]");

            });

            return success($this->employee);

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    public function update(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $this->employee->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $this->validateUniqueEmail($request);

                $this->updateEmployee($request);

                $this->employee->setActivityPropertyAttributes(ActivityAction::UPDATE)
                ->saveActivity("Update employee : {$this->employee->name} [{$this->employee->id}]");
            });

            return success($this->employee->fresh());

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    public function delete()
    {
        try {

            DB::transaction(function (){

                $this->employee->setOldActivityPropertyAttributes(ActivityAction::DELETE);

                $this->employee->delete();

                $this->employee->setActivityPropertyAttributes(ActivityAction::DELETE)
                ->saveActivity("Delete employee : {$this->employee->name} [{$this->employee->id}]");

            });

            return success();

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    public function promoteToAdministrator()
    {
        try {

            DB::transaction(function (){

                $this->employee->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $this->savePromoteAdmin($this->employee->id);

                $this->employee->setActivityPropertyAttributes(ActivityAction::UPDATE)
                ->saveActivity("Update employee : {$this->employee->name} [{$this->employee->id}]");

            });

            return success($this->employee->fresh());

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    /** --- SUB FUNCTIONS --- */

    private function  checkExistingEmployeeAndResignation($request)
    {
        $existingEmployee = Employee::where('statusId',StatusEmployee::ACTIVE_ID)
        ->whereHas('user', function($query) use ($request) {
            $query->where('email', $request->email);
        })->first();

        if($existingEmployee){
            errEmployeeEmailAlreadyExists();
        }

        $existingEmployeeResigned = Employee::where('statusId',StatusEmployee::RESIGNED_ID)
        ->whereHas('user', function($query) use ($request) {
            $query->where('email', $request->email);
        })->first();

        if ($existingEmployeeResigned) {
            $resignation = Resignation::where('employeeId', $existingEmployeeResigned->id)
                ->where('date', '>', Carbon::now()->subYear())
                ->first();

            if(!$resignation){
                errEmployeeResignExists();
            }
        }
    }


    private function createEmployee($request,$createdBy)
    {
        $filePath = $this->savePhoto($request);

        $dataInput = [
            "name" => $request ->name,
            "number" => EmployeeNumber::generate(),
            "companyOfficeId" => $request->companyOfficeId,
            "departmentId" => $request->departmentId,
            "photo" => $filePath,
            "phone" => $request->phone,
            "address" => $request->address,
            "statusId" => StatusEmployee::ACTIVE_ID
        ];

        $employee = Employee::create($dataInput + $createdBy);

        $employee->saveUser($request->only(['email', 'password']));
        $employee->saveParent($request);
        $employee->saveSiblings($request,$createdBy);

        return $employee;
    }


    private function savePhoto($request)
    {
        $file = $request->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public/employee', $fileName);
        return $filePath;
    }


    private function validateUniqueEmail($request)
    {
        $existingUser = User::where('email', $request->email)
        ->where('employeeId', '!=', $this->employee->user->employeeId)
        ->first();

        if($existingUser){
            errEmployeeEmailAlreadyExists();
        }
    }


    private function updateEmployee($request)
    {
        if($request->file('photo')){
            Storage::delete($this->employee->photo);
            $filePath = $this->savePhoto($request);
        }else{
            $filePath = $this->employee->photo;
        }

        $dataInput = [
            "name" => $request ->name,
            "companyOfficeId" => $request->companyOfficeId,
            "departmentId" => $request->departmentId,
            "photo" => $filePath,
            "phone" => $request->phone,
            "address" => $request->address,
        ];

        $this->employee->update($dataInput);
        $this->employee->saveUser($request->only(['email']));
        $this->employee->saveParent($request);
        $this->employee->saveSiblings($request);
    }


    private function savePromoteAdmin($employeeId)
    {
        User::where('employeeId', $employeeId)->update([
            'roleId' => RoleUser::ADMINISTRATOR_ID
        ]);
    }

}
