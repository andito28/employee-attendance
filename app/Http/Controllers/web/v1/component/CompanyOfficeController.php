<?php

namespace App\Http\Controllers\web\v1\component;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\v1\Component\CompanyOffice;
use App\Algorithms\v1\Component\ComponentAlgo;
use App\Http\Requests\v1\Component\CompanyOfficeRequest;

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


}
