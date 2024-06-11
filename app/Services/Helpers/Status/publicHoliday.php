<?php

if (!function_exists("errPublicHolidayGet")) {
    function errPublicHolidayGet($internalMsg = "", $status = null)
    {
        error(404, "Public Holiday not found!", $internalMsg, $status);
    }
}
