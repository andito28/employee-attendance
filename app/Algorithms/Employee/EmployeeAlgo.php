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
use Illuminate\Support\Facades\Storage;
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

                $employee = $this->createEmployee($model,$request,$createdBy);

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

                $this->validateUniqueEmail($model,$request);

                $this->updateEmployee($model,$request);

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

                Storage::delete($model->photo);

                $model->delete();

                $model->setActivityPropertyAttributes(ActivityAction::DELETE)
                    ->saveActivity("Delete " . $model->getTable() . ": $model->name [$model->id]");

            });

            return success();

        } catch (\Exception $exception) {
            exception($exception);
        }
    }


    public function promoteToAdministrator(Model $model)
    {
        try {

            DB::transaction(function () use ($model) {

                $model->setOldActivityPropertyAttributes(ActivityAction::UPDATE);

                $this->savePromoteAdmin($model->id);

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
            errEmployeeEmailAlreadyExists();
        }

        $existingEmployeeResigned = $model::where('statusId',StatusEmployee::RESIGNED_ID)
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


    private function createEmployee($model,$request,$createdBy){

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

        $this->createUser($request,$employee->id);
        $this->createParentEmployee($request,$employee->id);
        $this->createSiblingsEmployee($request,$employee->id,$createdBy);

        return $employee;
    }


    private function savePhoto($request){
        $file = $request->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public/employee', $fileName);
        return $filePath;
    }


    private function createUser($request,$employeeId){
        User::create([
            'employeeId' => $employeeId,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleId' => RoleUser::EMPLOYEE_ID
        ]);
    }


    private function createParentEmployee($request,$employeeId){
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


    private function createSiblingsEmployee($request,$employeeId,$createdBy){

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


    private function validateUniqueEmail($model,$request)
    {
        $existingUser = User::where('email', $request->email)
        ->where('employeeId', '!=', $model->user->employeeId)
        ->first();

        if($existingUser){
            errEmployeeEmailAlreadyExists();
        }
    }


    private function updateEmployee($model,$request){

        if($request->file('photo')){
            Storage::delete($model->photo);
            $filePath = $this->savePhoto($request);
        }else{
            $filePath = $model->photo;
        }

        $phone = isset($request->phone)? $request->phone: null;
        $address = isset($request->address)? $request->address: null;

        $dataInput = [
            "name" => $request ->name,
            "companyOfficeId" => $request->companyOfficeId,
            "departmentId" => $request->departmentId,
            "photo" => $filePath,
            "phone" => $phone,
            "address" => $address,
        ];

        $model->update($dataInput);

        if($request->email){
            $this->updateUser($model->id,$request);
        }

        $this->updateParentEmployee($model->id,$request);
        $this->updateSiblingsEmployee($model->id,$request);
    }


    private function updateUser($employeeId,$request){
        User::where('employeeId', $employeeId)->update([
            'email' => $request->email
        ]);
    }


    private function updateParentEmployee($employeeId,$request){

        Parental::where('employeeId', $employeeId)->update([
            'fatherName' => $request->fatherName,
            'fatherPhone' => $request->fatherPhone,
            'fatherEmail' => $request->fatherEmail,
            'motherName' => $request->motherName,
            'motherPhone' => $request->motherPhone,
            'motherEmail' => $request->motherEmail,
        ]);

    }


    private function updateSiblingsEmployee($employeeId,$request){
        $processedSiblings = [];

        foreach ($request->siblings as $siblingData) {
            $siblingId = $siblingData['id'] ?? null;

            if ($siblingId) {
                $sibling = Sibling::where('id',$siblingId)
                ->where('employeeId',$employeeId)->first();
                    if (!$sibling) {
                        errEmployeeSiblingsGet(
                            "employee ID:".$employeeId." & "."sibling ID:". $siblingData['id']
                        );
                    }
            } else {
                $sibling = new Sibling();
            }

            $sibling->employeeId = $employeeId;
            $sibling->name = $siblingData['name'];
            $sibling->email = $siblingData['email'] ?? null;
            $sibling->phone = $siblingData['phone'] ?? null;
            $sibling->save();

            $processedSiblings[] = $sibling->id;
        }

        Sibling::where('employeeId', $employeeId)
            ->whereNotIn('id', $processedSiblings)
            ->delete();
    }


    private function savePromoteAdmin($employeeId){

        User::where('employeeId', $employeeId)->update([
            'roleId' => RoleUser::ADMINISTRATOR_ID
        ]);
    }

}
