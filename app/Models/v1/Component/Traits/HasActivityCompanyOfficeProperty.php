<?php

namespace App\Models\v1\Component\Traits;

use App\Models\v1\Activity\Traits\HasActivity;
use App\Services\Constant\Activity\ActivityType;

trait HasActivityCompanyOfficeProperty
{
    use HasActivity;


    /**
     * @return string
     */
    public function getActivityType(): string
    {
        return ActivityType::GENERAL;
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

        return [];
    }

}
