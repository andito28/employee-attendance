<?php

namespace App\Algorithms\Employee;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Employee\Sibling;
use App\Models\Employee\Parental;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Services\Constant\RoleUser;
use App\Models\Employee\Resignation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Services\Constant\StatusEmployee;
use App\Services\Constant\Activity\ActivityAction;
use App\Services\Number\Generator\Employee\EmployeeNumber;

class EmployeeAlgo
{
    public function create($model, Request $request)
    {
        try {

            $employee = DB::transaction(function () use ($model, $request) {
                $createdBy = [];

                if(Auth::check()){
                    $createdBy = [
                    'createdBy' => auth()->user()->employee->id,
                    'createdByName' => auth()->user()->employee->name
                    ];
                }

                $this->checkExistingEmployeeAndResignation($model,$request);

                $employee = $this->saveEmployee($model,$request,$createdBy);

                $employee->setActivityPropertyAttributes(ActivityAction::CREATE)
                    ->saveActivity("Enter new " .$employee->getTable() . ":$employee->name [$employee->id]");

                return $employee;

            });

            return success($employee);

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


    /** --- SUB FUNCTIONS --- */

    private function  checkExistingEmployeeAndResignation($model, $request){

        $existingEmployee = $model::where('statusId',StatusEmployee::ACTIVE_ID)
        ->whereHas('user', function($query) use ($request) {
            $query->where('email', $request->email);
        })->first();

        if($existingEmployee){
            errEmployeeAlreadyExists();
        }

        $existingEmployeeResigned = $model::withTrashed()
        ->where('statusId',StatusEmployee::RESIGNED_ID)
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


    private function saveEmployee($model,$request,$createdBy){

        $phone = isset($request->phone)? $request->phone: null;
        $address = isset($request->address)? $request->address: null;

        $filePath = $this->savePhoto($request);

        $dataInput = [
            "name" => $request ->name,
            "number" => EmployeeNumber::generate(),
            "companyOfficeId" => $request->companyOfficeId,
            "departmentId" => $request->departmentId,
            "photo" => $filePath,
            "phone" => $phone,
            "address" => $address,
            "statusId" => StatusEmployee::ACTIVE_ID
        ];

        $employee = $model::create($dataInput + $createdBy);

        $this->saveUser($request,$employee->id);
        $this->saveParentEmployee($request,$employee->id);
        $this->saveSiblingsEmployee($request,$employee->id,$createdBy);

        return $employee;
    }


    private function savePhoto($request){
        $file = $request->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public/employee', $fileName);
        return $filePath;
    }


    private function saveUser($request,$employeeId){
        User::create([
            'employeeId' => $employeeId,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleId' => RoleUser::EMPLOYEE_ID
        ]);
    }


    private function saveParentEmployee($request,$employeeId){
        Parental::create([
            'employeeId' => $employeeId,
            'fatherName' => $request->fatherName,
            'fatherPhone' => $request->fatherPhone,
            'fatherEmail' => $request->fatherEmail,
            'motherName' => $request->motherName,
            'motherPhone' => $request->motherPhone,
            'motherEmail' => $request->motherEmail,
        ]);
    }


    private function saveSiblingsEmployee($request,$employeeId,$createdBy){

        foreach($request->siblings as $value){

            $email = isset($value['email'])? $value['email']: null;
            $phone = isset($value['phone'])? $value['phone']: null;

            $dataInput = [
                'employeeId' => $employeeId,
                'name' => $value['name'],
                'email' => $email,
                'phone' => $phone
            ];


            Sibling::create($dataInput + $createdBy);
        }
    }

}
