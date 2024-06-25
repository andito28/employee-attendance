<?php

if (!function_exists("errPublicHolidayGet")) {
    function errPublicHolidayGet($internalMsg = "", $status = null)
    {
        error(404, "Public Holiday not found!", $internalMsg, $status);
    }
}

if (!function_exists("errPublicHolidayIsAssign")) {
    function errPublicHolidayIsAssign($internalMsg = "", $status = null)
    {
        error(403, "Public Holiday is already assigned!", $internalMsg, $status);
    }
}

if (!function_exists("errPublicHolidayAlreadyExist")) {
    function errPublicHolidayAlreadyExist($internalMsg = "")
    {
        error(409, "Public Holiday Date Already Exists!", $internalMsg);
    }
}

