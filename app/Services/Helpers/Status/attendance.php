<?php

if (!function_exists("errAttendanceAlreadyExist")) {
    function errAttendanceAlreadyExist($internalMsg = "")
    {
        error(409, "Attendance Already Exists!", $internalMsg);
    }
}

if (!function_exists("errAttendanceTimeRange")) {
    function errAttendanceTimeRange($internalMsg = "")
    {
        error(400, "unable to take attendance", $internalMsg);
    }
}

