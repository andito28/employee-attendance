<?php

if (!function_exists("errShiftGet")) {
    function errShiftGet($internalMsg = "")
    {
        error(404, "Shift Not Found!", $internalMsg);
    }
}


?>
