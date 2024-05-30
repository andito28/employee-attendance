<?php

if (!function_exists("errLeaveValidateDate")) {
    function errLeaveValidateDate($internalMsg = "")
    {
        error(400, "Invalid Date!", $internalMsg);
    }
}


?>
