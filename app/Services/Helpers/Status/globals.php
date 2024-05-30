<?php

if (!function_exists("errDefault")) {
    function errDefault($internalMsg = "", $status = null)
    {
        error(500, "An error occurred!", $internalMsg, $status);
    }
}
