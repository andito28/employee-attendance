<?php

namespace App\Http\Controllers\Web\Employee;

use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use App\Http\Controllers\Controller;
use App\Algorithms\Employee\EmployeeAlgo;
use App\Http\Requests\Employee\EmployeeRequest;

class EmployeeController extends Controller
{
    public function get(Request $request){

    }

    public function create(EmployeeRequest $request){
        $algo = new EmployeeAlgo();
        return $algo->create(Employee::class,$request);
    }

    public function update($id,EmployeeRequest $request){
        $employee = Employee::find($id);
        if(!$employee){
            errEmployeeGet();
        }

        $algo = new EmployeeAlgo();
        return $algo->create(Employee::class,$request);
    }
}
