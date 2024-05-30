<?php

if (!function_exists("errEmployeeGet")) {
    function errEmployeeGet($internalMsg = "")
    {
        error(404, "Employee not found!", $internalMsg);
    }
}

