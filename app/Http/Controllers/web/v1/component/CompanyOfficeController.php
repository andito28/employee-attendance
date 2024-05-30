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


}
