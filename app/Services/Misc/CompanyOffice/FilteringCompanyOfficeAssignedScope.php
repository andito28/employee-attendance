<?php

namespace App\Services\Misc\CompanyOffice;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilteringCompanyOfficeAssignedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Change to auth_company_office_ids() after install globalxtreme/laravel-identifier.
        $companyOfficeIds = request()->companyOfficeIds ?: [];
        if (count($companyOfficeIds) > 0) {
            $builder->where(function ($query) use ($model, $companyOfficeIds) {
                $query->whereIn($model->getCompanyOfficeIdColumn(), $companyOfficeIds)->orWhereNull($model->getCompanyOfficeIdColumn());
            });
        }
    }
}
