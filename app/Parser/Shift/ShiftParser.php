<?php

namespace App\Parser\Shift;

use GlobalXtreme\Parser\BaseParser;

class ShiftParser extends BaseParser
{
    /**
     * @param $data
     *
     * @return array|null
     */
    public static function first($data)
    {
        if (!$data) {
            return null;
        }

        return parent::first($data);
    }

}
