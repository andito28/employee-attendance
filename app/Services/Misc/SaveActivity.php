<?php

namespace App\Services\Misc;

use App\Models\v1\Activity\Activity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaveActivity
{
    /**
     * @param Activity $activity
     */
    public function __construct(public Activity $activity)
    {
    }


    /**
     * @param string $type
     *
     * @return SaveActivity
     */
    public function setType(string $type)
    {
        $this->activity->type = $type;
        return $this;
    }

    /**
     * @param string $subType
     *
     * @return SaveActivity
     */
    public function setSubType(string $subType)
    {
        $this->activity->subType = $subType;
        return $this;
    }

    /**
     * @param string $action
     *
     * @return SaveActivity
     */
    public function setAction(string $action)
    {
        $this->activity->action = $action;
        return $this;
    }

    /**
     * @return SaveActivity
     */
    public function setCaused()
    {
        // TODO: Change to auth_company_office_ids() after install globalxtreme/laravel-identifier.
//        if ($user = auth_user()) {
//            $this->activity->causedBy = $user['id'];
//            $this->activity->causedByName = $user['fullName'];
//        }

        return $this;
    }

    /**
     * @param Model $reference
     *
     * @return SaveActivity
     */
    public function setReference(Model $reference)
    {
        $this->activity->referable()->associate($reference);
        return $this;
    }

    /**
     * @param string $description
     *
     * @return SaveActivity
     */
    public function setDescription(string $description)
    {
        $this->activity->description = $description;
        return $this;
    }

    /**
     * @param array|string $properties
     *
     * @return SaveActivity
     */
    public function setProperties(array|string $properties)
    {
        $this->activity->properties = is_string($properties) ? [$properties] : $properties;
        return $this;
    }

    /**
     * @param Carbon $date
     *
     * @return SaveActivity
     */
    public function setCreatedAt(Carbon $date)
    {
        $this->activity->{Activity::CREATED_AT} = $date;
        return $this;
    }

    /**
     * @param string|null $description
     *
     * @return Activity
     */
    public function log(string|null $description = null)
    {
        if (!$this->activity->causedBy) {

            // TODO: Change to auth_company_office_ids() after install globalxtreme/laravel-identifier.
//            if ($user = auth_user()) {
//                $this->activity->causedBy = $user['id'];
//                $this->activity->causedByName = $user['fullName'];
//            }

        }

        if ($description) {
            $this->setDescription($description);
        }

        $this->activity->save();
        return $this->activity;
    }

}
