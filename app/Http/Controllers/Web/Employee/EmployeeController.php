<?php

namespace App\Http\Controllers\Web\Employee;

use Illuminate\Http\Request;
use App\Jobs\DeleteAttendances;
use App\Models\Employee\Employee;
use App\Http\Controllers\Controller;
use App\Models\Employee\Resignation;
use App\Algorithms\Employee\EmployeeAlgo;
use App\Algorithms\Employee\ResignationAlgo;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Http\Requests\Employee\ResignationRequest;

class EmployeeController extends Controller
{
    public function get(Request $request)
    {
        $employee = Employee::filter($request)->getOrPaginate($request, true);
        return success($employee);
    }


    public function create(EmployeeRequest $request)
    {
        $algo = new EmployeeAlgo();
        return $algo->create(Employee::class,$request);
    }


    public function update($id,EmployeeRequest $request)
    {
        $employee = Employee::find($id);
        if(!$employee){
            errEmployeeGet();
        }

        $algo = new EmployeeAlgo();
        return $algo->update($employee,$request);
    }


    public function delete($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet();
        }

        $algo = new EmployeeAlgo();
        return $algo->delete($employee);
    }


    public function promoteToAdministrator($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet();
        }

        $algo = new EmployeeAlgo();
        return $algo->promoteToAdministrator($employee);
    }


    public function deleteAttendances($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet();
        }

        DeleteAttendances::dispatch($employee);
    }


    public function resignation(ResignationRequest $request,$id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet();
        }

        $algo = new ResignationAlgo();
        return $algo->create(Resignation::class,$request,$id);
    }


    // public function reverseResignationStatus($id){
    //     $employee = Employee::find($id);
    //     if (!$employee) {
    //         errEmployeeGet();
    //     }

    //     $algo = new ResignationAlgo();
    //     return $algo->reverseResignationStatus(Resignation::class,$id);
    // }
}
