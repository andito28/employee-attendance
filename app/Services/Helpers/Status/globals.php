<?php

if (!function_exists("errDefault")) {
    function errDefault($internalMsg = "", $status = null)
    {
        error(500, "An error occurred!", $internalMsg, $status);
    }
}

if (!function_exists("errAuthentication")) {
    function errAuthentication($internalMsg = "", $status = null)
    {
        error(401, "Unauthenticated!", $internalMsg, $status);
    }
}


if (!function_exists("errAccessPemission")) {
    function errAccessPemission($internalMsg = "", $status = null)
    {
        error(403, "No Access!", $internalMsg, $status);
    }
}
