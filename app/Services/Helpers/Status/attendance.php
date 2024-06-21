<?php

if (!function_exists("errAttendanceAlreadyExist")) {
    function errAttendanceAlreadyExist($internalMsg = "")
    {
        error(409, "Attendance Already Exists!", $internalMsg);
    }
}

if (!function_exists("errAttendanceCannotAbsent():")) {
    function errAttendanceCannotAbsent($internalMsg = "")
    {
        error(409, "cannot be absent!", $internalMsg);
    }
}



