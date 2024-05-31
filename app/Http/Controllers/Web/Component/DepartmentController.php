<?php

namespace App\Http\Controllers\Web\Component;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Component\Department;
use App\Algorithms\Component\ComponentAlgo;
use App\Models\Component\CompanyOfficeDepartment;
use App\Http\Requests\component\ComponentRequest;

class DepartmentController extends Controller
{
    public function get(Request $request)
    {
        $departments = Department::getOrPaginate($request, true);
        return success($departments);
    }


    public function create(ComponentRequest $request)
    {
        $algo = new ComponentAlgo();
        return $algo->createBy(Department::class, $request);
    }

    public function update($id, ComponentRequest $request)
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

        $algo = new ComponentAlgo();
        return $algo->delete($department);
    }
}
