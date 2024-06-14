<?php

if (!function_exists("errScheduleInvalidType")) {
    function errScheduleInvalidType($internalMsg = "")
    {
        error(400, "Invalid schedule type!", $internalMsg);
    }
}

if (!function_exists("errScheduleLeave")) {
    function errScheduleLeave($internalMsg = "")
    {
        error(403, "Schedule leave cannot be updated!", $internalMsg);
    }
}
