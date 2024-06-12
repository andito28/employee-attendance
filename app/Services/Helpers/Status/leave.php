<?php

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
