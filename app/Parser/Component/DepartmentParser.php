<?php

namespace App\Parser\Component;

use GlobalXtreme\Parser\BaseParser;

class DepartmentParser extends BaseParser
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
