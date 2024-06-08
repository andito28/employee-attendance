<?php

namespace App\Services\Number\Generator;

use Illuminate\Support\Facades\DB;
use App\Services\Number\BaseNumber;
use Illuminate\Database\Eloquent\Model;

class EmployeeNumber extends BaseNumber
{
    /**
     * @var string
     */
    protected static string $prefix = "EMP";

    /**
     * @var Model|string|null
     */
    protected Model|string|null $model = null;


    public static function generateNumber(): string
    {

        $datePrefix = date('YmdHis');

        $lastNumber = DB::table('employees')
            ->whereNotNull('number')
            ->where('number', 'LIKE', self::$prefix . $datePrefix . '%')
            ->orderBy('number', 'desc')
            ->value('number');

        if ($lastNumber) {
            $lastSequence = (int) substr($lastNumber, strlen(self::$prefix . $datePrefix)) + 1;
        } else {
            $lastSequence = 1;
        }

        $newNumber = self::$prefix . $datePrefix . str_pad($lastSequence, 3, '0', STR_PAD_LEFT);

        return $newNumber;
    }

}
