<?php

//Timesheet

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

//Timesheet correction

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

//shift

if (!function_exists("errShiftGet")) {
    function errShiftGet($internalMsg = "", $status = null)
    {
        error(404, "Shift not found!", $internalMsg, $status);
    }
}

//Public Holiday

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

//Leave

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

if (!function_exists("errLeaveApproveUnauthorized")) {
    function errLeaveApproveUnauthorized($internalMsg = "", $status = null)
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

if (!function_exists("errLeaveDelete")) {
    function errLeaveDelete($internalMsg = "")
    {
        error(403, "Cannot Delete Leave!", $internalMsg);
    }
}

//Schedule

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

if (!function_exists("errScheduleLeave")) {
    function errScheduleLeave($internalMsg = "")
    {
        error(403, "Schedule leave cannot be updated!", $internalMsg);
    }
}

if (!function_exists("errScheduleAlreadyExist")) {
    function errScheduleAlreadyExist($internalMsg = "")
    {
        error(409, "Schedule Already Exists!", $internalMsg);
    }
}




