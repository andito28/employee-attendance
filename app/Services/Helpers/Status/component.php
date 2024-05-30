<?php

if (!function_exists("errComponentDepartmentExists")) {
    function errComponentCompanyOfficeGet($internalMsg = "")
    {
        error(404, "Company office not found!", $internalMsg);
    }
}

if (!function_exists("errComponentDepartmentGet")) {
    function errComponentDepartmentGet($internalMsg = "")
    {
        error(404, "Department not found!", $internalMsg);
    }
}


if (!function_exists("errComponentDepartmentExists")) {
    function errComponentDepartmentExists($internalMsg = "")
    {
        error(403, "Cannot Delete Department!", $internalMsg);
    }
}

if (!function_exists("errComponenOfficetDepartmentExists")) {
    function errComponenOfficetDepartmentExists($internalMsg = "")
    {
        error(409, "Company Office Department Already Exists!", $internalMsg);
    }
}

