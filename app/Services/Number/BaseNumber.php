<?php

namespace App\Services\Number;

use App\Services\Number\Contract\NumberGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BaseNumber implements NumberGenerator
{
    /**
     * @var string
     */
    protected static string $prefix = "GX";

    /**
     * @var Model|string|null
     */
    protected Model|string|null $model = null;


    /**
     * @return string
     */
    public static function generate(): string
    {
        $static = new static();

        $date = now();
        $number = $date->format('myd');

        // Ex: 0122010003
        if (!$static->model) {
            $number .= $date->format('Hi');
        } else {
            $increment = static::getIncrementNumber();
            $number .= str_pad($increment, 4, '0', STR_PAD_LEFT);
        }

        // Ex: 0122010003325
        $number .= rand(111, 999);

        return strtoupper(static::$prefix . $number);
    }


    /** --- FUNCTIONS --- */

    /**
     * @param bool $checkMonth
     *
     * @return int
     */
    protected static function getIncrementNumber(bool $checkMonth = true): int
    {
        try {

            return (new static())->model::withTrashed()
                    ->where(function ($query) use ($checkMonth) {

                        if ($checkMonth) {
                            $date = now();

                            $query->whereMonth('createdAt', $date->month)
                                ->whereYear('createdAt', $date->year);
                        }

                    })->count() + 1;

        } catch (\Exception $exception) {
            exception($exception);
        }
    }

}
