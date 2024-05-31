<?php

use App\Models\Activity\Activity;
use App\Services\Misc\SaveActivity;

if (!function_exists("activity")) {

    /**
     * @param string|null $log
     *
     * @return SaveActivity
     */
    function activity(string|null $log = null): SaveActivity
    {
        $activity = new SaveActivity(new Activity);

        if ($log) {
            $activity->setDescription($log);
        }

        return $activity;
    }

}
