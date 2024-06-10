<?php

if (!function_exists("errShiftGet")) {
    function errShiftGet($internalMsg = "", $status = null)
    {
        error(404, "Shift not found!", $internalMsg, $status);
    }
}
