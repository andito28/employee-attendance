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
        error(400, "Employees are still active!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeNotActive")) {
    function errEmployeeNotActive($internalMsg = "", $status = null)
    {
        error(400, "employee has resigned!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeDateResign")) {
    function errEmployeeDateResign($internalMsg = "", $status = null)
    {
        error(400, "Date Resignation invalid!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeResetPasswordUnauthorized")) {
    function errEmployeeResetPasswordUnauthorized($internalMsg = "", $status = null)
    {
        error(403, "Unauthorized access!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeExistingPassword")) {
    function errEmployeeExistingPassword($internalMsg = "", $status = null)
    {
        error(400, "Existing password does not match!", $internalMsg, $status);
    }
}




