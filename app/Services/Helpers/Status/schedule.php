<?php

if (!function_exists("errScheduleInvalidType")) {
    function errScheduleInvalidType($internalMsg = "")
    {
        error(400, "Invalid scheduleable type!", $internalMsg);
    }
}


if (!function_exists("errScheduleAlreadyExist")) {
    function errScheduleAlreadyExist($internalMsg = "")
    {
        error(409, "Schedule Already Exists!", $internalMsg);
    }
}
?>
