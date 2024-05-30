<?php

if (!function_exists("errComponentCompanyOfficeGet")) {
    function errComponentCompanyOfficeGet($internalMsg = "")
    {
        error(404, "Company office not found!", $internalMsg);
    }
}
