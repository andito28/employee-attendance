<?php

if (!function_exists("errCorrectionGet")) {
    function errCorrectionGet($internalMsg = "", $status = null)
    {
        error(404, "Timesheet correction not found!", $internalMsg, $status);
    }
}

if (!function_exists("errCorrectionApproved")) {
    function errCorrectionApproved($internalMsg = "", $status = null)
    {
        error(404, "Approved not found!", $internalMsg, $status);
    }
}


if (!function_exists("errCorrectionDisapprove")) {
    function errCorrectionDisapprove($internalMsg = "", $status = null)
    {
        error(400, "The notes field is required!", $internalMsg, $status);
    }
}
