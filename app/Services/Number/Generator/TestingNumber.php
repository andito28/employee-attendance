<?php

namespace App\Services\Number\Generator;

use App\Services\Number\BaseNumber;
use Illuminate\Database\Eloquent\Model;

class TestingNumber extends BaseNumber
{
    /**
     * @var string
     */
    protected static string $prefix = "TEST";

    /**
     * @var Model|string|null
     */
    protected Model|string|null $model = null;

}
