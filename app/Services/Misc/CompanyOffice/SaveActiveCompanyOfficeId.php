<?php

namespace App\Services\Misc\CompanyOffice;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait SaveActiveCompanyOfficeId
{
    public static function bootSaveActiveCompanyOfficeId()
    {
        static::creating(function ($model) {
            self::setCompanyOfficeId($model);
        });
    }


    /** --- FUNCTIONS --- */

    private static function setCompanyOfficeId($model): void
    {
        if ($companyOffice = request()->companyOffice ?: null) { // TODO: Change to auth_company_office()

            if (method_exists($model, 'getCompanyOfficeIdColumn') &&
                Schema::connection($model->getConnectionName())->hasColumn($model->getTable(), $model->getCompanyOfficeIdColumn()) &&
                $model[$model->getCompanyOfficeIdColumn()] == NULL) {
                $model[$model->getCompanyOfficeIdColumn()] = $companyOffice['id'];
            }

            if (method_exists($model, 'getCompanyOfficeNameColumn') &&
                Schema::connection($model->getConnectionName())->hasColumn($model->getTable(), $model->getCompanyOfficeNameColumn()) &&
                $model[$model->getCompanyOfficeNameColumn()] == NULL) {
                $model[$model->getCompanyOfficeNameColumn()] = $companyOffice['name'];
            }

        }
    }

}
