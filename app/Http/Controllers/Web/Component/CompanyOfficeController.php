<?php

namespace App\Http\Controllers\Web\Component;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Component\CompanyOffice;
use App\Algorithms\Component\ComponentAlgo;
use App\Parser\Component\CompanyOfficeParser;
use App\Algorithms\Component\CompanyOfficeAlgo;
use App\Models\Component\CompanyOfficeDepartment;
use App\Http\Requests\Component\CompanyOfficeRequest;
use App\Http\Requests\component\OfficeDepartmentRequest;

class CompanyOfficeController extends Controller
{
    public function get(Request $request)
    {
        $companyOffices = CompanyOffice::filter($request)->getOrPaginate($request, true);
        return success($companyOffices);
    }


    public function create(CompanyOfficeRequest $request)
    {
        $algo = new ComponentAlgo();
        return $algo->createBy(CompanyOffice::class, $request);
    }

    public function update($id, CompanyOfficeRequest $request)
    {
        $companyOffice = CompanyOffice::find($id);
        if (!$companyOffice) {
            errComponentCompanyOfficeGet();
        }

        $algo = new ComponentAlgo();
        return $algo->update($companyOffice, $request);
    }


    public function delete($id)
    {
        $companyOffice = CompanyOffice::find($id);
        if (!$companyOffice) {
            errComponentCompanyOfficeGet();
        }

        $algo = new ComponentAlgo();
        return $algo->delete($companyOffice);
    }


    public function getDepartment($id){

        $companyOffice = CompanyOffice::with('departments')->find($id);
        if (!$companyOffice) {
            errComponentCompanyOfficeGet();
        }
        return success(CompanyOfficeParser::getDepartments($companyOffice));
    }


    public function saveOfficeDepartmentMapping($id,OfficeDepartmentRequest $request){

        $companyOffice = CompanyOffice::find($id);
        if (!$companyOffice) {
            errComponentCompanyOfficeGet();
        }

        $algo = new CompanyOfficeAlgo();
        return $algo->mappingOfficeDepartment(CompanyOfficeDepartment::class,$request,$id);

    }
}
