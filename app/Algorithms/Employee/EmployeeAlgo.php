<?php

namespace App\Algorithms\Employee;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Employee\User;
use App\Models\Employee\Sibling;
use App\Models\Employee\Employee;
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
use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Services\Number\Generator\Employee\EmployeeNumber;

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

    private function  checkExistingEmployeeAndResignation($request){

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


    private function createEmployee($request,$createdBy){

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

            $dataInput = [
                'employeeId' => $employeeId,
                'name' => $value['name'],
                'email' => $value['email'] ?? null,
                'phone' => $value['phone'] ?? null
            ];

            Sibling::create($dataInput + $createdBy);
        }
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


    private function updateEmployee($request){

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

        if($request->email){
            $this->updateUser($this->employee->id,$request);
        }

        $this->updateParentEmployee($this->employee->id,$request);
        $this->updateSiblingsEmployee($this->employee->id,$request);
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
