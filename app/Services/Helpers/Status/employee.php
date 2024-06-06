<?php

if (!function_exists("errEmployeeGet")) {
    function errEmployeeGet($internalMsg = "", $status = null)
    {
        error(404, "Employee not found!", $internalMsg, $status);
    }
}


if (!function_exists("errEmployeeEmailAlreadyExists")) {
    function errEmployeeEmailAlreadyExists($internalMsg = "", $status = null)
    {
        error(400, "Employee email Already exists!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeSiblingsGet")) {
    function errEmployeeSiblingsGet($internalMsg = "", $status = null)
    {
        error(404, "Siblings not found!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeResignExists")) {
    function errEmployeeResignExists($internalMsg = "", $status = null)
    {
        error(400, "Employee has resigned within the last year!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeNotResign")) {
    function errEmployeeNotResign($internalMsg = "", $status = null)
    {
        error(400, "Employees are still active!!", $internalMsg, $status);
    }
}



