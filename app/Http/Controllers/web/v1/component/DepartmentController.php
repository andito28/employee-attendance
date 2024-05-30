<?php

namespace App\Http\Controllers\web\v1\component;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v1\Component\Department;
use App\Algorithms\v1\Component\ComponentAlgo;
use App\Models\v1\Component\CompanyOfficeDepartment;
use App\Http\Requests\v1\component\DepartmentRequest;

class DepartmentController extends Controller
{

    public function get(Request $request)
    {
        $departments = Department::getOrPaginate($request, true);
        return success($departments);
    }


    public function create(DepartmentRequest $request)
    {
        $algo = new ComponentAlgo();
        return $algo->createBy(Department::class, $request);
    }

    public function update($id, DepartmentRequest $request)
    {
        $department = Department::find($id);
        if (!$department) {
            errComponentDepartmentGet();
        }

        $algo = new ComponentAlgo();
        return $algo->update($department, $request);
    }


    public function delete($id)
    {
        $department = Department::find($id);
        if (!$department) {
            errComponentDepartmentGet();
        }

        $companyOfficeDepartment = CompanyOfficeDepartment::where('departmentId',$id)->exists();

        if ($companyOfficeDepartment) {
            errComponentDepartmentExists();
        }

        $algo = new ComponentAlgo();
        return $algo->delete($department);
    }


}
