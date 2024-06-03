<?php

if (!function_exists("errEmployeeGet")) {
    function errEmployeeGet($internalMsg = "", $status = null)
    {
        error(404, "Employee not found!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeResignExists")) {
    function errEmployeeResignExists($internalMsg = "", $status = null)
    {
        error(400, "Employee has resigned within the last year!", $internalMsg, $status);
    }
}

if (!function_exists("errEmployeeAlreadyExists")) {
    function errEmployeeAlreadyExists($internalMsg = "", $status = null)
    {
        error(400, "Employee Already exists!", $internalMsg, $status);
    }
}


