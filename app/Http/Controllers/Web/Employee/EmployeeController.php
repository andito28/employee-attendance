<?php

namespace App\Http\Controllers\Web\Employee;

use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use App\Http\Controllers\Controller;
use App\Parser\Employee\EmployeeParser;
use App\Algorithms\Employee\EmployeeAlgo;
use App\Algorithms\Employee\ResignationAlgo;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Http\Requests\Employee\ResignationRequest;
use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function get(Request $request)
    {
        $employee = Employee::with('user','parental','siblings')->get();
        return success(EmployeeParser::getEmployee($employee));
    }

    public function create(CreateEmployeeRequest $request)
    {
        $algo = new EmployeeAlgo();
        return $algo->create($request);
    }

    public function update($id,UpdateEmployeeRequest $request)
    {
        $employee = Employee::find($id);
        if(!$employee){
            errEmployeeGet();
        }

        $algo = new EmployeeAlgo($employee);
        return $algo->update($request);
    }

    public function delete($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet();
        }

        $algo = new EmployeeAlgo($employee);
        return $algo->delete();
    }

    public function promoteToAdministrator($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet($employee);
        }

        $algo = new EmployeeAlgo($employee);
        return $algo->promoteToAdministrator();
    }

    public function resignation(ResignationRequest $request,$id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet();
        }

        $algo = new ResignationAlgo($employee);
        return $algo->create($request);
    }

    public function reverseResignationStatus($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            errEmployeeGet();
        }

        $algo = new ResignationAlgo($employee);
        return $algo->reverseResignationStatus();
    }
}
