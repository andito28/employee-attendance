<?php

if (!function_exists("errNationalHolidayGet")) {
    function errNationalHolidayGet($internalMsg = "")
    {
        error(404, "National Holiday not found!", $internalMsg);
    }
}

?>
