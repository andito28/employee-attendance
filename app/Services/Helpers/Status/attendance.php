<?php

if (!function_exists("errAttendanceAlreadyExist")) {
    function errAttendanceAlreadyExist($internalMsg = "")
    {
        error(409, "Attendance Already Exists!", $internalMsg);
    }
}



