<?php

if (!function_exists("errLeaveGet")) {
    function errLeaveGet($internalMsg = "")
    {
        error(404, "Leave Not Found!", $internalMsg);
    }
}

if (!function_exists("errLeaveValidateDate")) {
    function errLeaveValidateDate($internalMsg = "")
    {
        error(400, "Invalid Date!", $internalMsg);
    }
}


if (!function_exists("errLeaveDurationMax")) {
    function errLeaveDurationMax($internalMsg = "")
    {
        error(400, "Invalid Date!", $internalMsg);
    }
}

if (!function_exists("errLeaveApprove")) {
    function errLeaveApprove($internalMsg = "", $status = null)
    {
        error(403, "Unauthorized access!", $internalMsg, $status);
    }
}

if (!function_exists("errLeaveAlreadyApprove")) {
    function errLeaveAlreadyApprove($internalMsg = "", $status = null)
    {
        error(400, "Leave Already Approved!", $internalMsg, $status);
    }
}
