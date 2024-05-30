<?php

namespace App\Services\Misc\CompanyOffice;

trait FilteringCompanyOfficeAssigned
{
    public static function bootFilteringCompanyOfficeAssigned()
    {
        static::addGlobalScope(new FilteringCompanyOfficeAssignedScope());
    }
}
