<?php

namespace App\Models\PublicHoliday\Traits;

use App\Models\Activity\Traits\HasActivity;
use App\Models\PublicHoliday\PublicHoliday;
use App\Services\Constant\Activity\ActivityType;

trait HasActivityPublicHolidayActivityProperty
{
    use HasActivity;


    /**
     * @return string
     */
    public function getActivityType(): string
    {
        return ActivityType::PUBLIC_HOLIDAY;
    }

    /**
     * @return string
     */
    public function getActivitySubType(): string
    {
        return '';
    }


    /**
     * @return array
     */
    public function getActivityPropertyCreate()
    {
        return $this->setActivityPropertyParser();
    }

    /**
     * @return array
     */
    public function getActivityPropertyUpdate()
    {
        return $this->setActivityPropertyParser();
    }

    /**
     * @return array
     */
    public function getActivityPropertyDelete()
    {
        return $this->setActivityPropertyParser() + [
                'deletedAt' => $this->deletedAt?->format('d/m/Y H:i')
            ];
    }


    /** --- FUNCTIONS --- */

    /**
     * @return array|null
     */
    private function setActivityPropertyParser()
    {
        $this->refresh();

        return PublicHoliday::first($this);
    }

}
