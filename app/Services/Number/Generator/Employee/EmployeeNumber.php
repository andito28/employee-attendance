<?php

namespace App\Services\Number\Generator\Employee;

use App\Services\Number\BaseNumber;
use Illuminate\Database\Eloquent\Model;

class EmployeeNumber extends BaseNumber
{
    /**
     * @var string
     */
    protected static string $prefix = "GX";

    /**
     * @var Model|string|null
     */
    protected Model|string|null $model = null;

}
